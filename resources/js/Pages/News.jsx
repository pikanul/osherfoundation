import React, { useCallback, useEffect, useMemo, useRef, useState } from "react";
import axios from "axios";
import { Link, router, usePage } from "@inertiajs/react";
import Root from "../component/layout/Root";
import Breadcrumb from "../component/breadcrumb";
import { CalendarDaysIcon, MagnifyingGlassIcon } from "@heroicons/react/24/solid";

const stripHtml = (value = "") => String(value).replace(/<[^>]*>/g, " ").replace(/\s+/g, " ").trim();

const formatDate = (value) => {
    if (!value) return "";
    try {
        return new Date(value).toLocaleDateString([], { year: "numeric", month: "short", day: "2-digit" });
    } catch (e) {
        return value;
    }
};

export default function News() {
    const { props, url } = usePage();
    const { newsscategories = [] } = props;
    const searchParams = useMemo(() => new URLSearchParams(String(url || "").split("?")[1] || ""), [url]);
    const activeCategory = searchParams.get("category") || "";
    const initialKeyword = searchParams.get("keyword") || "";

    const [items, setItems] = useState([]);
    const [hasMore, setHasMore] = useState(true);
    const [cursor, setCursor] = useState({ beforeId: null, beforePublishDate: null });
    const [keyword, setKeyword] = useState(initialKeyword);
    const [isInitialLoading, setIsInitialLoading] = useState(true);
    const [isLoadingMore, setIsLoadingMore] = useState(false);
    const [error, setError] = useState("");
    const sentinelRef = useRef(null);

    const activeCategoryName = newsscategories.find((category) => category.slug === activeCategory)?.name || "All News & Resources";

    const fetchPage = useCallback(
        async ({ append = false, beforeId = null, beforePublishDate = null } = {}) => {
            setError("");
            try {
                const response = await axios.get("/ajax/news", {
                    params: {
                        per_page: 9,
                        category: activeCategory || undefined,
                        keyword: initialKeyword || undefined,
                        before_id: beforeId,
                        before_publish_date: beforePublishDate,
                    },
                });

                const nextItems = Array.isArray(response?.data?.items) ? response.data.items : [];
                setItems((prev) => (append ? [...prev, ...nextItems] : nextItems));
                setHasMore(Boolean(response?.data?.has_more));
                setCursor({
                    beforeId: response?.data?.next_before_id ?? null,
                    beforePublishDate: response?.data?.next_before_publish_date ?? null,
                });
            } catch (e) {
                setError(e?.response?.data?.message || "Failed to load resources.");
            }
        },
        [activeCategory, initialKeyword]
    );

    const loadInitial = useCallback(async () => {
        setIsInitialLoading(true);
        setHasMore(true);
        setCursor({ beforeId: null, beforePublishDate: null });
        await fetchPage({ append: false });
        setIsInitialLoading(false);
    }, [fetchPage]);

    const loadMore = useCallback(async () => {
        if (!hasMore || isInitialLoading || isLoadingMore) return;
        setIsLoadingMore(true);
        await fetchPage({
            append: true,
            beforeId: cursor.beforeId,
            beforePublishDate: cursor.beforePublishDate,
        });
        setIsLoadingMore(false);
    }, [cursor.beforeId, cursor.beforePublishDate, fetchPage, hasMore, isInitialLoading, isLoadingMore]);

    useEffect(() => {
        setKeyword(initialKeyword);
        loadInitial();
    }, [initialKeyword, loadInitial]);

    useEffect(() => {
        const el = sentinelRef.current;
        if (!el || !hasMore) return;

        const observer = new IntersectionObserver(
            ([entry]) => {
                if (entry?.isIntersecting) loadMore();
            },
            { root: null, rootMargin: "320px 0px" }
        );

        observer.observe(el);
        return () => observer.disconnect();
    }, [hasMore, loadMore]);

    const submitSearch = (event) => {
        event.preventDefault();
        router.get("/news", {
            category: activeCategory || undefined,
            keyword: keyword.trim() || undefined,
        });
    };

    const categoryHref = (slug = "") => (slug ? `/news?category=${slug}` : "/news");

    return (
        <Root>
            <Breadcrumb title="Media & Resource Center" subtitle={activeCategoryName} summary="Browse OSHE Foundation news, reports, publications, newsletters, and observations." />

            <main className="min-h-screen bg-slate-50 py-8">
                <div className="mx-auto max-w-10/12">
                    <section className="mb-6 rounded-md border border-slate-200 bg-white p-4 shadow-sm">
                        <div className="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                            <div>
                                <p className="text-sm font-bold uppercase tracking-[0.18em] text-emerald-700">Media & Resource Center</p>
                                <h1 className="mt-1 text-3xl font-black text-slate-900">{activeCategoryName}</h1>
                            </div>
                            <form onSubmit={submitSearch} className="flex w-full max-w-md">
                                <input
                                    value={keyword}
                                    onChange={(e) => setKeyword(e.target.value)}
                                    placeholder="Search resources..."
                                    className="h-11 min-w-0 flex-1 rounded-l-md border border-slate-300 px-3 text-sm outline-none focus:border-emerald-700"
                                />
                                <button type="submit" className="inline-flex h-11 w-12 items-center justify-center rounded-r-md bg-emerald-700 text-white">
                                    <MagnifyingGlassIcon className="h-5 w-5" />
                                </button>
                            </form>
                        </div>

                        <div className="mt-5 flex flex-wrap gap-2">
                            <Link href="/news" className={`rounded-full px-4 py-2 text-sm font-bold transition ${!activeCategory ? "bg-emerald-700 text-white" : "bg-slate-100 text-slate-700 hover:bg-emerald-50"}`}>
                                All News & Resources
                            </Link>
                            {newsscategories.map((category) => (
                                <Link
                                    key={category.slug}
                                    href={categoryHref(category.slug)}
                                    className={`rounded-full px-4 py-2 text-sm font-bold transition ${activeCategory === category.slug ? "bg-emerald-700 text-white" : "bg-slate-100 text-slate-700 hover:bg-emerald-50"}`}
                                >
                                    {category.name}
                                </Link>
                            ))}
                        </div>
                    </section>

                    {isInitialLoading && (
                        <div className="flex items-center justify-center py-16 text-slate-600">
                            <span className="loading loading-spinner loading-md" />
                            <span className="ml-3">Loading resources...</span>
                        </div>
                    )}

                    {!isInitialLoading && error && (
                        <div className="rounded-md border border-red-200 bg-red-50 p-4 text-sm text-red-700">{error}</div>
                    )}

                    {!isInitialLoading && !error && items.length === 0 && (
                        <div className="rounded-md border border-slate-200 bg-white p-8 text-center text-slate-600">No resources found for this category.</div>
                    )}

                    {!isInitialLoading && items.length > 0 && (
                        <div className="grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-3">
                            {items.map((item) => {
                                const summary = stripHtml(item.short_descripiton || item.long_description).slice(0, 180);

                                return (
                                    <article key={item.id} className="overflow-hidden rounded-md border border-slate-200 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
                                        {item.news_image_url && (
                                            <Link href={`/news/${item.id}`} className="block aspect-[16/9] bg-slate-100">
                                                <img src={item.news_image_url} alt={item.title} className="h-full w-full object-cover" />
                                            </Link>
                                        )}
                                        <div className="p-5">
                                            <div className="mb-3 flex items-center gap-2 text-sm font-semibold text-slate-500">
                                                <CalendarDaysIcon className="h-4 w-4 text-emerald-700" />
                                                {formatDate(item.publish_date)}
                                            </div>
                                            <h2 className="text-xl font-black leading-snug text-slate-900">
                                                <Link href={`/news/${item.id}`} className="hover:text-emerald-800">{item.title}</Link>
                                            </h2>
                                            {summary && <p className="mt-3 text-sm leading-6 text-slate-600">{summary}{summary.length >= 180 ? "..." : ""}</p>}
                                            <Link href={`/news/${item.id}`} className="mt-4 inline-flex text-sm font-bold text-emerald-800 hover:text-emerald-950">
                                                Read more
                                            </Link>
                                        </div>
                                    </article>
                                );
                            })}
                        </div>
                    )}

                    <div className="mt-10 flex justify-center">
                        <div ref={sentinelRef} />
                        {isLoadingMore && (
                            <div className="flex items-center gap-3 text-sm text-slate-600">
                                <span className="loading loading-spinner loading-sm" />
                                <span>Loading more...</span>
                            </div>
                        )}
                        {!hasMore && items.length > 0 && <div className="text-sm text-slate-500">No more resources.</div>}
                    </div>
                </div>
            </main>
        </Root>
    );
}
