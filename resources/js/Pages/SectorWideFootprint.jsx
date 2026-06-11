import React, { useEffect, useMemo, useRef, useState } from "react";
import { usePage } from "@inertiajs/react";
import {
    FaBalanceScale,
    FaBuilding,
    FaCheckCircle,
    FaHardHat,
    FaHeartbeat,
    FaIndustry,
    FaLeaf,
    FaRecycle,
    FaSeedling,
    FaShip,
    FaTshirt,
    FaUsers,
} from "react-icons/fa";
import Root from "../component/layout/Root";

const fallbackItemsText = `rmg | 1 | 1 | Ready-Made Garments (RMG) | OSHE works with workers, trade unions, employers, and stakeholders in the RMG sector to promote occupational safety and health, labour rights, decent work, gender equality, social dialogue, and workplace protection. The RMG sector remains one of OSHE's important areas of engagement due to its large workforce, high concentration of women workers, and strong relevance to labour standards, workplace safety, and social compliance. | Workplace safety awareness; Labour rights education; Trade union strengthening; Gender equality and GBV prevention; Social protection; Responsible workplace practices
construction | 1 | 2 | Construction | Construction is one of the most hazardous sectors for workers due to risks related to falls, unsafe equipment, lack of protective gear, electrical hazards, and weak safety practices. OSHE supports construction workers through OSH awareness, safety training, emergency preparedness, and advocacy for safer working conditions. | OSH training; Accident prevention; Use of protective equipment; Emergency preparedness; Safer working conditions; Compliance with safety standards
shipbreaking | 1 | 3 | Shipbreaking | Shipbreaking workers are exposed to serious occupational hazards, including heavy machinery risks, toxic substances, fire and explosion risks, unsafe dismantling practices, and environmental pollution. OSHE works to promote workplace safety, health protection, workers' rights, environmental justice, and improved safety practices in this high-risk sector. | Accident prevention; Occupational disease awareness; Safe work practices; Toxic hazard awareness; Environmental justice; Worker protection and accountability
leather-tannery | 1 | 4 | Leather and Tannery | The leather and tannery sector involves chemical exposure, unsafe handling of materials, environmental pollution, and occupational health risks. OSHE promotes OSH awareness, labour rights, decent work, and environmental responsibility for workers and communities connected to the leather supply chain. | Chemical safety awareness; Workplace health protection; Labour standards; Social dialogue; Environmental responsibility; Safer handling practices
waste-management | 1 | 5 | Waste Management | Waste workers face major health and safety risks, including exposure to hazardous waste, sharp materials, toxic substances, infections, poor sanitation, and social exclusion. OSHE works with waste workers and related stakeholders to promote occupational safety, health protection, dignity, livelihood support, and social recognition. | OSH awareness; Health protection; Dignity at work; Worker empowerment; Social protection advocacy; Safer working conditions
agriculture | 1 | 6 | Agriculture | Agricultural workers often face risks from pesticides, unsafe tools, climate change, heat stress, poor access to health protection, and informal employment conditions. OSHE supports agriculture workers and communities through awareness, livelihood support, climate resilience, environmental justice, and social protection advocacy. | Pesticide safety; Climate resilience; Sustainable livelihoods; Health protection; Informal worker protection; Climate adaptation
health-sanitation | 1 | 7 | Health and Sanitation | Workers engaged in health, sanitation, cleaning, and related services are often exposed to biological hazards, waste, chemicals, infection risks, and unsafe working environments. OSHE promotes safety awareness, health protection, dignity at work, and social protection for these workers. | Biological hazard awareness; Infection prevention; Sanitation worker dignity; Health protection; Safe service delivery; Social protection
informal-home-based | 1 | 8 | Informal and Home-Based Work | OSHE works with informal and home-based workers who often lack formal contracts, legal protection, social security, workplace safety measures, and access to institutional support. These workers include women workers, community-based workers, self-employed workers, and vulnerable groups engaged in informal livelihoods. | Worker awareness; Legal protection advocacy; Social security inclusion; Livelihood protection; Women worker empowerment; Informal worker representation
smes | 1 | 9 | Small and Medium Enterprises (SMEs) | Workers in SMEs often face gaps in safety systems, documentation, legal compliance, training, and access to workplace protection. OSHE supports SMEs by promoting OSH awareness, labour rights, decent work principles, and practical workplace safety measures. | OSH awareness; Labour rights education; Workplace safety systems; Documentation support; Worker participation; Responsible business conduct
jhut | 1 | 10 | Jhut Industry | The jhut industry involves workers engaged in recycling, sorting, handling, and processing garment waste and related materials. Workers may face risks related to dust, unsafe handling practices, fire hazards, poor working conditions, and informal employment. | Dust and fire hazard awareness; Safer handling practices; Health protection; Informal worker rights; Better working conditions; Waste recycling safety
other-vulnerable | 1 | 11 | Other Labour-Intensive and Vulnerable Sectors | Beyond these sectors, OSHE also works with other labour-intensive and vulnerable worker groups where risks related to unsafe work, weak labour protection, poverty, informality, climate change, and social exclusion remain high. | Vulnerable worker protection; OSH awareness; Labour rights advocacy; Social protection; Climate justice; Community empowerment`;

