import React, { useEffect, useMemo, useState } from "react";
import { Link } from "@inertiajs/react";
import PhotoLightbox from "../../../PhotoLightbox";

export default function PhotoGallerySection({ ajaxUrl = null, seeMoreHref = null, detailsBaseHref = "/photo-gallery" }) {
    const [items, setItems] = useState([]);
    const [isLoading, setIsLoading] = useState(Boolean(ajaxUrl));
    const [loadError, setLoadError] = useState(null);
    const [activeIndex, setActiveIndex] = useState(null);

    useEffect(() => {
        if (!ajaxUrl) return;

        let isMounted = true;
        const controller = new AbortController();

        const load = async () => {
            setIsLoading(true);
            setLoadError(null);

            try {
                const response = await fetch(ajaxUrl, {
                    method: "GET",
                    headers: { Accept: "application/json" },
                    signal: controller.signal,
                });

                const data = await response.json().catch(() => ({}));
                if (!response.ok) throw new Error(data?.message || "Failed to load photos");

                if (!isMounted) return;
                setItems(data?.items || []);
            } catch (error) {
                if (!isMounted || error?.name === "AbortError") return;
                setLoadError(error?.message || "Failed to load photos");
                setItems([]);
            } finally {
                if (isMounted) setIsLoading(false);
            }
        };

        load();

        return () => {
            isMounted = false;
            controller.abort();
        };
    }, [ajaxUrl]);

    const photos = useMemo(() => items || [], [items]);

    const openAt = (index) => setActiveIndex(index);
    const close = () => setActiveIndex(null);
    const prev = () => setActiveIndex((prevIndex) => (prevIndex === null ? null : (prevIndex - 1 + photos.length) % photos.length));
    const next = () => setActiveIndex((prevIndex) => (prevIndex === null ? null : (prevIndex + 1) % photos.length));

    return (
        <section className="relative overflow-hidden bg-white py-12 sm:py-16">
            <div className="mx-auto max-w-10/12">
                <div className="mb-6 flex flex-wrap items-center justify-between gap-3">
                    <div>
                        <h2 className="text-2xl font-black uppercase text-[#0f2f45] sm:text-3xl">Photo Gallery</h2>
                        <p className="mt-1 text-sm text-slate-600">Snapshots from OSHE Foundation programs, campaigns, and events.</p>
                    </div>
                    <span className="rounded-full bg-[#0f2f45]/10 px-4 py-2 text-sm font-bold text-[#0f2f45]">{photos.length} Photos</span>
                </div>

                {loadError ? <div className="mb-4 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">{loadError}</div> : null}

                <div className="grid grid-cols-2 gap-4 md:grid-cols-3 xl:grid-cols-4">
                    {isLoading &&
                        Array.from({ length: 8 }).map((_, idx) => (
                            <div key={idx} className="skeleton h-40 w-full rounded-xl sm:h-52" />
                        ))}

                    {!isLoading &&
                        photos.map((photo, index) => (
                            <div key={photo.id} className="group relative overflow-hidden rounded-xl border border-slate-200 bg-slate-100 shadow-sm">
                                <button type="button" onClick={() => openAt(index)} className="block w-full">
                                    <img
                                        src={photo.image_url}
                                        alt={photo.name || "Photo"}
                                        className="h-40 w-full object-cover transition duration-300 group-hover:scale-105 sm:h-52"
                                        loading="lazy"
                                    />
                                </button>
                                <Link
                                    href={`${detailsBaseHref}/${photo.id}`}
                                    className="absolute bottom-2 right-2 rounded-md bg-white/90 px-2.5 py-1 text-xs font-bold text-[#0f2f45] shadow"
                                >
                                    Details
                                </Link>
                            </div>
                        ))}
                </div>

                <div className="pt-8 text-center">
                    {seeMoreHref ? (
                        <Link href={seeMoreHref} className="inline-flex items-center rounded-full bg-[#0f2f45] px-6 py-3 text-sm font-bold uppercase tracking-wide text-white transition hover:bg-[#153f5d]">
                            View Full Gallery
                        </Link>
                    ) : null}
                </div>
            </div>

            <PhotoLightbox items={photos} activeIndex={activeIndex} onClose={close} onPrev={prev} onNext={next} />
        </section>
    );
}
