import React, { useEffect, useMemo, useState } from "react";
import { Link } from "@inertiajs/react";
import { ChevronDownIcon } from "@heroicons/react/24/solid";

function parseLocalDate(dateStr) {
    if (!dateStr || typeof dateStr !== "string") return null;
    const parts = dateStr.split("-").map(Number);
    if (parts.length !== 3 || parts.some(Number.isNaN)) return null;
    const [year, month, day] = parts;
    return new Date(year, month - 1, day);
}

function formatDateLabel(dateStr) {
    const d = parseLocalDate(dateStr);
    if (!d) return "Date TBA";
    return d.toLocaleDateString([], {
        weekday: "short",
        month: "short",
        day: "numeric",
    });
}

function formatDateRange(startDate, endDate) {
    const start = formatDateLabel(startDate);
    if (!endDate || endDate === startDate) return start;
    return `${start} - ${formatDateLabel(endDate)}`;
}

function formatDateNum(dateStr) {
    const d = parseLocalDate(dateStr);
    if (!d) return "--";
    return String(d.getDate());
}

function formatDateMonth(dateStr) {
    const d = parseLocalDate(dateStr);
    if (!d) return "---";
    return d.toLocaleDateString([], { month: "short" }).toUpperCase();
}

function formatTimeRange(startTime, endTime) {
    if (!startTime && !endTime) return "Time TBA";
    const normalize = (time) => {
        if (!time) return "";
        const [h = "00", m = "00"] = String(time).split(":");
        const d = new Date();
        d.setHours(Number(h), Number(m), 0, 0);
        return d.toLocaleTimeString([], { hour: "2-digit", minute: "2-digit" });
    };
    const start = normalize(startTime);
    const end = normalize(endTime);
    return start && end ? `${start} - ${end}` : start || end;
}

function getEventDateStatus(startDate, endDate) {
    const today = new Date();
    today.setHours(0, 0, 0, 0);

    const start = parseLocalDate(startDate);
    if (!start) {
        return {
            key: "upcoming",
            label: "Upcoming",
            badgeClass: "bg-yellow-400 text-gray-900",
        };
    }
    start.setHours(0, 0, 0, 0);

    const end = parseLocalDate(endDate || startDate) || start;
    end.setHours(0, 0, 0, 0);

    if (today < start) {
        return {
            key: "upcoming",
            label: "Upcoming",
            badgeClass: "bg-yellow-400 text-gray-900",
        };
    }

    if (today > end) {
        return {
            key: "past",
            label: "Past",
            badgeClass: "bg-red-500 text-white",
        };
    }

    return {
        key: "active",
        label: "Active",
        badgeClass: "bg-green-600 text-white",
    };
}

