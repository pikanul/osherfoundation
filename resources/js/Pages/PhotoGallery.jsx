import React, { useMemo, useState } from "react";
import { Link, usePage } from "@inertiajs/react";
import Root from "../component/layout/Root";
import Breadcrumb from "../component/Breadcrumb";
import PhotoLightbox from "../component/PhotoLightbox";

export default function PhotoGallery() {
    const { app_url, initial_photos = [], photos_has_more = false, photos_before_id = null } = usePage().props;
    const [photos, setPhotos] = useState(initial_photos || []);
    const [hasMore, setHasMore] = useState(Boolean(photos_has_more));
    const [beforeId, setBeforeId] = useState(photos_before_id);
    const [loadingMore, setLoadingMore] = useState(false);
    const [activeIndex, setActiveIndex] = useState(null);

    const appUrl = useMemo(() => (app_url || "").replace(/\/+$/, ""), [app_url]);
    const withAppUrl = (path) => (appUrl ? `${appUrl}${path}` : path);

    const loadMore = async () => {
        if (!hasMore || loadingMore) return;

        setLoadingMore(true);
        try {
            const response = await fetch(withAppUrl(`/ajax/photo-gallery?per_page=24&before_id=${beforeId || ""}`), {
                method: "GET",
                headers: { Accept: "application/json" },
            });
            const data = await response.json().catch(() => ({}));
            if (!response.ok) throw new Error(data?.message || "Failed to load more photos");

            const nextItems = data?.items || [];
            setPhotos((prev) => [...prev, ...nextItems]);
            setHasMore(Boolean(data?.has_more));
            setBeforeId(data?.next_before_id || null);
        } catch (error) {
            // Keep UI usable even when request fails.
            setHasMore(false);
        } finally {
            setLoadingMore(false);
        }
    };

    const prev = () => setActiveIndex((prevIndex) => (prevIndex === null ? null : (prevIndex - 1 + photos.length) % photos.length));
    const next = () => setActiveIndex((prevIndex) => (prevIndex === null ? null : (prevIndex + 1) % photos.length));

    return (
        <Root>
            <Breadcrumb title="Photo Gallery" subtitle="Our recent moments" summary="Explore moments captured from OSHE Foundation activities." />

            <section className="bg-slate-50 py-10 sm:py-14">
                <div className="mx-auto max-w-10/12">
                    <div className="mb-6 flex items-center justify-between">
                        <h2 className="text-2xl font-black uppercase text-[#0f2f45] sm:text-3xl">All Photos</h2>
                        <span className="rounded-full bg-[#0f2f45]/10 px-4 py-2 text-sm font-bold text-[#0f2f45]">{photos.length} Photos</span>
                    </div>

                    {!photos.length ? (
                        <div className="rounded-xl border border-slate-200 bg-white py-12 text-center text-slate-600">No photos found.</div>
                    ) : (
                        <div className="grid grid-cols-2 gap-4 md:grid-cols-3 xl:grid-cols-4">
                            {photos.map((photo, index) => (
                                <article key={photo.id} className="group overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
                                    <button type="button" onClick={() => setActiveIndex(index)} className="block w-full">
                                        <img
                                            src={photo.image_url}
                                            alt={photo.name || "Photo"}
                                            className="h-42 w-full object-cover transition duration-300 group-hover:scale-105 sm:h-56"
                                            loading="lazy"
                                        />
                                    </button>
                                    <div className="flex items-center justify-between gap-2 p-3">
                                        <h3 className="line-clamp-1 text-sm font-semibold text-slate-900">{photo.name || `Photo #${photo.id}`}</h3>
                                        <Link href={withAppUrl(`/photo-gallery/${photo.id}`)} className="rounded-md bg-slate-100 px-2.5 py-1 text-xs font-bold text-[#0f2f45] hover:bg-slate-200">
                                            Details
                                        </Link>
                                    </div>
                                </article>
                            ))}
                        </div>
                    )}

                    {hasMore ? (
                        <div className="pt-8 text-center">
                            <button
                                type="button"
                                onClick={loadMore}
                                disabled={loadingMore}
                                className="inline-flex items-center rounded-full bg-[#0f2f45] px-6 py-3 text-sm font-bold uppercase tracking-wide text-white transition hover:bg-[#153f5d] disabled:cursor-not-allowed disabled:opacity-60"
                            >
                                {loadingMore ? "Loading..." : "Load More"}
                            </button>
                        </div>
                    ) : null}
                </div>
            </section>

            <PhotoLightbox items={photos} activeIndex={activeIndex} onClose={() => setActiveIndex(null)} onPrev={prev} onNext={next} />
        </Root>
    );
}
