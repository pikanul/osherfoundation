import React, { useMemo, useState } from "react";
import { Link, usePage } from "@inertiajs/react";
import Root from "../component/layout/Root";
import Breadcrumb from "../component/Breadcrumb";
import PhotoLightbox from "../component/PhotoLightbox";

export default function PhotoGalleryDetails() {
    const { app_url, photo, related_photos = [], next_photo_id = null, prev_photo_id = null } = usePage().props;
    const [activeIndex, setActiveIndex] = useState(null);

    const appUrl = useMemo(() => (app_url || "").replace(/\/+$/, ""), [app_url]);
    const withAppUrl = (path) => (appUrl ? `${appUrl}${path}` : path);

    const lightboxItems = related_photos.length ? related_photos : photo ? [photo] : [];
    const currentPhotoIndex = lightboxItems.findIndex((item) => item.id === photo?.id);

    const prev = () => setActiveIndex((prevIndex) => (prevIndex === null ? null : (prevIndex - 1 + lightboxItems.length) % lightboxItems.length));
    const next = () => setActiveIndex((prevIndex) => (prevIndex === null ? null : (prevIndex + 1) % lightboxItems.length));

    if (!photo) {
        return (
            <Root>
                <section className="py-16 text-center text-slate-600">Photo not found.</section>
            </Root>
        );
    }

    return (
        <Root>
            <Breadcrumb title="Photo Details" subtitle="Single photo view" summary="View image details and explore related gallery photos." />

            <section className="bg-white py-10 sm:py-14">
                <div className="mx-auto grid max-w-10/12 gap-8 lg:grid-cols-[1.35fr_1fr]">
                    <article className="overflow-hidden rounded-2xl border border-slate-200 bg-slate-50 shadow-sm">
                        <button type="button" onClick={() => setActiveIndex(currentPhotoIndex >= 0 ? currentPhotoIndex : 0)} className="block w-full">
                            <img src={photo.image_url} alt={photo.name || "Photo"} className="max-h-[72vh] w-full object-contain bg-black/5" />
                        </button>

                        <div className="border-t border-slate-200 p-4 sm:p-6">
                            <h1 className="text-xl font-black text-[#0f2f45] sm:text-2xl">{photo.name || `Photo #${photo.id}`}</h1>
                            <p className="mt-2 text-sm text-slate-600">Click the photo to open interactive popup gallery.</p>
                        </div>
                    </article>

                    <aside className="space-y-4">
                        <div className="rounded-2xl border border-slate-200 bg-slate-50 p-4 sm:p-5">
                            <h2 className="text-base font-bold uppercase tracking-wide text-[#0f2f45]">Navigation</h2>
                            <div className="mt-4 flex flex-wrap gap-2">
                                {prev_photo_id ? (
                                    <Link href={withAppUrl(`/photo-gallery/${prev_photo_id}`)} className="rounded-full border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-100">
                                        Previous
                                    </Link>
                                ) : null}
                                {next_photo_id ? (
                                    <Link href={withAppUrl(`/photo-gallery/${next_photo_id}`)} className="rounded-full border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-100">
                                        Next
                                    </Link>
                                ) : null}
                                <Link href={withAppUrl("/photo-gallery")} className="rounded-full bg-[#0f2f45] px-4 py-2 text-sm font-bold text-white hover:bg-[#153f5d]">
                                    Back to Gallery
                                </Link>
                            </div>
                        </div>

                        <div className="rounded-2xl border border-slate-200 bg-white p-4 sm:p-5">
                            <h2 className="mb-3 text-base font-bold uppercase tracking-wide text-[#0f2f45]">Related Photos</h2>
                            <div className="grid grid-cols-3 gap-2 sm:grid-cols-4">
                                {lightboxItems.map((item, index) => (
                                    <button
                                        key={item.id}
                                        type="button"
                                        onClick={() => setActiveIndex(index)}
                                        className="overflow-hidden rounded-md border border-slate-200"
                                    >
                                        <img src={item.image_url} alt={item.name || "Photo"} className="h-18 w-full object-cover transition hover:scale-105" loading="lazy" />
                                    </button>
                                ))}
                            </div>
                        </div>
                    </aside>
                </div>
            </section>

            <PhotoLightbox items={lightboxItems} activeIndex={activeIndex} onClose={() => setActiveIndex(null)} onPrev={prev} onNext={next} />
        </Root>
    );
}
