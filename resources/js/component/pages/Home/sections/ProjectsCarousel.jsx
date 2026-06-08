import React, { useEffect, useMemo, useState, useId } from "react";
import { usePage } from "@inertiajs/react";
import { ChevronLeftIcon, ChevronRightIcon } from "@heroicons/react/24/solid";
import { Swiper, SwiperSlide } from "swiper/react";
import { Navigation, Pagination, A11y, Autoplay } from "swiper/modules";
import "swiper/css";
import "swiper/css/navigation";
import "swiper/css/pagination";

// Prevent duplicate network calls for the same carousel endpoint.
const SLIDE_DATA_CACHE = new Map();
const SLIDE_REQUEST_CACHE = new Map();

function normalizeSlides(slides) {

    const list = Array.isArray(slides) ? slides : [];
    return list
        .map((s) => ({
            image: s?.image || s?.image_url || null,
            title: s?.title || "",
            subTitle: s?.subTitle || s?.sub_title || "",
        }))
        .filter((s) => s.image);
}

export default function ProjectsCarousel({ slides = [], ajaxUrl = null }) {
    const { props } = usePage();
    const { projects_title } = props;
    const [remoteSlides, setRemoteSlides] = useState(null);
    const [isFetching, setIsFetching] = useState(Boolean(ajaxUrl));
    const [isPreloading, setIsPreloading] = useState(false);
    const [loadError, setLoadError] = useState(null);
    const swiperId = useId().replace(/[:]/g, "");
    const nextClass = `projects-swiper-next-${swiperId}`;
    const prevClass = `projects-swiper-prev-${swiperId}`;
    const paginationClass = `projects-swiper-pagination-${swiperId}`;

    const effectiveSlides = useMemo(() => {
        if (ajaxUrl) return normalizeSlides(remoteSlides || []);
        return normalizeSlides(slides || []);
    }, [ajaxUrl, remoteSlides, slides]);

    const slideItems = useMemo(() => {
        return effectiveSlides.map((s, idx) => {
            const id = `slide${idx + 1}`;
            return {
                ...s,
                id,
            };
        });
    }, [effectiveSlides]);

    useEffect(() => {
        if (!ajaxUrl) return;

        let isMounted = true;
        const controller = new AbortController();

        const load = async () => {
            if (SLIDE_DATA_CACHE.has(ajaxUrl)) {
                setRemoteSlides(SLIDE_DATA_CACHE.get(ajaxUrl));
                setLoadError(null);
                setIsFetching(false);
                return;
            }

            setIsFetching(true);
            setLoadError(null);
            try {
                let request = SLIDE_REQUEST_CACHE.get(ajaxUrl);
                if (!request) {
                    request = fetch(ajaxUrl, {
                        method: "GET",
                        headers: { Accept: "application/json" },
                        signal: controller.signal,
                    })
                        .then(async (r) => {
                            const data = await r.json().catch(() => ({}));
                            if (!r.ok) throw new Error(data?.message || "Failed to load sliders");
                            return data?.items || data?.sliders || [];
                        })
                        .finally(() => {
                            SLIDE_REQUEST_CACHE.delete(ajaxUrl);
                        });

                    SLIDE_REQUEST_CACHE.set(ajaxUrl, request);
                }

                const nextSlides = await request;

                if (!isMounted) return;
                SLIDE_DATA_CACHE.set(ajaxUrl, nextSlides);
                setRemoteSlides(nextSlides);
            } catch (e) {
                if (!isMounted) return;
                if (e?.name === "AbortError") return;
                setLoadError(e?.message || "Failed to load sliders");
                setRemoteSlides([]);
                setIsPreloading(false);
            } finally {
                if (!isMounted) return;
                setIsFetching(false);
            }
        };

        load();

        return () => {
            isMounted = false;
            controller.abort();
        };
    }, [ajaxUrl]);

    useEffect(() => {
        let isMounted = true;

        if (!slideItems.length) {
            setIsPreloading(false);
            return () => {
                isMounted = false;
            };
        }

        setIsPreloading(true);
        const preload = async () => {
            try {
                await Promise.all(
                    slideItems.map(
                        (s) =>
                            new Promise((resolve) => {
                                const img = new Image();
                                img.onload = resolve;
                                img.onerror = resolve;
                                img.src = s.image;
                            })
                    )
                );
            } finally {
                if (!isMounted) return;
                setIsPreloading(false);
            }
        };

        preload();
        return () => {
            isMounted = false;
        };
    }, [slideItems]);

    const isLoading = isFetching || isPreloading;

    return (
        <section className="relative overflow-hidden bg-slate-50 py-10 sm:py-14">
            <div className="pointer-events-none absolute inset-0 bg-[radial-gradient(circle_at_12%_20%,rgba(15,47,69,0.08),transparent_40%),radial-gradient(circle_at_88%_10%,rgba(251,191,36,0.12),transparent_35%)]" />

            <div className="relative mx-auto max-w-10/12">
                <div className="mb-6 text-center sm:mb-8">
                    <h2 className="text-3xl font-black uppercase tracking-wide text-[#0f2f45] sm:text-4xl">
                        {projects_title}
                    </h2>
                    <div className="mx-auto mt-4 h-1.5 w-24 rounded-full bg-amber-400" />
                </div>

                {loadError ? <div className="mx-2 my-3 text-sm text-red-600">{loadError}</div> : null}

                <div className="relative overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-xl">
                    {isLoading ? (
                        <div className="relative w-full">
                            <div className="skeleton h-[300px] w-full sm:h-[420px] lg:h-[560px]" />
                        </div>
                    ) : null}

                    {!isLoading && !slideItems.length ? (
                        <div className="relative w-full">
                            <div className="w-full py-12 text-center text-gray-600">No sliders found.</div>
                        </div>
                    ) : null}

                    {!isLoading && slideItems.length > 0 ? (
                        <>
                            <Swiper
                                modules={[Navigation, Pagination, A11y, Autoplay]}
                                loop={slideItems.length > 1}
                                speed={650}
                                autoplay={
                                    slideItems.length > 1
                                        ? { delay: 5000, disableOnInteraction: false, pauseOnMouseEnter: true }
                                        : false
                                }
                                navigation={{
                                    nextEl: `.${nextClass}`,
                                    prevEl: `.${prevClass}`,
                                }}
                                pagination={{
                                    el: `.${paginationClass}`,
                                    clickable: true,
                                }}
                                className="w-full"
                            >
                                {slideItems.map((s) => (
                                    <SwiperSlide key={s.id}>
                                        <div className="relative w-full">
                                            <img src={s.image} className="h-[300px] w-full object-cover sm:h-[420px] lg:h-[560px]" alt={s.title} />

                                            <div className="absolute inset-0 bg-gradient-to-t from-black/60 via-black/25 to-black/10" />

                                            <div className="absolute left-1/2 top-1/2 flex w-[88%] -translate-x-1/2 -translate-y-1/2 flex-col items-center justify-center text-center text-white sm:w-[75%]">
                                                <h3 className="text-2xl font-black drop-shadow sm:text-3xl lg:text-4xl">{s.title}</h3>
                                                {s.subTitle ? <p className="mt-2 text-sm font-semibold text-slate-100 drop-shadow sm:text-lg">{s.subTitle}</p> : null}
                                            </div>
                                        </div>
                                    </SwiperSlide>
                                ))}
                            </Swiper>

                            {slideItems.length > 1 ? (
                                <>
                                    <button
                                        type="button"
                                        className={`${prevClass} absolute left-3 top-1/2 z-20 flex h-10 w-10 -translate-y-1/2 items-center justify-center rounded-full border border-white/50 bg-black/35 text-white backdrop-blur transition hover:bg-black/55 sm:left-5 sm:h-12 sm:w-12`}
                                        aria-label="Previous slide"
                                    >
                                        <ChevronLeftIcon className="h-5 w-5" />
                                    </button>

                                    <button
                                        type="button"
                                        className={`${nextClass} absolute right-3 top-1/2 z-20 flex h-10 w-10 -translate-y-1/2 items-center justify-center rounded-full border border-white/50 bg-black/35 text-white backdrop-blur transition hover:bg-black/55 sm:right-5 sm:h-12 sm:w-12`}
                                        aria-label="Next slide"
                                    >
                                        <ChevronRightIcon className="h-5 w-5" />
                                    </button>

                                    <div className={`${paginationClass} absolute bottom-4 left-1/2 z-20 -translate-x-1/2`} />
                                </>
                            ) : null}
                        </>
                    ) : null}
                </div>
            </div>
        </section>
    );
}

