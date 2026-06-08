import React, { useEffect } from "react";
import { ChevronLeftIcon, ChevronRightIcon, XMarkIcon } from "@heroicons/react/24/solid";

export default function PhotoLightbox({ items = [], activeIndex = null, onClose, onPrev, onNext }) {
    useEffect(() => {
        if (activeIndex === null) return;

        const onKeyDown = (event) => {
            if (event.key === "Escape") onClose?.();
            if (event.key === "ArrowLeft") onPrev?.();
            if (event.key === "ArrowRight") onNext?.();
        };

        const previousOverflow = document.body.style.overflow;
        document.body.style.overflow = "hidden";
        window.addEventListener("keydown", onKeyDown);

        return () => {
            document.body.style.overflow = previousOverflow;
            window.removeEventListener("keydown", onKeyDown);
        };
    }, [activeIndex, onClose, onNext, onPrev]);

    if (activeIndex === null || !items[activeIndex]) return null;

    const item = items[activeIndex];

    return (
        <div className="fixed inset-0 z-[90] flex items-center justify-center bg-black/80 px-4" role="dialog" aria-modal="true" aria-label="Photo preview">
            <button type="button" className="absolute inset-0" onClick={onClose} aria-label="Close preview" />

            <div className="relative w-full max-w-6xl overflow-hidden rounded-2xl border border-white/15 bg-slate-950 shadow-2xl">
                <div className="flex items-center justify-between border-b border-white/10 px-4 py-3 text-white">
                    <div className="truncate text-sm font-semibold sm:text-base">{item.name || "Photo"}</div>
                    <button
                        type="button"
                        onClick={onClose}
                        className="inline-flex h-9 w-9 items-center justify-center rounded-md border border-white/20 text-white transition hover:bg-white/10"
                        aria-label="Close"
                    >
                        <XMarkIcon className="h-5 w-5" />
                    </button>
                </div>

                <div className="relative flex max-h-[80vh] min-h-[260px] items-center justify-center bg-black">
                    <img src={item.image_url} alt={item.name || "Photo"} className="max-h-[80vh] w-auto max-w-full object-contain" />

                    {items.length > 1 && (
                        <>
                            <button
                                type="button"
                                onClick={onPrev}
                                className="absolute left-3 inline-flex h-11 w-11 items-center justify-center rounded-full border border-white/25 bg-black/35 text-white transition hover:bg-black/55"
                                aria-label="Previous photo"
                            >
                                <ChevronLeftIcon className="h-6 w-6" />
                            </button>
                            <button
                                type="button"
                                onClick={onNext}
                                className="absolute right-3 inline-flex h-11 w-11 items-center justify-center rounded-full border border-white/25 bg-black/35 text-white transition hover:bg-black/55"
                                aria-label="Next photo"
                            >
                                <ChevronRightIcon className="h-6 w-6" />
                            </button>
                        </>
                    )}
                </div>
            </div>
        </div>
    );
}
