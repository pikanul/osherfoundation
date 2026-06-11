import React from "react";
import { usePage } from "@inertiajs/react";
import Root from "../component/layout/Root";

export default function WhatWeDoImagePage({ pageKey = "", title = "What We Do" }) {
    const { what_we_do_settings = {} } = usePage().props;
    const imageSrc = what_we_do_settings[pageKey] || "";

    return (
        <Root>
            <main className="what-we-do-page">
                {imageSrc && (
                    <section className="what-we-do-image-shell" aria-label={title}>
                        <img
                            src={imageSrc}
                            alt={title}
                            className="what-we-do-image"
                            draggable="false"
                        />
                    </section>
                )}

                <style>{`
                    .what-we-do-page {
                        min-height: calc(100vh - 1px);
                        background: #ffffff;
                    }

                    .what-we-do-image-shell {
                        display: flex;
                        align-items: flex-start;
                        justify-content: center;
                        width: 100%;
                        min-height: calc(100vh - 1px);
                        overflow-x: hidden;
                        overflow-y: auto;
                        background: #ffffff;
                    }

                    .what-we-do-image {
                        display: block;
                        width: 100%;
                        height: auto;
                        max-width: none;
                        object-fit: contain;
                        object-position: top center;
                        user-select: none;
                    }

                    @media (min-aspect-ratio: 3/2) {
                        .what-we-do-image {
                            width: 100%;
                            height: auto;
                            object-fit: contain;
                        }
                    }

                    @media (max-width: 900px) {
                        .what-we-do-image-shell {
                            justify-content: flex-start;
                            overflow-x: auto;
                            overscroll-behavior-x: contain;
                        }

                        .what-we-do-image {
                            width: auto;
                            max-width: none;
                            min-width: 1180px;
                            height: calc(100vh - 1px);
                            object-fit: contain;
                        }
                    }
                `}</style>
            </main>
        </Root>
    );
}
