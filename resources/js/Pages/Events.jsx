import React, { useCallback, useEffect, useMemo, useRef, useState } from "react";
import axios from "axios";
import Root from "../component/layout/Root";
import Breadcrumb from '../component/breadcrumb';
import { ChevronDownIcon } from "@heroicons/react/24/solid";

const formatDate = (value) => {
    if (!value) return "";
    try {
        return new Date(value).toLocaleDateString();
    } catch (e) {
        return value;
    }
};

const formatMonthShort = (value) => {
    if (!value) return "";
    try {
        return new Date(value).toLocaleDateString([], { month: "short" }).toUpperCase();
    } catch (e) {
        return "";
    }
};

const formatDayNum = (value) => {
    if (!value) return "";
    try {
        return new Date(value).toLocaleDateString([], { day: "2-digit" });
    } catch (e) {
        return "";
    }
};

const formatTimeRange = (startTime, endTime) => {
    if (!startTime && !endTime) return "";
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
};

const parseLocalDate = (dateStr) => {
    if (!dateStr || typeof dateStr !== "string") return null;
    const parts = dateStr.split("-").map(Number);
    if (parts.length !== 3 || parts.some(Number.isNaN)) return null;
    const [year, month, day] = parts;
    return new Date(year, month - 1, day);
};

const formatInputDate = (date) => {
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, "0");
    const day = String(date.getDate()).padStart(2, "0");
    return `${year}-${month}-${day}`;
};

const getDefaultDateRange = () => {
    const today = new Date();
    const start = new Date(today);
    const end = new Date(today);
    start.setDate(start.getDate() - 30);
    end.setDate(end.getDate() + 30);

    return {
        start_date: formatInputDate(start),
        end_date: formatInputDate(end),
    };
};

const getEventDateStatus = (startDate, endDate) => {
    const today = new Date();
    today.setHours(0, 0, 0, 0);

    const start = parseLocalDate(startDate);
    if (!start) {
        return {
            key: "upcoming",
            label: "Upcoming",
            badgeClass: "bg-yellow-400 text-gray-900",
            cardClass: "border-yellow-300",
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
            cardClass: "border-yellow-300",
        };
    }

    if (today > end) {
        return {
            key: "past",
            label: "Past",
            badgeClass: "bg-red-500 text-white",
            cardClass: "border-red-300",
        };
    }

    return {
        key: "active",
        label: "Active",
        badgeClass: "bg-green-600 text-white",
        cardClass: "border-green-300",
    };
};

