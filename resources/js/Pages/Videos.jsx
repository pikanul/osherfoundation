import React, { useMemo } from "react";
import { usePage } from "@inertiajs/react";
import Root from "../component/layout/Root";
import VideoGallery from "../component/pages/Home/sections/VideoGallery";
import Breadcrumb from '../component/breadcrumb';

export default function Videos() {
    const { app_url } = usePage().props;
    const appUrl = useMemo(() => (app_url || "").replace(/\/+$/, ""), [app_url]);
    const withAppUrl = (path) => (appUrl ? `${appUrl}${path}` : path);

    return (
        <Root>
            <Breadcrumb title="Video Gallery" subtitle="Watch our visual stories" summary="Explore videos from OSHE Foundation activities and campaigns." />
    

            <VideoGallery ajaxUrl={withAppUrl("/ajax/youtube-videos?per_page=50")} seeMoreHref={null} />
        </Root>
    );
}

