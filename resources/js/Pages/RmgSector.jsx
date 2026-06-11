import React from "react";
import { usePage } from "@inertiajs/react";
import Root from "../component/layout/Root";
import JourneyTimeline from "../component/pages/Home/sections/JourneyTimeline";

export default function RmgSector() {
    const { sectoral_coverage_settings = {} } = usePage().props;
    const settings = sectoral_coverage_settings.rmg_sector || {};

    return (
        <Root>
            <JourneyTimeline
                kicker={settings.kicker || "Sectoral Coverage"}
                title={settings.title || "RMG Sector"}
                itemsText={settings.items_text || ""}
                fallbackItems={[]}
            />
        </Root>
    );
}
