import React from "react";
import { usePage } from "@inertiajs/react";
import Root from "../component/layout/Root";

export default function NationalPolicyContributions() {
    const { national_policy_settings = {} } = usePage().props;
    const imageSrc = national_policy_settings.page_image || "";

    return (
        <Root>
            <main className="national-policy-page">
                {imageSrc && (
                    <section className="national-policy-image-shell" aria-label="National Policy Contributions">
                        <img
                            src={imageSrc}
                            alt="National Policy Contributions"
                            className="national-policy-image"
                            draggable="false"
                        />
                    </section>
                )}

                <style>{`
                    .national-policy-page {
                        min-height: calc(100vh - 1px);
                        background: #ffffff;
                    }

                    .national-policy-image-shell {
                        display: flex;
                        align-items: flex-start;
                        justify-content: center;
                        width: 100%;
                        min-height: calc(100vh - 1px);
                        overflow-x: hidden;
                        overflow-y: auto;
                        background: #ffffff;
                    }

                    .national-policy-image {
                        display: block;
                        width: 100%;
                        height: auto;
                        max-width: none;
                        object-fit: contain;
                        object-position: top center;
                        user-select: none;
                    }

                    @media (min-aspect-ratio: 3/2) {
                        .national-policy-image {
                            width: 100%;
                            height: auto;
                            object-fit: contain;
                        }
                    }

                    @media (max-width: 900px) {
                        .national-policy-image-shell {
                            justify-content: flex-start;
                            overflow-x: auto;
                            overscroll-behavior-x: contain;
                        }

                        .national-policy-image {
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
