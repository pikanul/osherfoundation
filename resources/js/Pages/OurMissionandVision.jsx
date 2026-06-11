import React, { useEffect, useRef, useState } from "react";
import { EyeIcon, ShieldCheckIcon } from "@heroicons/react/24/outline";
import Root from "../component/layout/Root";

const missionText =
    "To promote and protect the human rights of workers, with a special focus on workplace safety, workers’ health, and environmental protection. OSHE works to eliminate poverty, advance social progress, and build a healthier future for workers by strengthening the capacity, solidarity, and unified voice of the labour movement as a vital contributor to the world of work and sustainable development.";

const visionText =
    "A world of work where every worker enjoys safe, healthy, dignified, and rights-based workplaces, free from hazards, poverty, and discrimination, while contributing to sustainable development and social progress.";

const toLines = (text) => text.match(/[^.!?]+[.!?]+|[^.!?]+$/g)?.map((line) => line.trim()).filter(Boolean) || [];

function TypewriterText({ text, start }) {
    const [visibleChars, setVisibleChars] = useState(0);
    const lines = toLines(text);
    const totalChars = text.length;

    useEffect(() => {
        let interval;

        if (!start) {
            setVisibleChars(0);
            return undefined;
        }

        let current = 0;
        const timeout = window.setTimeout(() => {
            interval = window.setInterval(() => {
                current += 3;
                setVisibleChars(Math.min(current, totalChars));

                if (current >= totalChars) {
                    window.clearInterval(interval);
                }
            }, 22);
        }, 420);

        return () => {
            window.clearTimeout(timeout);
            window.clearInterval(interval);
        };
    }, [start, totalChars]);

    let remaining = visibleChars;

    return (
        <div className="mv-card-copy">
            {lines.map((line, index) => {
                const charsForLine = Math.max(0, Math.min(line.length, remaining));
                remaining -= line.length;

                return (
                    <p key={`${line}-${index}`} className="mv-type-line">
                        {line.slice(0, charsForLine)}
                        {visibleChars < totalChars && charsForLine === line.length && index < lines.length - 1 ? " " : ""}
                        {visibleChars < totalChars && charsForLine > 0 && charsForLine < line.length ? (
                            <span className="mv-type-cursor" aria-hidden="true" />
                        ) : null}
                    </p>
                );
            })}
        </div>
    );
}

function LeafDivider() {
    return (
        <div className="mv-leaf-divider" aria-hidden="true">
            <span />
            <i />
            <span />
        </div>
    );
}

function Card({ title, text, type, visible }) {
    const Icon = type === "mission" ? ShieldCheckIcon : EyeIcon;

    return (
        <article className={`mv-card ${visible ? "is-visible" : ""}`}>
            <div className="mv-corner-wave" aria-hidden="true" />
            <div className="mv-card-head">
                <div className="mv-icon">
                    <Icon className="h-9 w-9" />
                </div>
                <h3>{title}</h3>
            </div>
            <TypewriterText text={text} start={visible} />
        </article>
    );
}

