import React, { useEffect, useRef } from "react";
import {
    FaBriefcaseMedical,
    FaBuilding,
    FaChild,
    FaClipboardCheck,
    FaCloudSun,
    FaDigitalTachograph,
    FaFileSignature,
    FaFireExtinguisher,
    FaFirstAid,
    FaGlobeAsia,
    FaGraduationCap,
    FaHandsHelping,
    FaHardHat,
    FaHeartbeat,
    FaIndustry,
    FaLeaf,
    FaMapMarkedAlt,
    FaPeopleCarry,
    FaRecycle,
    FaSeedling,
    FaShip,
    FaTools,
    FaUserShield,
    FaUsers,
} from "react-icons/fa";
import { usePage } from "@inertiajs/react";

const iconPool = [
    FaSeedling,
    FaBuilding,
    FaPeopleCarry,
    FaShip,
    FaFileSignature,
    FaHardHat,
    FaLeaf,
    FaChild,
    FaRecycle,
    FaCloudSun,
    FaHandsHelping,
    FaFireExtinguisher,
    FaUsers,
    FaBriefcaseMedical,
    FaGraduationCap,
    FaTools,
    FaClipboardCheck,
    FaFirstAid,
    FaMapMarkedAlt,
    FaHeartbeat,
    FaGlobeAsia,
    FaUserShield,
    FaDigitalTachograph,
    FaIndustry,
];

const defaultMilestones = [
    {
        year: "2001",
        text: "OSHE begins its journey",
        icon: FaSeedling,
    },
    {
        year: "2003",
        text: "Formal institutional establishment",
        icon: FaBuilding,
    },
    {
        year: "2005",
        text: "OSH initiatives for informal and home-based workers",
        icon: FaPeopleCarry,
    },
    {
        year: "2006",
        text: "Decent work advocacy in shipbreaking; launch of medical health camps (2006–2016)",
        icon: FaShip,
    },
    {
        year: "2007",
        text: "National OSH Policy drafting; OSH training in RMG and construction sectors",
        icon: FaFileSignature,
    },
    {
        year: "2008",
        text: "Development of OSH guidelines for construction",
        icon: FaHardHat,
    },
    {
        year: "2009",
        text: "National OSH profiling; green jobs and strategy development",
        icon: FaLeaf,
    },
    {
        year: "2010",
        text: "Awareness on child labour hazards, primary health care, and climate change",
        icon: FaChild,
    },
    {
        year: "2011",
        text: "Waste pickers, health education, organizing of home-based workers",
        icon: FaRecycle,
    },
    {
        year: "2012",
        text: "Climate change education, PPE support for informal workers",
        icon: FaCloudSun,
    },
    {
        year: "2013",
        text: "Rana Plaza victim support, asbestos exposure awareness, just transition in shipbreaking",
        icon: FaHandsHelping,
    },
    {
        year: "2014",
        text: "Fire incident reviews (e.g., Tajreen), worker education support",
        icon: FaFireExtinguisher,
    },
    {
        year: "2015",
        text: "OSH outreach in local communities and workplace safety promotion",
        icon: FaUsers,
    },
    {
        year: "2016",
        text: "Medical health camps in shipbreaking field (2006–2016)\nPioneered campaign on asbestosis, certified and supported victims to receive compensation",
        icon: FaBriefcaseMedical,
    },
    {
        year: "2017",
        text: "OSH training for trade union federations",
        icon: FaGraduationCap,
    },
    {
        year: "2018",
        text: "Decent work in leather supply chains; establishment of union training institute",
        icon: FaTools,
    },
    {
        year: "2019",
        text: "OSH assessments in SME sectors; expansion of social protection programs",
        icon: FaClipboardCheck,
    },
    {
        year: "2020",
        text: "COVID-19 Response, ongoing OSH and leather sector initiatives",
        icon: FaFirstAid,
    },
    {
        year: "2021",
        text: "Victim support campaigns, regional occupational disaster mapping",
        icon: FaMapMarkedAlt,
    },
    {
        year: "2022",
        text: "Continued engagement in leather, education, informal sectors; Workplace Accident monitoring and compensation benefit (Special focus on EIS)",
        icon: FaHeartbeat,
    },
    {
        year: "2023",
        text: "Promotion of sustainable leather production; Asia-wide Occupational Disaster Mapping initiative",
        icon: FaGlobeAsia,
    },
    {
        year: "2024",
        text: "Continued monitoring, victim support, and Shipbreaking worker health camp promotion",
        icon: FaUserShield,
    },
    {
        year: "2025",
        text: "Scaling of digital accident tracking systems, regional OSH campaigns, and union capacity development",
        icon: FaDigitalTachograph,
    },
];

