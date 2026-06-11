import React, { useMemo, useState } from "react";
import { usePage } from "@inertiajs/react";

const treeImage = "/assets/partners/strategic-partners-tree.png";

export default function ProjectPartners() {
    const { strategic_partners_settings = {} } = usePage().props;
    const [animationEnabled, setAnimationEnabled] = useState(true);
    const settings = {
        background_image: strategic_partners_settings.background_image || treeImage,
        oshe_logo: strategic_partners_settings.oshe_logo || "/assets/header/oshe-d-logo-trimmed.png",
        title: strategic_partners_settings.title || "STRATEGIC PARTNERS & DONORS",
        subtitle: strategic_partners_settings.subtitle || "Building change through collaboration",
        tree_text: strategic_partners_settings.tree_text || "Stronger Partnerships for a Better Tomorrow",
        root_label: strategic_partners_settings.root_label || "Together for Change",
        cta_title: strategic_partners_settings.cta_title || "Together We Can Create Safe & Sustainable Workplaces",
        cta_description: strategic_partners_settings.cta_description || "Partner with OSHE and be part of our mission to promote workers' rights, safety, health and social justice.",
        cta_button_text: strategic_partners_settings.cta_button_text || "Become a Partner",
        cta_button_link: strategic_partners_settings.cta_button_link || "/partner-with-us",
    };
    const titleParts = useMemo(() => {
        const title = String(settings.title);
        const match = title.match(/^(.*?)(PARTNERS\s*&\s*DONORS)(.*)$/i);

        if (!match) return [title, ""];

        return [`${match[1]}`.trim(), `${match[2]}${match[3] || ""}`.trim()];
    }, [settings.title]);

    return (
        <main className="bg-white">
            <style>{`
                .strategic-tree-shell {
                    background: linear-gradient(180deg, #ffffff 0%, #f4fbf8 100%);
                }
                .strategic-tree-stage {
                    position: relative;
                    width: 100%;
                    overflow: hidden;
                    background: #fff;
                }
                .strategic-tree-stage img {
                    display: block;
                    width: 100%;
                    height: auto;
                    user-select: none;
                    image-rendering: auto;
                }
                .strategic-title-cleaner {
                    position: absolute;
                    left: 0;
                    right: 0;
                    top: 0;
                    height: 17.6%;
                    z-index: 3;
                    background:
                        radial-gradient(circle at 50% 76%, rgba(232, 246, 239, .42), transparent 34%),
                        linear-gradient(180deg, rgba(255,255,255,.98) 0%, rgba(255,255,255,.96) 72%, rgba(255,255,255,.80) 100%);
                    pointer-events: none;
                }
                .strategic-lower-cleaner,
                .strategic-center-cleaner {
                    position: absolute;
                    z-index: 3;
                    pointer-events: none;
                }
                .strategic-center-cleaner {
                    left: 42.05%;
                    top: 66.9%;
                    width: 15.9%;
                    height: 15.8%;
                    border-radius: 1.1vw;
                    background:
                        linear-gradient(180deg, rgba(112, 68, 31, .99) 0 48%, rgba(7, 137, 78, .99) 48% 100%);
                    box-shadow: 0 .45vw 1.3vw rgba(74, 45, 17, .2);
                }
                .strategic-lower-cleaner {
                    left: 10.05%;
                    right: 10.05%;
                    top: 81.15%;
                    height: 16.55%;
                    border-radius: 1.5vw;
                    background: linear-gradient(90deg, #006943, #087a4f 56%, #009061);
                    box-shadow: none;
                }
                .strategic-crisp-title {
                    position: absolute;
                    left: 0;
                    right: 0;
                    top: 6.9%;
                    z-index: 4;
                    text-align: center;
                    text-transform: uppercase;
                    letter-spacing: .055em;
                    font-weight: 950;
                    font-size: clamp(24px, 3.05vw, 60px);
                    line-height: 1;
                    color: #071931;
                    text-shadow: 0 2px 7px rgba(255,255,255,.95);
                    pointer-events: none;
                    overflow-wrap: anywhere;
                    text-wrap: balance;
                }
                .strategic-crisp-title span {
                    color: #05713f;
                }
                .strategic-crisp-subtitle {
                    position: absolute;
                    left: 0;
                    right: 0;
                    top: 12.6%;
                    z-index: 4;
                    text-align: center;
                    font-size: clamp(12px, 1.06vw, 21px);
                    font-weight: 800;
                    color: #10213a;
                    text-shadow: 0 2px 5px rgba(255,255,255,.9);
                    pointer-events: none;
                    overflow-wrap: anywhere;
                    text-wrap: balance;
                }
                .strategic-crisp-tree-text,
                .strategic-crisp-root-label {
                    position: absolute;
                    z-index: 6;
                    left: 42.85%;
                    width: 14.3%;
                    text-align: center;
                    font-weight: 950;
                    pointer-events: none;
                    text-shadow: 0 2px 4px rgba(0,0,0,.2);
                    overflow-wrap: anywhere;
                    text-wrap: balance;
                }
                .strategic-crisp-tree-text {
                    top: 67.85%;
                    border-radius: .75vw;
                    background: transparent;
                    color: #fff;
                    padding: .72vw .8vw;
                    font-size: clamp(10px, 1.04vw, 20px);
                    line-height: 1.14;
                }
                .strategic-crisp-root-label {
                    top: 76.2%;
                    border-radius: .7vw;
                    background: transparent;
                    color: #fff;
                    padding: .52vw .7vw;
                    font-size: clamp(11px, 1.25vw, 24px);
                    line-height: 1.1;
                }
                .strategic-crisp-cta {
                    position: absolute;
                    z-index: 5;
                    left: 10.25%;
                    right: 10.25%;
                    top: 81.15%;
                    min-height: 16.55%;
                    border-radius: 1.5vw;
                    background: transparent;
                    box-shadow: none;
                    color: #fff;
                    display: grid;
                    grid-template-columns: 8% 30% 1px 34% 18%;
                    align-items: center;
                    gap: 2.1%;
                    padding: .95vw 2.1%;
                }
                .strategic-crisp-cta-icon {
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    width: 4.7vw;
                    max-width: 88px;
                    min-width: 46px;
                    aspect-ratio: 1;
                    border-radius: 999px;
                    background: #fff;
                    color: #087a4f;
                    font-size: clamp(24px, 2.4vw, 46px);
                    font-weight: 900;
                }
                .strategic-crisp-cta h2 {
                    font-size: clamp(15px, 1.46vw, 29px);
                    line-height: 1.16;
                    font-weight: 950;
                    margin: 0;
                    overflow-wrap: anywhere;
                    text-wrap: balance;
                }
                .strategic-crisp-cta-line {
                    height: 68%;
                    background: rgba(255,255,255,.32);
                }
                .strategic-crisp-cta p {
                    margin: 0;
                    font-size: clamp(10px, .86vw, 17px);
                    line-height: 1.4;
                    font-weight: 750;
                    color: rgba(255,255,255,.92);
                    overflow-wrap: anywhere;
                    text-wrap: balance;
                }
                .strategic-crisp-cta a {
                    justify-self: end;
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                    gap: .75vw;
                    width: 100%;
                    max-width: 300px;
                    min-height: 3.25vw;
                    border-radius: .8vw;
                    background: #fff;
                    color: #08734d;
                    font-size: clamp(12px, 1vw, 19px);
                    font-weight: 950;
                    text-decoration: none;
                    box-shadow: 0 .6vw 1.2vw rgba(0,0,0,.14);
                    text-align: center;
                    white-space: normal;
                    overflow-wrap: anywhere;
                }
                .strategic-tree-hotspot {
                    position: absolute;
                    border-radius: 16px;
                    outline: none;
                }
                .strategic-tree-hotspot:focus-visible {
                    box-shadow: 0 0 0 4px rgba(0, 143, 122, .45);
                }
                .strategic-tree-cta {
                    left: 66.9%;
                    top: 86.2%;
                    width: 12.5%;
                    height: 5.7%;
                }
                .strategic-tree-animation {
                    right: 5.25%;
                    top: 19.15%;
                    width: 10.4%;
                    height: 4.4%;
                }
                .strategic-tree-animation::after {
                    content: "";
                    position: absolute;
                    inset: 0;
                    border-radius: 14px;
                    opacity: ${animationEnabled ? ".18" : ".08"};
                    box-shadow: 0 0 0 2px ${animationEnabled ? "rgba(0, 143, 122, .65)" : "rgba(15, 23, 42, .45)"};
                }
                .strategic-tree-pulse {
                    pointer-events: none;
                    position: absolute;
                    left: 46.8%;
                    top: 55.5%;
                    width: 7.2%;
                    aspect-ratio: 1;
                    border-radius: 999px;
                    opacity: ${animationEnabled ? "1" : "0"};
                    box-shadow: 0 0 0 0 rgba(31, 180, 106, .42);
                    animation: strategicPulse 2.4s ease-out infinite;
                }
                @keyframes strategicPulse {
                    70% { box-shadow: 0 0 0 28px rgba(31, 180, 106, 0); }
                    100% { box-shadow: 0 0 0 0 rgba(31, 180, 106, 0); }
                }
                @media (prefers-reduced-motion: reduce) {
                    .strategic-tree-pulse { animation: none; }
                }
                @media (max-width: 900px) {
                    .strategic-tree-stage {
                        overflow-x: auto;
                        overscroll-behavior-x: contain;
                    }
                    .strategic-tree-stage img,
                    .strategic-tree-stage .strategic-tree-hotspot,
                    .strategic-tree-stage .strategic-tree-pulse {
                        min-width: 1180px;
                    }
                    .strategic-tree-stage img {
                        max-width: none;
                    }
                    .strategic-crisp-title,
                    .strategic-crisp-subtitle,
                    .strategic-title-cleaner,
                    .strategic-center-cleaner,
                    .strategic-lower-cleaner,
                    .strategic-crisp-tree-text,
                    .strategic-crisp-root-label,
                    .strategic-crisp-cta,
                    .strategic-tree-hotspot,
                    .strategic-tree-pulse {
                        min-width: 1180px;
                    }
                }
            `}</style>

            <section className="strategic-tree-shell">
                <div className="strategic-tree-stage">
                    <img
                        src={settings.background_image}
                        alt="Strategic Partners and Donors tree interface for OSHE Foundation"
                        draggable="false"
                    />

                    <div className="strategic-title-cleaner" aria-hidden="true" />
                    <div className="strategic-center-cleaner" aria-hidden="true" />
                    <div className="strategic-lower-cleaner" aria-hidden="true" />
                    <h1 className="strategic-crisp-title">
                        {titleParts[0]} {titleParts[1] ? <span>{titleParts[1]}</span> : null}
                    </h1>
                    <p className="strategic-crisp-subtitle">{settings.subtitle}</p>
                    <div className="strategic-crisp-tree-text">{settings.tree_text}</div>
                    <div className="strategic-crisp-root-label">{settings.root_label}</div>
                    <div className="strategic-crisp-cta">
                        <div className="strategic-crisp-cta-icon" aria-hidden="true">♧</div>
                        <h2>{settings.cta_title}</h2>
                        <span className="strategic-crisp-cta-line" aria-hidden="true" />
                        <p>{settings.cta_description}</p>
                        <a href={settings.cta_button_link}>
                            {settings.cta_button_text}
                            <span aria-hidden="true">→</span>
                        </a>
                    </div>

                    <span className="strategic-tree-pulse" aria-hidden="true" />

                    <button
                        type="button"
                        className="strategic-tree-hotspot strategic-tree-animation"
                        onClick={() => setAnimationEnabled((current) => !current)}
                        aria-label={animationEnabled ? "Turn animation off" : "Turn animation on"}
                        title={animationEnabled ? "Animation is on" : "Animation is off"}
                    />

                    <a
                        href="/partner-with-us"
                        className="strategic-tree-hotspot strategic-tree-cta"
                        aria-label="Become a Partner"
                        title="Become a Partner"
                    />
                </div>
            </section>
        </main>
    );
}