const defaultCrossCutting = `Occupational safety and health awareness and risk prevention
Labour rights and decent work promotion
Trade union strengthening and worker participation
Social dialogue among workers, employers, government, and civil society
Social protection and livelihood security
Gender equality, GBV prevention, and workplace inclusion
Occupational disease monitoring and workplace health protection
Climate justice, environmental justice, and Just Transition
Policy advocacy, legal reform, and institutional strengthening
Community-based awareness, training, and worker empowerment`;

const iconMap = {
    rmg: FaTshirt,
    construction: FaHardHat,
    shipbreaking: FaShip,
    "leather-tannery": FaIndustry,
    "waste-management": FaRecycle,
    agriculture: FaSeedling,
    "health-sanitation": FaHeartbeat,
    "informal-home-based": FaUsers,
    smes: FaBuilding,
    jhut: FaRecycle,
    "other-vulnerable": FaBalanceScale,
};

const parseItems = (text, assets = {}) => String(text || fallbackItemsText)
    .split(/\r?\n/)
    .map((line) => line.trim())
    .filter(Boolean)
    .map((line, index) => {
        const [slug, active = "1", order = String(index + 1), title = "", description = "", focus = ""] = line.split("|").map((part) => part.trim());

        if (!slug || active === "0" || !title) {
            return null;
        }

        return {
            slug,
            order: Number(order) || index + 1,
            title,
            description,
            focus: focus.split(";").map((point) => point.trim()).filter(Boolean),
            image: assets?.[slug]?.image || "",
            iconImage: assets?.[slug]?.icon || "",
            Icon: iconMap[slug] || FaLeaf,
        };
    })
    .filter(Boolean)
    .sort((a, b) => a.order - b.order);

const parseLines = (text, fallback) => String(text || fallback)
    .split(/\r?\n/)
    .map((line) => line.trim())
    .filter(Boolean);

function SectorIcon({ item, className = "" }) {
    const Icon = item.Icon;

    if (item.iconImage) {
        return <img src={item.iconImage} alt="" className={`object-contain ${className}`} />;
    }

    return <Icon className={className} aria-hidden="true" />;
}