const parseJourneyItems = (itemsText, fallbackItems = defaultMilestones) => {
    if (!itemsText || typeof itemsText !== "string") {
        return fallbackItems;
    }

    const parsed = itemsText
        .split(/\r?\n/)
        .map((line) => line.trim())
        .filter(Boolean)
        .map((line, index) => {
            const match = line.match(/^(\d{4})\s*(?:\||-|:)\s*(.+)$/);

            if (!match) {
                return null;
            }

            const [, year, text] = match;

            if (!text.trim()) {
                return null;
            }

            return {
                year,
                text: text.trim().replace(/\s+\/\s+/g, "\n"),
                icon: iconPool[index % iconPool.length],
            };
        })
        .filter(Boolean);

    return parsed.length ? parsed : fallbackItems;
};

const getYearRange = (items) => {
    const years = items.map(({ year }) => year).filter(Boolean);

    if (!years.length) {
        return "";
    }

    return `${years[0]}-${years[years.length - 1]}`;
};

const settingText = (value, fallback) => {
    const normalized = String(value || "").trim().toLowerCase();

    if (!normalized || normalized === "journey title text" || normalized === "journey kicker text") {
        return fallback;
    }

    return value;
};

export default function JourneyTimeline({
    kicker: kickerProp = null,
    title: titleProp = null,
    itemsText: itemsTextProp = null,
    fallbackItems = defaultMilestones,
}) {
    const {
        journey_kicker_text,
        journey_title_text,
        journey_timeline_items_text,
    } = usePage().props;
    const sectionRef = useRef(null);
    const progressRef = useRef(null);
    const dotRef = useRef(null);
    const milestones = parseJourneyItems(itemsTextProp ?? journey_timeline_items_text, fallbackItems);
    const title = titleProp ?? settingText(journey_title_text, "OSHE's Journey: Footprint & Strategic Progress");
    const kicker = kickerProp ?? settingText(journey_kicker_text, "Strategic Progress");
    const yearRange = getYearRange(milestones);

    useEffect(() => {
        const section = sectionRef.current;
        const progress = progressRef.current;
        const dot = dotRef.current;

        if (!section || !progress || !dot) {
            return undefined;
        }

        // Reveal each milestone as it enters the viewport.
        const observer = new IntersectionObserver(
            (entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add("is-visible");
                    }
                });
            },
            { rootMargin: "0px 0px -18% 0px", threshold: 0.18 },
        );

        section.querySelectorAll(".oshe-timeline-item").forEach((item) => {
            observer.observe(item);
        });

        // Move the glowing progress line and dot while the user scrolls through the section.
        const updateProgress = () => {
            const rect = section.getBoundingClientRect();
            const viewportHeight = window.innerHeight || document.documentElement.clientHeight;
            const scrollable = rect.height - viewportHeight * 0.42;
            const rawProgress = (viewportHeight * 0.45 - rect.top) / scrollable;
            const clamped = Math.min(1, Math.max(0, rawProgress));

            progress.style.transform = `scaleY(${clamped})`;
            dot.style.top = `${clamped * 100}%`;
        };

        updateProgress();
        window.addEventListener("scroll", updateProgress, { passive: true });
        window.addEventListener("resize", updateProgress);

        return () => {
            observer.disconnect();
            window.removeEventListener("scroll", updateProgress);
            window.removeEventListener("resize", updateProgress);
        };
    }, []);

    return (
        <section ref={sectionRef} className="oshe-journey-section">
            <div className="oshe-journey-bg" />
            <div className="oshe-journey-shell">
                <div className="oshe-journey-heading">
                    <span className="oshe-journey-kicker">{kicker}</span>
                    <h2>
                        {title}
                        {yearRange && <small>({yearRange})</small>}
                    </h2>
                </div>

                <div className="oshe-timeline-wrap">
                    <div className="oshe-timeline-line">
                        <span ref={progressRef} className="oshe-timeline-progress" />
                        <span ref={dotRef} className="oshe-timeline-dot" />
                    </div>

                    {milestones.map(({ year, text, icon: Icon }, index) => (
                        <article
                            className="oshe-timeline-item"
                            key={year}
                            style={{ "--delay": `${Math.min(index * 45, 600)}ms` }}
                        >
                            <div className="oshe-timeline-year">{year}</div>
                            <div className="oshe-timeline-icon" aria-hidden="true">
                                <Icon />
                            </div>
                            <div className="oshe-timeline-card">
                                {text.split("\n").map((line, lineIndex) => (
                                    <p key={`${year}-${lineIndex}`}>{line}</p>
                                ))}
                            </div>
                        </article>
                    ))}
                </div>
            </div>

            <style>{`
                .oshe-journey-section {
                    position: relative;
                    overflow: hidden;
                    width: 100%;
                    padding: clamp(56px, 7vw, 110px) 18px;
                    background:
                        linear-gradient(135deg, rgba(239, 248, 255, 0.96), rgba(255, 255, 255, 0.94) 48%, rgba(235, 250, 239, 0.96)),
                        #f8fbff;
                    color: #0f2f45;
                }

                /* Soft OSHE-sector background: safety, workers, industry, health, environment, monitoring. */
                .oshe-journey-bg {
                    position: absolute;
                    inset: 0;
                    pointer-events: none;
                    background:
                        radial-gradient(circle at 9% 18%, rgba(8, 110, 209, 0.13), transparent 26%),
                        radial-gradient(circle at 88% 12%, rgba(8, 117, 61, 0.14), transparent 28%),
                        radial-gradient(circle at 50% 92%, rgba(230, 83, 31, 0.10), transparent 30%);
                }

                .oshe-journey-bg::before {
                    content: "⚙  ⛑  🏭  🧤  🩺  ♻  ⚓  📡  🌿";
                    position: absolute;
                    left: 50%;
                    top: 50%;
                    width: min(1180px, 94vw);
                    transform: translate(-50%, -50%);
                    color: rgba(15, 47, 69, 0.08);
                    font-size: clamp(46px, 6vw, 96px);
                    letter-spacing: clamp(18px, 3vw, 48px);
                    line-height: 1.8;
                    text-align: center;
                    filter: blur(0.2px);
                }

                .oshe-journey-bg::after {
                    content: "";
                    position: absolute;
                    inset: 0;
                    background-image:
                        linear-gradient(rgba(15, 47, 69, 0.04) 1px, transparent 1px),
                        linear-gradient(90deg, rgba(15, 47, 69, 0.04) 1px, transparent 1px);
                    background-size: 42px 42px;
                    mask-image: radial-gradient(circle at center, black, transparent 74%);
                }

                .oshe-journey-shell {
                    position: relative;
                    z-index: 1;
                    max-width: 1180px;
                    margin: 0 auto;
                }

                .oshe-journey-heading {
                    max-width: 840px;
                    margin: 0 auto clamp(38px, 5vw, 70px);
                    text-align: center;
                }

                .oshe-journey-kicker {
                    display: inline-flex;
                    align-items: center;
                    gap: 8px;
                    border-radius: 999px;
                    border: 1px solid rgba(8, 117, 61, 0.22);
                    background: rgba(255, 255, 255, 0.78);
                    padding: 8px 15px;
                    color: #08753d;
                    font-size: 12px;
                    font-weight: 900;
                    letter-spacing: 0;
                    text-transform: uppercase;
                    box-shadow: 0 12px 32px rgba(15, 47, 69, 0.08);
                }

                .oshe-journey-heading h2 {
                    margin: 18px 0 0;
                    color: #0b3157;
                    font-size: clamp(32px, 4.2vw, 62px);
                    font-weight: 950;
                    line-height: 1.05;
                    letter-spacing: 0;
                }

                .oshe-journey-heading small {
                    display: block;
                    margin-top: 10px;
                    color: #08753d;
                    font-size: clamp(20px, 2.2vw, 34px);
                    font-weight: 900;
                }

                .oshe-timeline-wrap {
                    position: relative;
                    display: grid;
                    gap: 24px;
                    padding: 10px 0;
                }

                .oshe-timeline-line {
                    position: absolute;
                    left: 50%;
                    top: 0;
                    bottom: 0;
                    width: 4px;
                    transform: translateX(-50%);
                    border-radius: 999px;
                    background: rgba(15, 47, 69, 0.13);
                    overflow: visible;
                }

                .oshe-timeline-progress {
                    position: absolute;
                    inset: 0;
                    transform: scaleY(0);
                    transform-origin: top;
                    border-radius: inherit;
                    background: linear-gradient(180deg, #086ed1, #08753d 58%, #e6531f);
                    box-shadow: 0 0 24px rgba(8, 117, 61, 0.34);
                }

                .oshe-timeline-dot {
                    position: absolute;
                    left: 50%;
                    top: 0;
                    width: 22px;
                    height: 22px;
                    transform: translate(-50%, -50%);
                    border: 4px solid #ffffff;
                    border-radius: 50%;
                    background: #e6531f;
                    box-shadow: 0 0 0 8px rgba(230, 83, 31, 0.16), 0 0 30px rgba(230, 83, 31, 0.55);
                    transition: top 120ms linear;
                }

                .oshe-timeline-item {
                    --side-offset: calc(50% + 44px);
                    position: relative;
                    display: grid;
                    grid-template-columns: 1fr 88px 1fr;
                    align-items: center;
                    min-height: 118px;
                    opacity: 0;
                    transform: translateY(34px);
                    transition: opacity 700ms ease var(--delay), transform 700ms ease var(--delay);
                }

                .oshe-timeline-item.is-visible {
                    opacity: 1;
                    transform: translateY(0);
                }

                .oshe-timeline-year {
                    justify-self: end;
                    margin-right: 34px;
                    border-radius: 999px;
                    background: #0b3157;
                    padding: 10px 18px;
                    color: #ffffff;
                    font-size: 22px;
                    font-weight: 950;
                    box-shadow: 0 16px 32px rgba(11, 49, 87, 0.16);
                }

                .oshe-timeline-icon {
                    position: relative;
                    z-index: 2;
                    display: grid;
                    place-items: center;
                    justify-self: center;
                    width: 66px;
                    height: 66px;
                    border: 5px solid #ffffff;
                    border-radius: 50%;
                    background: linear-gradient(135deg, #086ed1, #08753d);
                    color: #ffffff;
                    font-size: 27px;
                    box-shadow: 0 18px 38px rgba(15, 47, 69, 0.20);
                    transition: transform 500ms ease, box-shadow 500ms ease;
                }

                .oshe-timeline-item.is-visible .oshe-timeline-icon {
                    transform: scale(1.04);
                    box-shadow: 0 20px 44px rgba(8, 117, 61, 0.28);
                }

                .oshe-timeline-card {
                    justify-self: start;
                    max-width: 470px;
                    border: 1px solid rgba(15, 47, 69, 0.10);
                    border-left: 5px solid #08753d;
                    border-radius: 8px;
                    background: rgba(255, 255, 255, 0.86);
                    padding: 20px 22px;
                    box-shadow: 0 18px 44px rgba(15, 47, 69, 0.12);
                    backdrop-filter: blur(10px);
                }

                .oshe-timeline-card p {
                    margin: 0;
                    color: #243348;
                    font-size: clamp(15px, 1.15vw, 18px);
                    font-weight: 750;
                    line-height: 1.55;
                }

                .oshe-timeline-card p + p {
                    margin-top: 8px;
                }

                .oshe-timeline-item:nth-child(even) .oshe-timeline-year {
                    grid-column: 3;
                    grid-row: 1;
                    justify-self: start;
                    margin-right: 0;
                    margin-left: 34px;
                    background: #08753d;
                }

                .oshe-timeline-item:nth-child(even) .oshe-timeline-icon {
                    grid-column: 2;
                    grid-row: 1;
                    background: linear-gradient(135deg, #08753d, #e6531f);
                }

                .oshe-timeline-item:nth-child(even) .oshe-timeline-card {
                    grid-column: 1;
                    grid-row: 1;
                    justify-self: end;
                    border-right: 5px solid #086ed1;
                    border-left: 1px solid rgba(15, 47, 69, 0.10);
                }

                @media (max-width: 900px) {
                    .oshe-journey-section {
                        padding-inline: 14px;
                    }

                    .oshe-timeline-line {
                        left: 34px;
                    }

                    .oshe-timeline-item,
                    .oshe-timeline-item:nth-child(even) {
                        grid-template-columns: 68px 1fr;
                        gap: 14px;
                        min-height: auto;
                    }

                    .oshe-timeline-year,
                    .oshe-timeline-item:nth-child(even) .oshe-timeline-year {
                        grid-column: 2;
                        grid-row: 1;
                        justify-self: start;
                        align-self: end;
                        margin: 0 0 8px;
                        padding: 8px 14px;
                        font-size: 18px;
                    }

                    .oshe-timeline-icon,
                    .oshe-timeline-item:nth-child(even) .oshe-timeline-icon {
                        grid-column: 1;
                        grid-row: 1 / span 2;
                        width: 58px;
                        height: 58px;
                        font-size: 23px;
                    }

                    .oshe-timeline-card,
                    .oshe-timeline-item:nth-child(even) .oshe-timeline-card {
                        grid-column: 2;
                        grid-row: 2;
                        justify-self: stretch;
                        max-width: none;
                        border-right: 1px solid rgba(15, 47, 69, 0.10);
                        border-left: 5px solid #08753d;
                        padding: 17px;
                    }
                }

                @media (prefers-reduced-motion: reduce) {
                    .oshe-timeline-item,
                    .oshe-timeline-icon,
                    .oshe-timeline-dot {
                        transition: none;
                    }
                }
            `}</style>
        </section>
    );
}