export default function EventsCTA({ eventsHref = "/events" }) {
    const [items, setItems] = useState([]);
    const [isLoading, setIsLoading] = useState(true);
    const [error, setError] = useState("");
    const [expandedEventId, setExpandedEventId] = useState(null);

    useEffect(() => {
        let isMounted = true;
        const controller = new AbortController();

        const load = async () => {
            setIsLoading(true);
            setError("");

            const today = new Date();
            const yyyy = today.getFullYear();
            const mm = String(today.getMonth() + 1).padStart(2, "0");
            const dd = String(today.getDate()).padStart(2, "0");
            const todayStr = `${yyyy}-${mm}-${dd}`;

            try {
                const res = await fetch(`/ajax/events?per_page=30&start_date=${todayStr}`, {
                    headers: { Accept: "application/json" },
                    signal: controller.signal,
                });
                const data = await res.json().catch(() => ({}));
                if (!res.ok) throw new Error(data?.message || "Failed to load events.");

                if (!isMounted) return;

                const rawItems = Array.isArray(data?.items) ? data.items : [];
                const todayDate = parseLocalDate(todayStr);

                const upcoming = rawItems
                    .filter((e) => {
                        const start = parseLocalDate(e?.start_date);
                        const end = parseLocalDate(e?.end_date || e?.start_date);
                        return start && end && todayDate && end >= todayDate;
                    })
                    .sort((a, b) => {
                        const da = parseLocalDate(a?.start_date)?.getTime() || 0;
                        const db = parseLocalDate(b?.start_date)?.getTime() || 0;
                        if (da !== db) return da - db;
                        const ta = String(a?.start_time || "");
                        const tb = String(b?.start_time || "");
                        return ta.localeCompare(tb);
                    })
                    .slice(0, 7);

                setItems(upcoming);
            } catch (e) {
                if (!isMounted) return;
                if (e?.name === "AbortError") return;
                setError(e?.message || "Failed to load events.");
                setItems([]);
            } finally {
                if (!isMounted) return;
                setIsLoading(false);
            }
        };

        load();

        return () => {
            isMounted = false;
            controller.abort();
        };
    }, []);

    const hasEvents = useMemo(() => items.length > 0, [items.length]);

    return (
        <section className="relative overflow-hidden bg-gradient-to-br from-[#0f2f45] via-[#153c56] to-[#1a4f73] py-10 sm:py-12">
            <div className="pointer-events-none absolute inset-0 bg-[radial-gradient(circle_at_15%_20%,rgba(251,191,36,0.22),transparent_35%),radial-gradient(circle_at_85%_15%,rgba(56,189,248,0.16),transparent_30%)]" />

            <div className="relative mx-auto max-w-10/12">
                <div className="mb-6 flex flex-wrap items-center justify-between gap-4">
                    <div>
                        <h2 className="text-2xl font-black text-white sm:text-3xl">Upcoming Events</h2>
                        <p className="mt-1 text-sm text-slate-200">Today and next events from OSHE Foundation.</p>
                    </div>

                    <Link
                        href={eventsHref}
                        className="inline-flex items-center rounded-full bg-amber-300 px-5 py-2.5 text-sm font-bold uppercase tracking-wide text-slate-900 transition hover:bg-amber-200"
                    >
                        View More
                    </Link>
                </div>

                <div className="rounded-2xl border border-white/20 bg-white/10 p-4 shadow-xl backdrop-blur-sm sm:p-5">
                    {isLoading ? (
                        <div className="space-y-3">
                            {Array.from({ length: 7 }).map((_, idx) => (
                                <div key={idx} className="skeleton h-16 w-full rounded-xl" />
                            ))}
                        </div>
                    ) : null}

                    {!isLoading && error ? (
                        <div className="rounded-xl border border-red-300/40 bg-red-500/15 p-4 text-sm text-red-100">{error}</div>
                    ) : null}

                    {!isLoading && !error && !hasEvents ? (
                        <div className="rounded-xl border border-white/20 bg-white/5 p-6 text-center text-sm text-slate-200">
                            No upcoming events found.
                        </div>
                    ) : null}

                    {!isLoading && !error && hasEvents ? (
                        <div className="space-y-3">
                            {items.map((event) => (
                                <article key={event.id} className="rounded-xl border border-white/15 bg-white/5 p-3 transition hover:bg-white/10">
                                    {(() => {
                                        const status = getEventDateStatus(event.start_date, event.end_date);
                                        return (
                                            <>
                                    <div className="flex items-center gap-3">
                                        <div className="flex h-14 w-14 shrink-0 flex-col items-center justify-center rounded-lg border border-amber-300/40 bg-amber-300/15 text-amber-200">
                                            <span className="text-[10px] font-bold tracking-wide">{formatDateMonth(event.start_date)}</span>
                                            <span className="text-lg font-black leading-none">{formatDateNum(event.start_date)}</span>
                                        </div>

                                        <div className="min-w-0 flex-1">
                                            <h3 className="line-clamp-1 text-sm font-bold text-white sm:text-base">{event.title}</h3>
                                            <p className="mt-0.5 text-xs text-slate-200 sm:text-sm">
                                                {formatDateRange(event.start_date, event.end_date)} • {formatTimeRange(event.start_time, event.end_time)}
                                            </p>
                                            <div className="mt-1.5 flex flex-wrap items-center gap-2">
                                                <span className={`rounded-full px-2 py-0.5 text-[10px] font-semibold ${status.badgeClass}`}>
                                                    {status.label}
                                                </span>
                                                {event.type_name ? (
                                                    <span
                                                        className="rounded-full px-2 py-0.5 text-[10px] font-semibold text-white"
                                                        style={{ backgroundColor: event.type_color || "#1f2937" }}
                                                    >
                                                        {event.type_name}
                                                    </span>
                                                ) : null}
                                            </div>
                                        </div>

                                        <button
                                            type="button"
                                            onClick={() => setExpandedEventId((prev) => (prev === event.id ? null : event.id))}
                                            className="inline-flex h-8 w-8 items-center justify-center rounded-full border border-white/25 bg-white/10 text-slate-100 transition hover:bg-white/20"
                                            aria-label={expandedEventId === event.id ? "Collapse description" : "Expand description"}
                                            aria-expanded={expandedEventId === event.id}
                                        >
                                            <ChevronDownIcon
                                                className={`h-4 w-4 transition-transform duration-200 ${expandedEventId === event.id ? "rotate-180" : ""}`}
                                            />
                                        </button>
                                    </div>

                                    {expandedEventId === event.id ? (
                                        <div className="mt-3 rounded-lg border border-white/15 bg-black/10 p-3">
                                            {event.description ? (
                                                <div
                                                    className="prose prose-invert max-w-none text-sm leading-relaxed text-slate-100"
                                                    dangerouslySetInnerHTML={{ __html: event.description }}
                                                />
                                            ) : (
                                                <p className="text-sm text-slate-200">No description available for this event.</p>
                                            )}
                                        </div>
                                    ) : null}
                                            </>
                                        );
                                    })()}
                                </article>
                            ))}
                        </div>
                    ) : null}
                </div>
            </div>
        </section>
    );
}
