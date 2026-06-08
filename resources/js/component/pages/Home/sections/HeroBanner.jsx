import React, { useEffect, useMemo, useState } from "react";

export default function HeroBanner({ imageSrc }) {
    const images = useMemo(() => {
        if (Array.isArray(imageSrc)) {
            return imageSrc.filter(Boolean);
        }

        return imageSrc ? [imageSrc] : [];
    }, [imageSrc]);

    const [activeIndex, setActiveIndex] = useState(0);
    const hasMultipleImages = images.length > 1;

    useEffect(() => {
        setActiveIndex(0);
    }, [images.length]);

    useEffect(() => {
        if (!hasMultipleImages) {
            return undefined;
        }

        const timer = window.setInterval(() => {
            setActiveIndex((index) => (index + 1) % images.length);
        }, 5000);

        return () => window.clearInterval(timer);
    }, [hasMultipleImages, images.length]);

    if (images.length === 0) {
        return null;
    }

    return (
        <div className="relative w-full">
            <img
                className="w-full h-auto object-contain"
                src={images[activeIndex]}
                alt="OSHE"
            />
            {hasMultipleImages && (
                <div className="absolute bottom-4 left-0 right-0 flex items-center justify-center gap-2">
                    {images.map((image, index) => (
                        <button
                            key={`${image}-${index}`}
                            type="button"
                            aria-label={`Show hero image ${index + 1}`}
                            onClick={() => setActiveIndex(index)}
                            className={`h-2.5 w-2.5 rounded-full border border-white transition ${
                                activeIndex === index ? "bg-white" : "bg-white/40"
                            }`}
                        />
                    ))}
                </div>
            )}
        </div>
    );
}