export default function SectorWideFootprint() {
    const { sector_wide_settings = {} } = usePage().props;
    const [activeSlug, setActiveSlug] = useState("");
    const [expanded, setExpanded] = useState({});
    const cardsRef = useRef({});
    const items = useMemo(
        () => parseItems(sector_wide_settings.items_text, sector_wide_settings.assets),
        [sector_wide_settings.assets, sector_wide_settings.items_text],
    );
    const crossCuttingPoints = useMemo(
        () => parseLines(sector_wide_settings.cross_cutting_points, defaultCrossCutting),
        [sector_wide_settings.cross_cutting_points],
    );

    useEffect(() => {
        if (!items.length) return undefined;
        setActiveSlug((current) => current || items[0].slug);

        const observer = new IntersectionObserver(
            (entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add("is-visible");
                        setActiveSlug(entry.target.getAttribute("data-sector-slug"));
                    }
                });
            },
            { root: null, rootMargin: "0px 0px -30% 0px", threshold: 0.08 },
        );

        Object.values(cardsRef.current).forEach((node) => node && observer.observe(node));
        return () => observer.disconnect();
    }, [items]);

    const title = sector_wide_settings.title || "Sector-Wide Footprint";
    const intro = sector_wide_settings.intro || "OSHE Foundation works across both formal and informal sectors in Bangladesh, addressing the safety, health, rights, dignity, and livelihood needs of workers in diverse labour markets.";
    const subIntro = sector_wide_settings.sub_intro || "Through training, awareness raising, research, policy advocacy, social dialogue, community engagement, and rights-based support, OSHE promotes safer workplaces and sustainable livelihoods across multiple sectors.";

    return (
        <Root>
            <main className="sector-footprint">
                <section className="sector-hero">
                    <div className="sector-map-pattern" aria-hidden="true" />
                    <p className="sector-kicker">OSHE Foundation</p>
                    <h1>{title}</h1>
                    <p className="sector-intro">{intro}</p>
                    <p className="sector-subintro">{subIntro}</p>
                </section>

                <section className="sector-scroll-shell">
                    <aside className="sector-nav" aria-label="Sector navigation">
                        <div className="sector-progress" aria-hidden="true" />
                        {items.map((item) => (
                            <button
                                key={item.slug}
                                type="button"
                                onClick={() => cardsRef.current[item.slug]?.scrollIntoView({ behavior: "smooth", block: "center" })}
                                className={activeSlug === item.slug ? "is-active" : ""}
                            >
                                <SectorIcon item={item} className="sector-nav-icon" />
                                <span>{item.title}</span>
                            </button>
                        ))}
                    </aside>

                    <div className="sector-card-list">
                        {items.map((item, index) => {
                            const isExpanded = Boolean(expanded[item.slug]);
                            const longDescription = item.description.length > 260;
                            const visibleDescription = !longDescription || isExpanded ? item.description : `${item.description.slice(0, 260)}...`;

                            return (
                                <article
                                    key={item.slug}
                                    ref={(node) => { cardsRef.current[item.slug] = node; }}
                                    data-sector-slug={item.slug}
                                    className={`sector-card ${activeSlug === item.slug ? "is-active" : ""}`}
                                    style={{ "--delay": `${Math.min(index * 80, 520)}ms` }}
                                >
                                    <div className="sector-card-copy">
                                        <div className="sector-card-heading">
                                            <span className="sector-card-icon">
                                                <SectorIcon item={item} className="sector-card-icon-svg" />
                                            </span>
                                            <div>
                                                <span className="sector-card-count">{String(index + 1).padStart(2, "0")}</span>
                                                <h2>{item.title}</h2>
                                            </div>
                                        </div>
                                        <p>{visibleDescription}</p>
                                        {longDescription && (
                                            <button
                                                type="button"
                                                className="sector-read-more"
                                                onClick={() => setExpanded((current) => ({ ...current, [item.slug]: !isExpanded }))}
                                            >
                                                {isExpanded ? "Show Less" : "Read More"}
                                            </button>
                                        )}
                                        {item.focus.length > 0 && (
                                            <div className="sector-focus-grid">
                                                {item.focus.map((point) => (
                                                    <span key={point}>
                                                        <FaCheckCircle aria-hidden="true" />
                                                        {point}
                                                    </span>
                                                ))}
                                            </div>
                                        )}
                                    </div>

                                    <div className="sector-visual" aria-label={`${item.title} visual`}>
                                        {item.image ? (
                                            <img src={item.image} alt={`${item.title} sector`} />
                                        ) : (
                                            <div className="sector-visual-fallback">
                                                <SectorIcon item={item} className="sector-visual-icon" />
                                            </div>
                                        )}
                                    </div>
                                </article>
                            );
                        })}
                    </div>
                </section>

                <section className="sector-priority">
                    <h2>{sector_wide_settings.cross_cutting_title || "Cross-Cutting Sector Priorities"}</h2>
                    <div className="sector-priority-grid">
                        {crossCuttingPoints.map((point) => (
                            <div key={point}>
                                <FaCheckCircle aria-hidden="true" />
                                <span>{point}</span>
                            </div>
                        ))}
                    </div>
                    {(sector_wide_settings.closing_text || "").trim() && (
                        <p className="sector-closing">{sector_wide_settings.closing_text}</p>
                    )}
                </section>
            </main>

            <style>{`
                .sector-footprint {
                    min-height: 100vh;
                    background: linear-gradient(180deg, #f4fbf7 0%, #ffffff 38%, #eef8f1 100%);
                    color: #142238;
                }

                .sector-hero {
                    position: relative;
                    overflow: hidden;
                    padding: clamp(64px, 8vw, 118px) clamp(18px, 5vw, 72px);
                    text-align: center;
                    background:
                        radial-gradient(circle at 12% 22%, rgba(0, 119, 96, 0.16), transparent 26%),
                        radial-gradient(circle at 88% 18%, rgba(141, 198, 63, 0.18), transparent 28%),
                        linear-gradient(135deg, #ffffff, #eaf8f0);
                }

                .sector-map-pattern {
                    position: absolute;
                    inset: 20px 6% auto auto;
                    width: 310px;
                    height: 220px;
                    opacity: 0.13;
                    background:
                        radial-gradient(circle, #007760 1.8px, transparent 2px) 0 0 / 14px 14px;
                    transform: rotate(-8deg);
                }

                .sector-kicker {
                    position: relative;
                    margin: 0 0 12px;
                    color: #007760;
                    font-size: 13px;
                    font-weight: 900;
                    letter-spacing: 0.22em;
                    text-transform: uppercase;
                }

                .sector-hero h1 {
                    position: relative;
                    margin: 0;
                    color: #063d34;
                    font-size: clamp(42px, 7vw, 86px);
                    font-weight: 950;
                    letter-spacing: 0;
                    line-height: 0.95;
                }

                .sector-intro,
                .sector-subintro {
                    position: relative;
                    max-width: 1060px;
                    margin: 28px auto 0;
                    color: #27364c;
                    font-size: clamp(16px, 1.35vw, 20px);
                    line-height: 1.78;
                }

                .sector-subintro {
                    max-width: 930px;
                    margin-top: 16px;
                    color: #446052;
                    font-weight: 650;
                }

                .sector-scroll-shell {
                    display: grid;
                    grid-template-columns: minmax(250px, 320px) minmax(0, 1fr);
                    gap: clamp(24px, 4vw, 56px);
                    max-width: 1500px;
                    margin: 0 auto;
                    padding: clamp(44px, 6vw, 86px) clamp(16px, 4vw, 54px);
                }

                .sector-nav {
                    position: sticky;
                    top: 130px;
                    align-self: start;
                    display: flex;
                    flex-direction: column;
                    gap: 8px;
                    padding: 16px;
                    border: 1px solid rgba(0, 119, 96, 0.14);
                    border-radius: 8px;
                    background: rgba(255, 255, 255, 0.86);
                    box-shadow: 0 18px 42px rgba(14, 66, 50, 0.12);
                    backdrop-filter: blur(14px);
                }

                .sector-progress {
                    position: absolute;
                    left: 26px;
                    top: 28px;
                    bottom: 28px;
                    width: 2px;
                    background: linear-gradient(#007760, #8dc63f);
                    opacity: 0.18;
                }

                .sector-nav button {
                    position: relative;
                    z-index: 1;
                    display: flex;
                    align-items: center;
                    gap: 12px;
                    border: 0;
                    border-radius: 8px;
                    background: transparent;
                    padding: 12px;
                    color: #26354c;
                    text-align: left;
                    font-size: 14px;
                    font-weight: 850;
                    cursor: pointer;
                    transition: background 180ms ease, color 180ms ease, transform 180ms ease;
                }

                .sector-nav button:hover,
                .sector-nav button.is-active {
                    background: #e7f7ee;
                    color: #005f4c;
                    transform: translateX(3px);
                }

                .sector-nav-icon {
                    width: 20px;
                    height: 20px;
                    flex: 0 0 auto;
                    color: #007760;
                }

                .sector-card-list {
                    display: grid;
                    gap: clamp(22px, 3vw, 38px);
                }

                .sector-card {
                    display: grid;
                    grid-template-columns: minmax(0, 1.05fr) minmax(240px, 0.72fr);
                    gap: clamp(20px, 3vw, 42px);
                    align-items: stretch;
                    min-height: 360px;
                    border: 1px solid rgba(0, 119, 96, 0.12);
                    border-radius: 8px;
                    background: #ffffff;
                    box-shadow: 0 20px 48px rgba(18, 54, 39, 0.1);
                    opacity: 1;
                    transform: translateY(0) scale(1);
                    transition: border-color 220ms ease, box-shadow 220ms ease, transform 220ms ease;
                    overflow: hidden;
                }

                .sector-card.is-visible {
                    opacity: 1;
                    transform: translateY(0) scale(1);
                }

                .sector-card.is-active,
                .sector-card:hover {
                    border-color: rgba(0, 119, 96, 0.36);
                    box-shadow: 0 26px 60px rgba(0, 119, 96, 0.17);
                    transform: translateY(-3px);
                }

                .sector-card-copy {
                    padding: clamp(24px, 4vw, 44px);
                }

                .sector-card-heading {
                    display: flex;
                    align-items: center;
                    gap: 18px;
                    margin-bottom: 18px;
                }

                .sector-card-icon {
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                    width: 66px;
                    height: 66px;
                    border-radius: 8px;
                    background: linear-gradient(135deg, #007760, #8dc63f);
                    color: #ffffff;
                    box-shadow: 0 14px 28px rgba(0, 119, 96, 0.22);
                }

                .sector-card-icon-svg {
                    width: 30px;
                    height: 30px;
                }

                .sector-card-count {
                    color: #8aa19a;
                    font-size: 12px;
                    font-weight: 900;
                    letter-spacing: 0.18em;
                }

                .sector-card h2 {
                    margin: 2px 0 0;
                    color: #0a342d;
                    font-size: clamp(24px, 3vw, 38px);
                    font-weight: 950;
                    letter-spacing: 0;
                    line-height: 1.08;
                }

                .sector-card p {
                    margin: 0;
                    color: #344357;
                    font-size: 16px;
                    line-height: 1.8;
                }

                .sector-read-more {
                    margin-top: 14px;
                    border: 0;
                    background: transparent;
                    color: #007760;
                    font-size: 14px;
                    font-weight: 900;
                    cursor: pointer;
                }

                .sector-focus-grid {
                    display: flex;
                    flex-wrap: wrap;
                    gap: 10px;
                    margin-top: 24px;
                }

                .sector-focus-grid span {
                    display: inline-flex;
                    align-items: center;
                    gap: 8px;
                    border-radius: 999px;
                    background: #f0f8f3;
                    padding: 9px 12px;
                    color: #1b4e43;
                    font-size: 13px;
                    font-weight: 800;
                }

                .sector-focus-grid svg {
                    color: #007760;
                    flex: 0 0 auto;
                }

                .sector-visual {
                    position: relative;
                    min-height: 100%;
                    background:
                        linear-gradient(135deg, rgba(0, 119, 96, 0.9), rgba(141, 198, 63, 0.78)),
                        #007760;
                }

                .sector-visual img {
                    width: 100%;
                    height: 100%;
                    object-fit: cover;
                    display: block;
                }

                .sector-visual-fallback {
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    height: 100%;
                    min-height: 320px;
                    color: rgba(255, 255, 255, 0.86);
                }

                .sector-visual-icon {
                    width: clamp(92px, 10vw, 150px);
                    height: clamp(92px, 10vw, 150px);
                }

                .sector-priority {
                    max-width: 1400px;
                    margin: 0 auto;
                    padding: clamp(48px, 6vw, 84px) clamp(16px, 4vw, 54px) clamp(72px, 8vw, 116px);
                }

                .sector-priority h2 {
                    margin: 0 0 28px;
                    color: #063d34;
                    font-size: clamp(30px, 4vw, 52px);
                    font-weight: 950;
                    letter-spacing: 0;
                    text-align: center;
                }

                .sector-priority-grid {
                    display: grid;
                    grid-template-columns: repeat(2, minmax(0, 1fr));
                    gap: 14px;
                }

                .sector-priority-grid div {
                    display: flex;
                    align-items: flex-start;
                    gap: 12px;
                    border: 1px solid rgba(0, 119, 96, 0.14);
                    border-radius: 8px;
                    background: rgba(255, 255, 255, 0.9);
                    padding: 16px;
                    color: #26384d;
                    font-weight: 760;
                    box-shadow: 0 12px 28px rgba(12, 56, 43, 0.08);
                }

                .sector-priority-grid svg {
                    margin-top: 3px;
                    color: #007760;
                    flex: 0 0 auto;
                }

                .sector-closing {
                    max-width: 980px;
                    margin: 34px auto 0;
                    border-radius: 8px;
                    background: linear-gradient(135deg, #007760, #0d8e73);
                    padding: 26px;
                    color: #ffffff;
                    font-size: clamp(18px, 2vw, 24px);
                    font-weight: 850;
                    line-height: 1.55;
                    text-align: center;
                    box-shadow: 0 18px 40px rgba(0, 119, 96, 0.22);
                }

                @media (max-width: 1024px) {
                    .sector-scroll-shell {
                        grid-template-columns: 1fr;
                    }

                    .sector-nav {
                        position: relative;
                        top: 0;
                        flex-direction: row;
                        overflow-x: auto;
                    }

                    .sector-nav button {
                        min-width: 210px;
                    }

                    .sector-progress {
                        display: none;
                    }
                }

                @media (max-width: 760px) {
                    .sector-card {
                        grid-template-columns: 1fr;
                    }

                    .sector-visual {
                        min-height: 220px;
                        order: -1;
                    }

                    .sector-priority-grid {
                        grid-template-columns: 1fr;
                    }
                }

                @media (prefers-reduced-motion: reduce) {
                    .sector-card,
                    .sector-nav button {
                        opacity: 1;
                        transform: none;
                        transition: none;
                    }
                }
            `}</style>
        </Root>
    );
}
