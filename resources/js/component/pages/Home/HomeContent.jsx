import React, { useMemo } from "react";
import { usePage } from "@inertiajs/react";

import HeroBanner from "./sections/HeroBanner";
import MissionVision from "./sections/MissionVision";
import JourneyTimeline from "./sections/JourneyTimeline";
import OurImpactHome from "./sections/OurImpactHome";
import Partners from "./sections/Partners";
import VideoGallery from "./sections/VideoGallery";
import PhotoGallerySection from "./sections/PhotoGallerySection";

export default function HomeContent() {
    const {
        app_url,
        banner_image_heor,
        impact_home_status,
        impact_home_text,
        impact_home_link,
        impact_image,
    } = usePage().props;
    const appUrl = useMemo(() => (app_url || "").replace(/\/+$/, ""), [app_url]);
    const withAppUrl = (path) => {
        if (!path) return "";
        if (/^(https?:)?\/\//i.test(path) || path.startsWith("#")) return path;
        const normalizedPath = path.startsWith("/") ? path : `/${path}`;

        return appUrl ? `${appUrl}${normalizedPath}` : normalizedPath;
    };

    return (
        <div>
            <HeroBanner imageSrc={banner_image_heor} />
            <MissionVision />
            <JourneyTimeline />
            {String(impact_home_status ?? "1") !== "0" ? (
                <OurImpactHome
                    imageSrc={impact_image}
                    href={withAppUrl(impact_home_link || "/OurImpact")}
                    label={impact_home_text || "Our Impact"}
                />
            ) : null}
            <PhotoGallerySection
                ajaxUrl={withAppUrl("/ajax/photo-gallery?per_page=8")}
                seeMoreHref={withAppUrl("/photo-gallery")}
                detailsBaseHref={withAppUrl("/photo-gallery")}
            />
            <VideoGallery ajaxUrl={withAppUrl("/ajax/youtube-videos?per_page=6")} seeMoreHref={withAppUrl("/videos")} />
            <Partners ajaxUrl={withAppUrl("/ajax/clients?per_page=7")} />
        </div>
    );
}