export default function Events() {
    const [eventTypes, setEventTypes] = useState([]);
    const [selectedTypeId, setSelectedTypeId] = useState("all");
    const [dateRange, setDateRange] = useState(getDefaultDateRange);

    const [items, setItems] = useState([]);
    const [hasMore, setHasMore] = useState(true);
    const [cursor, setCursor] = useState({ beforeId: null, beforeStartDate: null });

    const [isInitialLoading, setIsInitialLoading] = useState(true);
    const [isLoadingMore, setIsLoadingMore] = useState(false);
    const [error, setError] = useState("");
    const [expandedEventId, setExpandedEventId] = useState(null);

    const sentinelRef = useRef(null);

    const canLoadMore = useMemo(
        () => hasMore && !isInitialLoading && !isLoadingMore,
        [hasMore, isInitialLoading, isLoadingMore]
    );

    const hasInvalidDateRange = useMemo(() => {
        if (!dateRange.start_date || !dateRange.end_date) return false;
        return dateRange.start_date > dateRange.end_date;
    }, [dateRange.end_date, dateRange.start_date]);

    const loadEventTypes = useCallback(async () => {
        try {
            const response = await axios.get("/ajax/event-types");
            const nextTypes = Array.isArray(response?.data?.items) ? response.data.items : [];
            setEventTypes(nextTypes);
        } catch (e) {
            // keep filter usable with "All Types" even if type endpoint fails
            setEventTypes([]);
        }
    }, []);

    const fetchPage = useCallback(
        async ({
            beforeId = null,
            beforeStartDate = null,
            append = false,
            typeId = selectedTypeId,
            startDate = dateRange.start_date,
            endDate = dateRange.end_date,
        } = {}) => {
            if (startDate && endDate && startDate > endDate) {
                setError("Start date cannot be after end date.");
                return;
            }

            setError("");
            try {
                const response = await axios.get("/ajax/events", {
                    params: {
                        per_page: 9,
                        before_id: beforeId,
                        before_start_date: beforeStartDate,
                        type_id: typeId === "all" ? null : typeId,
                        start_date: startDate || null,
                        end_date: endDate || null,
                    },
                });

                const nextItems = Array.isArray(response?.data?.items) ? response.data.items : [];
                const nextHasMore = Boolean(response?.data?.has_more);
                const nextBeforeId = response?.data?.next_before_id ?? null;
                const nextBeforeStartDate = response?.data?.next_before_start_date ?? null;

                setItems((prev) => (append ? [...prev, ...nextItems] : nextItems));
                setHasMore(nextHasMore);
                setCursor({ beforeId: nextBeforeId, beforeStartDate: nextBeforeStartDate });
            } catch (e) {
                setError(e?.response?.data?.message || "Failed to load events.");
            }
        },
        [dateRange.end_date, dateRange.start_date, selectedTypeId]
    );

    const loadInitial = useCallback(async () => {
        setIsInitialLoading(true);
        setHasMore(true);
        setCursor({ beforeId: null, beforeStartDate: null });
        await fetchPage({
            append: false,
            beforeId: null,
            beforeStartDate: null,
        });
        setIsInitialLoading(false);
    }, [fetchPage]);

    const loadMore = useCallback(async () => {
        if (!canLoadMore || hasInvalidDateRange) return;
        setIsLoadingMore(true);
        await fetchPage({
            beforeId: cursor.beforeId,
            beforeStartDate: cursor.beforeStartDate,
            append: true,
        });
        setIsLoadingMore(false);
    }, [canLoadMore, cursor.beforeId, cursor.beforeStartDate, fetchPage, hasInvalidDateRange]);

    useEffect(() => {
        loadEventTypes();
    }, [loadEventTypes]);

    useEffect(() => {
        loadInitial();
    }, [loadInitial, selectedTypeId, dateRange.start_date, dateRange.end_date]);

    useEffect(() => {
        const el = sentinelRef.current;
        if (!el) return;
        if (!hasMore || hasInvalidDateRange) return;

        const observer = new IntersectionObserver(
            (entries) => {
                const entry = entries[0];
                if (!entry?.isIntersecting) return;
                loadMore();
            },
            { root: null, rootMargin: "320px 0px" }
        );

        observer.observe(el);
        return () => observer.disconnect();
    }, [hasMore, loadMore, hasInvalidDateRange]);

    return (
        <Root>
            <Breadcrumb title="OSHE Foundation Events" subtitle="Programs, workshops, and activities" summary="Browse upcoming and past events from OSHE Foundation." />


            <div className="bg-gray-50 min-h-screen">
                <div className="max-w-10/12 mx-auto  py-8">
                    <div className="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6 mb-6">
                        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                            <div>
                                <label className="block text-xs font-semibold text-gray-700 mb-1">Event Type</label>
                                <select
                                    value={selectedTypeId}
                                    onChange={(e) => setSelectedTypeId(e.target.value)}
                                    className="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300"
                                >
                                    <option value="all">All Types</option>
                                    {eventTypes.map((type) => (
                                        <option key={type.id} value={type.id}>
                                            {type.name}
                                        </option>
                                    ))}
                                </select>
                            </div>

                            <div>
                                <label className="block text-xs font-semibold text-gray-700 mb-1">Start Date</label>
                                <input
                                    type="date"
                                    value={dateRange.start_date}
                                    onChange={(e) => setDateRange((prev) => ({ ...prev, start_date: e.target.value }))}
                                    className="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300"
                                />
                            </div>

                            <div>
                                <label className="block text-xs font-semibold text-gray-700 mb-1">End Date</label>
                                <input
                                    type="date"
                                    value={dateRange.end_date}
                                    onChange={(e) => setDateRange((prev) => ({ ...prev, end_date: e.target.value }))}
                                    className="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300"
                                />
                            </div>

                            <div className="flex items-end">
                                <button
                                    type="button"
                                    onClick={() => {
                                        setSelectedTypeId("all");
                                        setDateRange(getDefaultDateRange());
                                    }}
                                    className="w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-100"
                                >
                                    Clear Filters
                                </button>
                            </div>
                        </div>
                    </div>

                    {hasInvalidDateRange && (
                        <div className="text-sm text-red-600 mb-4">
                            Start date cannot be after end date.
                        </div>
                    )}

                    {isInitialLoading && (
                        <div className="flex items-center justify-center py-16">
                            <div className="flex items-center gap-3 text-gray-700">
                                <span className="loading loading-spinner loading-md" />
                                <span>Loading events...</span>
                            </div>
                        </div>
                    )}

                    {!isInitialLoading && error && (
                        <div className="text-sm text-red-600 mb-4">
                            {error}{" "}
                            <button type="button" onClick={loadInitial} className="underline hover:no-underline">
                                Retry
                            </button>
                        </div>
                    )}

                    {!isInitialLoading && !error && items.length === 0 && (
                        <div className="text-sm text-gray-600">No events found for this filter.</div>
                    )}

                    {!isInitialLoading && items.length > 0 && (
                        <div className="grid grid-cols-1 gap-5">
                            {items.map((event) => {
                                const status = getEventDateStatus(event.start_date, event.end_date);
                                const isExpanded = expandedEventId === event.id;
                                return (
                                    <article
                                        key={event.id}
                                        className={`overflow-hidden rounded-2xl border bg-white shadow-sm transition-all duration-200 hover:shadow-md ${status.cardClass}`}
                                    >
                                        <div className="flex flex-col sm:flex-row">
                                            <div className="relative flex shrink-0 items-center justify-center border-b border-dashed border-gray-200 bg-gradient-to-b from-[#0f2f45] to-[#1f4f6f] px-6 py-5 sm:w-[120px] sm:border-b-0 sm:border-r">
                                                <div className="text-center text-white">
                                                    <p className="text-[11px] font-extrabold tracking-[0.14em]">
                                                        {formatMonthShort(event.start_date)}
                                                    </p>
                                                    <p className="mt-1 text-3xl font-black leading-none">
                                                        {formatDayNum(event.start_date)}
                                                    </p>
                                                </div>
                                            </div>

                                            <div className="flex-1 p-4 sm:p-5">
                                                <div className="flex items-start justify-between gap-3">
                                                    <div className="min-w-0">
                                                        <h3 className="text-base font-bold text-gray-900 sm:text-lg">{event.title}</h3>
                                                        <div className="mt-2 flex flex-wrap items-center gap-2">
                                                            <span className={`text-xs font-semibold px-2.5 py-1 rounded-full ${status.badgeClass}`}>
                                                                {status.label}
                                                            </span>
                                                            {event.type_name && (
                                                                <span
                                                                    className="text-xs font-semibold px-2.5 py-1 rounded-full text-white"
                                                                    style={{ backgroundColor: event.type_color || "#1f2937" }}
                                                                >
                                                                    {event.type_name}
                                                                </span>
                                                            )}
                                                        </div>
                                                    </div>

                                                    <button
                                                        type="button"
                                                        onClick={() =>
                                                            setExpandedEventId((prev) => (prev === event.id ? null : event.id))
                                                        }
                                                        className="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-full border border-gray-300 bg-white text-gray-700 transition hover:bg-gray-100"
                                                        aria-label={isExpanded ? "Collapse description" : "Expand description"}
                                                        aria-expanded={isExpanded}
                                                    >
                                                        <ChevronDownIcon
                                                            className={`h-4 w-4 transition-transform duration-200 ${
                                                                isExpanded ? "rotate-180" : ""
                                                            }`}
                                                        />
                                                    </button>
                                                </div>

                                                <div className="mt-4 grid grid-cols-1 gap-2 text-sm text-gray-700">
                                                    <p>
                                                        <span className="font-semibold">Date:</span>{" "}
                                                        {formatDate(event.start_date)}
                                                        {event.end_date && event.end_date !== event.start_date
                                                            ? ` - ${formatDate(event.end_date)}`
                                                            : ""}
                                                    </p>
                                                    {(event.start_time || event.end_time) && (
                                                        <p>
                                                            <span className="font-semibold">Time:</span>{" "}
                                                            {formatTimeRange(event.start_time, event.end_time)}
                                                        </p>
                                                    )}
                                                </div>

                                                {isExpanded ? (
                                                    <div className="mt-4 rounded-lg border border-gray-200 bg-gray-50 p-3">
                                                        {event.description ? (
                                                            <div
                                                                className="prose rich-content max-w-none text-sm text-gray-700"
                                                                dangerouslySetInnerHTML={{ __html: event.description }}
                                                            />
                                                        ) : (
                                                            <p className="text-sm text-gray-600">No description available for this event.</p>
                                                        )}
                                                    </div>
                                                ) : null}
                                            </div>
                                        </div>
                                    </article>
                                );
                            })}
                        </div>
                    )}

                    <div className="flex justify-center mt-10">
                        <div ref={sentinelRef} />
                        {isLoadingMore && (
                            <div className="flex items-center gap-3 text-gray-600 text-sm">
                                <span className="loading loading-spinner loading-sm" />
                                <span>Loading more...</span>
                            </div>
                        )}
                        {!hasMore && items.length > 0 && (
                            <div className="text-gray-500 text-sm">No more events.</div>
                        )}
                    </div>
                </div>
            </div>
        </Root>
    );
}
