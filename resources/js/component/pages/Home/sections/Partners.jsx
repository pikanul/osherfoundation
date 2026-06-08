import React, { useEffect, useMemo, useState } from "react";
import { usePage } from "@inertiajs/react";
import { Swiper, SwiperSlide } from "swiper/react";
import { A11y, Autoplay, FreeMode } from "swiper/modules";
import "swiper/css";

export default function Partners({ logos = [], ajaxUrl = null }) {
    const { props } = usePage();
    const { partner_title } = props;
    const [remoteItems, setRemoteItems] = useState(null);
    const [isFetching, setIsFetching] = useState(Boolean(ajaxUrl));
    const [isPreloading, setIsPreloading] = useState(false);
    const [loadError, setLoadError] = useState(null);

    const logoItems = useMemo(() => {
        if (ajaxUrl) {
            return (remoteItems || []).map((c) => ({
                id: c.id,
                name: c.company_name || c.name || "Partner",
                image: c.image_url,
            }));
        }
        return (logos || []).map((src, idx) => ({
            id: idx,
            name: "Partner",
            image: src,
        }));
    }, [ajaxUrl, remoteItems, logos]);

    useEffect(() => {
        if (!ajaxUrl) return;

        let isMounted = true;
        const controller = new AbortController();

        const load = async () => {
            setIsFetching(true);
            setLoadError(null);
            try {
                const r = await fetch(ajaxUrl, {
                    method: "GET",
                    headers: { Accept: "application/json" },
                    signal: controller.signal,
                });
                const data = await r.json().catch(() => ({}));
                if (!r.ok) throw new Error(data?.message || "Failed to load partners");

                if (!isMounted) return;
                setRemoteItems(data?.items || data?.clients || []);
                setIsPreloading(true);
            } catch (e) {
                if (!isMounted) return;
                if (e?.name === "AbortError") return;
                setLoadError(e?.message || "Failed to load partners");
                setRemoteItems([]);
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

        if (!logoItems.length) {
            setIsPreloading(false);
            return () => {
                isMounted = false;
            };
        }

        setIsPreloading(true);
        const preload = async () => {
            try {
                await Promise.all(
                    logoItems
                        .filter((l) => l.image)
                        .map(
                            (l) =>
                                new Promise((resolve) => {
                                    const img = new Image();
                                    img.onload = resolve;
                                    img.onerror = resolve;
                                    img.src = l.image;
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
    }, [logoItems]);

    const isLoading = isFetching || isPreloading;

    return (
        <section className="relative overflow-hidden bg-slate-50 py-10 sm:py-14">
            <div className="pointer-events-none absolute inset-0 bg-[radial-gradient(circle_at_10%_20%,rgba(15,47,69,0.08),transparent_40%),radial-gradient(circle_at_85%_10%,rgba(251,191,36,0.12),transparent_35%)]" />

            <div className="relative mx-auto max-w-10/12">
                <div className="mb-6 text-center sm:mb-8">
                    <h2 className="text-3xl font-black uppercase tracking-wide text-[#0f2f45] sm:text-4xl">
                        {partner_title}
                    </h2>
                    <div className="mx-auto mt-4 h-1.5 w-24 rounded-full bg-amber-400" />
                </div>

                {loadError ? <div className="mb-4 text-center text-sm text-red-600">{loadError}</div> : null}

                <div className="relative overflow-hidden py-4  sm:py-6">
                    {isLoading ? (
                        <div className="grid grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-5 lg:grid-cols-6">
                            {Array.from({ length: 8 }).map((_, idx) => (
                                <div key={idx} className="skeleton h-20 w-full rounded-xl" />
                            ))}
                        </div>
                    ) : null}

                    {!isLoading && !logoItems.length ? (
                        <div className="py-10 text-center text-sm text-slate-600">No partners found.</div>
                    ) : null}

                    {!isLoading && logoItems.length > 0 ? (
                        <>
                            <Swiper
                                modules={[A11y, Autoplay, FreeMode]}
                                loop={logoItems.length > 4}
                                speed={7000}
                                spaceBetween={16}
                                freeMode={{
                                    enabled: true,
                                    momentum: false,
                                }}
                                autoplay={{
                                    delay: 0,
                                    disableOnInteraction: false,
                                    pauseOnMouseEnter: true,
                                }}
                                allowTouchMove={false}
                                breakpoints={{
                                    0: { slidesPerView: 2 },
                                    640: { slidesPerView: 3 },
                                    768: { slidesPerView: 4 },
                                    1024: { slidesPerView: 5 },
                                    1280: { slidesPerView: 6 },
                                }}
                                className="partners-swiper-marquee w-full"
                            >
                                {logoItems.map((l) => (
                                    <SwiperSlide key={l.id}>
                                        <div className="group flex h-24 items-center justify-center rounded-xl border border-slate-200 bg-slate-50 p-1 transition hover:border-[#0f2f45]/35 hover:bg-white">
                                            <img
                                                src={l.image}
                                                alt={l.name}
                                                className="max-h-16 w-full object-contain opacity-85 grayscale transition duration-300 group-hover:opacity-100 group-hover:grayscale-0"
                                                loading="lazy"
                                            />
                                        </div>
                                    </SwiperSlide>
                                ))}
                            </Swiper>

                        </>
                    ) : null}
                </div>

                <style>{`
                    .partners-swiper-marquee .swiper-wrapper {
                        transition-timing-function: linear !important;
                    }
                `}</style>

                <div className="mt-6 rounded-2xl border border-[#0f2f45]/20 bg-gradient-to-br from-[#0f2f45] to-[#153c56] p-6 text-slate-100 shadow-xl sm:p-8">
                    <p className="text-base leading-relaxed sm:text-lg">
                        The OSHE Foundation actively fosters partnerships with diverse platforms and networks to enhance
                        its impact and achieve its mission and vision through a collaborative approach. Through these
                        collaborations, we work together to promote social justice and empower workers in both the
                        formal and informal economies, as well as within the global supply chain.
                    </p>
                </div>
            </div>
        </section>
    );
}