export default function OurMissionandVision() {
    const sectionRef = useRef(null);
    const [visible, setVisible] = useState(false);

    useEffect(() => {
        const section = sectionRef.current;

        if (!section) {
            return undefined;
        }

        const observer = new IntersectionObserver(
            ([entry]) => {
                if (entry.isIntersecting) {
                    setVisible(true);
                    observer.disconnect();
                }
            },
            { threshold: 0.24, rootMargin: "0px 0px -12% 0px" },
        );

        observer.observe(section);

        return () => observer.disconnect();
    }, []);

    return (
        <Root>
            <main className="bg-white">
                <section ref={sectionRef} className="mv-section">
                    <div className="mv-bg" aria-hidden="true" />
                    <div className="mv-shell">
                        <div className="mv-heading">
                            <h2>Mission and Vision</h2>
                            <LeafDivider />
                        </div>

                        <div className="mv-grid">
                            <Card title="Mission" text={missionText} type="mission" visible={visible} />
                            <Card title="Vision" text={visionText} type="vision" visible={visible} />
                        </div>
                    </div>

                    <style>{`
                        .mv-section {
                            position: relative;
                            isolation: isolate;
                            overflow: hidden;
                            min-height: calc(100vh - 170px);
                            padding: clamp(56px, 7vw, 96px) 18px;
                            background:
                                linear-gradient(135deg, rgba(226, 249, 247, 0.96), rgba(255, 255, 255, 0.98) 44%, rgba(236, 250, 238, 0.94)),
                                #f8ffff;
                        }

                        .mv-bg {
                            position: absolute;
                            inset: 0;
                            z-index: -1;
                            overflow: hidden;
                        }

                        .mv-bg::before {
                            content: "";
                            position: absolute;
                            inset: 0;
                            background-image:
                                radial-gradient(circle at 9% 18%, rgba(0, 143, 122, 0.12), transparent 26%),
                                radial-gradient(circle at 88% 18%, rgba(8, 110, 209, 0.10), transparent 28%),
                                radial-gradient(circle at 52% 96%, rgba(141, 198, 63, 0.13), transparent 34%),
                                radial-gradient(rgba(0, 143, 122, 0.16) 1px, transparent 1px);
                            background-size: auto, auto, auto, 22px 22px;
                        }

                        .mv-bg::after {
                            content: "";
                            position: absolute;
                            left: -6%;
                            right: -6%;
                            bottom: -46px;
                            height: 170px;
                            background:
                                linear-gradient(174deg, transparent 0 42%, rgba(189, 239, 235, 0.70) 42.5% 66%, transparent 66.5%),
                                linear-gradient(8deg, transparent 0 48%, rgba(221, 247, 226, 0.86) 48.5% 76%, transparent 76.5%);
                        }

                        .mv-shell {
                            position: relative;
                            max-width: 1180px;
                            margin: 0 auto;
                        }

                        .mv-heading {
                            margin-bottom: clamp(30px, 4vw, 48px);
                            text-align: center;
                        }

                        .mv-heading h2 {
                            margin: 0;
                            color: #0f2f45;
                            font-size: clamp(30px, 3vw, 42px);
                            font-weight: 900;
                            line-height: 1.15;
                            letter-spacing: 0;
                        }

                        .mv-leaf-divider {
                            display: inline-flex;
                            align-items: center;
                            gap: 10px;
                            margin-top: 14px;
                        }

                        .mv-leaf-divider span {
                            width: 48px;
                            height: 3px;
                            border-radius: 999px;
                            background: linear-gradient(90deg, rgba(0, 143, 122, 0), #008f7a);
                        }

                        .mv-leaf-divider span:last-child {
                            background: linear-gradient(90deg, #8dc63f, rgba(141, 198, 63, 0));
                        }

                        .mv-leaf-divider i {
                            width: 20px;
                            height: 12px;
                            border-radius: 100% 0 100% 0;
                            background: linear-gradient(135deg, #008f7a, #8dc63f);
                            transform: rotate(-22deg);
                            box-shadow: 0 6px 18px rgba(0, 143, 122, 0.18);
                        }

                        .mv-grid {
                            display: grid;
                            grid-template-columns: repeat(2, minmax(0, 1fr));
                            gap: clamp(22px, 3vw, 34px);
                            align-items: stretch;
                        }

                        .mv-card {
                            position: relative;
                            overflow: hidden;
                            min-height: 390px;
                            border: 1px solid rgba(0, 143, 122, 0.16);
                            border-radius: 24px;
                            background: rgba(255, 255, 255, 0.92);
                            padding: clamp(26px, 3vw, 38px);
                            box-shadow: 0 20px 48px rgba(15, 47, 69, 0.12);
                            opacity: 0;
                            transform: scaleY(0.2);
                            transform-origin: top center;
                            transition: opacity 0.8s ease-out, transform 0.8s ease-out;
                            backdrop-filter: blur(10px);
                        }

                        .mv-card.is-visible {
                            opacity: 1;
                            transform: scaleY(1);
                        }

                        .mv-corner-wave {
                            position: absolute;
                            right: -36px;
                            top: -42px;
                            width: 180px;
                            height: 150px;
                            border-radius: 0 0 0 90px;
                            background:
                                radial-gradient(circle at 70% 24%, rgba(141, 198, 63, 0.36), transparent 34%),
                                linear-gradient(135deg, rgba(0, 143, 122, 0.22), rgba(189, 239, 235, 0.42));
                            transform: rotate(-7deg);
                        }

                        .mv-card-head {
                            position: relative;
                            display: flex;
                            align-items: center;
                            gap: 16px;
                            margin-bottom: 24px;
                        }

                        .mv-icon {
                            display: grid;
                            place-items: center;
                            width: 64px;
                            height: 64px;
                            border-radius: 18px;
                            background: linear-gradient(135deg, #008f7a, #8dc63f);
                            color: #ffffff;
                            box-shadow: 0 12px 24px rgba(0, 143, 122, 0.18);
                        }

                        .mv-card h3 {
                            margin: 0;
                            color: #0f2f45;
                            font-size: clamp(26px, 2.3vw, 34px);
                            font-weight: 900;
                            line-height: 1.1;
                            letter-spacing: 0;
                        }

                        .mv-card-copy {
                            position: relative;
                            color: #26384d;
                            font-size: clamp(16px, 1.12vw, 18px);
                            font-weight: 600;
                            line-height: 1.8;
                            text-align: justify;
                            text-justify: inter-word;
                        }

                        .mv-type-line {
                            margin: 0;
                            min-height: 1.8em;
                            white-space: pre-wrap;
                        }

                        .mv-type-line + .mv-type-line {
                            margin-top: 12px;
                        }

                        .mv-type-cursor {
                            display: inline-block;
                            width: 2px;
                            height: 1.08em;
                            margin-left: 2px;
                            transform: translateY(0.18em);
                            border-radius: 999px;
                            background: #008f7a;
                            animation: mvCursorBlink 0.85s step-end infinite;
                        }

                        @keyframes mvCursorBlink {
                            50% { opacity: 0; }
                        }

                        @media (max-width: 900px) {
                            .mv-grid {
                                grid-template-columns: 1fr;
                            }

                            .mv-card {
                                min-height: auto;
                            }
                        }

                        @media (prefers-reduced-motion: reduce) {
                            .mv-card,
                            .mv-type-line,
                            .mv-type-cursor {
                                animation: none !important;
                                transition: none !important;
                                opacity: 1 !important;
                                transform: none !important;
                            }
                        }
                    `}</style>
                </section>
            </main>
        </Root>
    );
}
