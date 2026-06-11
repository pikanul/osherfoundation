import React from "react";
import { usePage } from "@inertiajs/react";
import Root from "../component/layout/Root";

const defaultCoreValuesImage = "/assets/core-values/oshes-core-values.png";

export default function OshesCoreValues() {
    const { core_values_settings = {} } = usePage().props;
    const imageSrc = core_values_settings.page_image || defaultCoreValuesImage;

    return (
        <Root>
            <main className="core-values-page">
                <section className="core-values-image-shell" aria-label="OSHE's Core Values">
                    <img
                        src={imageSrc}
                        alt="OSHE's Core Values"
                        className="core-values-image"
                        draggable="false"
                    />
                </section>

                <style>{`
                    .core-values-page {
                        min-height: calc(100vh - 1px);
                        background: #ffffff;
                    }

                    .core-values-image-shell {
                        display: flex;
                        align-items: flex-start;
                        justify-content: center;
                        width: 100%;
                        min-height: calc(100vh - 1px);
                        overflow-x: hidden;
                        overflow-y: auto;
                        background: #ffffff;
                    }

                    .core-values-image {
                        display: block;
                        width: 100%;
                        height: auto;
                        max-width: none;
                        object-fit: contain;
                        object-position: top center;
                        user-select: none;
                    }

                    @media (min-aspect-ratio: 3/2) {
                        .core-values-image {
                            width: 100%;
                            height: auto;
                            object-fit: contain;
                        }
                    }

                    @media (max-width: 900px) {
                        .core-values-image-shell {
                            justify-content: flex-start;
                            overflow-x: auto;
                            overscroll-behavior-x: contain;
                        }

                        .core-values-image {
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
