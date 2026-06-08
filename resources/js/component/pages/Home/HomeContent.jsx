import React, { useMemo } from "react";
import { usePage } from "@inertiajs/react";

import oshe from "../../../assets/image/oshe.jpg";

import HeroBanner from "./sections/HeroBanner";
import MissionVision from "./sections/MissionVision";
import WorkersPower from "./sections/WorkersPower";
import EoshVictims from "./sections/EoshVictims";
import ProjectsCarousel from "./sections/ProjectsCarousel";
import Partners from "./sections/Partners";
import EventsCTA from "./sections/EventsCTA";
import VideoGallery from "./sections/VideoGallery";
import PhotoGallerySection from "./sections/PhotoGallerySection";

export default function HomeContent() {
    const { app_url, banner_image_heor } = usePage().props;
    const appUrl = useMemo(() => (app_url || "").replace(/\/+$/, ""), [app_url]);
    const withAppUrl = (path) => (appUrl ? `${appUrl}${path}` : path);

    return (
        <div>
            <HeroBanner imageSrc={banner_image_heor} />
            <MissionVision />
            <WorkersPower />
            <EoshVictims />
            <ProjectsCarousel ajaxUrl={withAppUrl("/ajax/sliders?per_page=4")} />
            <EventsCTA eventsHref={withAppUrl("/Events")} />
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
