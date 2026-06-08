import React, { useCallback, useEffect, useMemo, useRef, useState } from "react";
import { Link, usePage } from "@inertiajs/react";
import axios from "axios";
import { ChevronDownIcon } from "@heroicons/react/24/solid";
import Root from "../component/layout/Root";
import Breadcrumb from "../component/breadcrumb";

const formatPublishDate = (value) => {
    if (!value) return "";
    try {
        return new Date(value).toLocaleDateString([], {
            year: "numeric",
            month: "short",
            day: "numeric",
        });
    } catch (e) {
        return value;
    }
};

export default function News() {
    const { props } = usePage();
    const {
        news_categories,
        active_category_slug,
        initial_news,
        news_has_more,
        news_before_id,
        news_before_publish_date,
        search_keyword,
    } = props;

    const categories = Array.isArray(news_categories) ? news_categories : [];
    const appUrl = (props.app_url || "").replace(/\/+$/, "");
    const withAppUrl = (path) => (appUrl ? `${appUrl}${path}` : path);

    const [items, setItems] = useState(Array.isArray(initial_news) ? initial_news : []);
    const [hasMore, setHasMore] = useState(Boolean(news_has_more));
    const [beforeId, setBeforeId] = useState(typeof news_before_id === "number" ? news_before_id : null);
    const [beforePublishDate, setBeforePublishDate] = useState(
        typeof news_before_publish_date === "string" ? news_before_publish_date : null
    );
    const [isLoadingMore, setIsLoadingMore] = useState(false);
    const [error, setError] = useState("");

    const sentinelRef = useRef(null);

    const loadMore = useCallback(async () => {
        if (!hasMore || isLoadingMore) return;

        setIsLoadingMore(true);
        setError("");

        try {
            const response = await axios.get("/ajax/news", {
                params: {
                    before_id: beforeId,
                    before_publish_date: beforePublishDate,
                    category: active_category_slug || undefined,
                    keyword: search_keyword || undefined,
                },
            });

            const nextItems = Array.isArray(response?.data?.items) ? response.data.items : [];
            const nextBeforeId = response?.data?.next_before_id ?? null;
            const nextBeforePublishDate = response?.data?.next_before_publish_date ?? null;
            const nextHasMore = Boolean(response?.data?.has_more);

            setItems((prev) => [...prev, ...nextItems]);
            setBeforeId(nextBeforeId);
            setBeforePublishDate(nextBeforePublishDate);
            setHasMore(nextHasMore);
        } catch (e) {
            setError(e?.response?.data?.message || "Failed to load more news.");
        } finally {
            setIsLoadingMore(false);
        }
    }, [hasMore, isLoadingMore, beforeId, beforePublishDate, active_category_slug, search_keyword]);

    const canObserve = useMemo(() => hasMore, [hasMore]);

    useEffect(() => {
        const el = sentinelRef.current;
        if (!el) return;
        if (!canObserve) return;

        const observer = new IntersectionObserver(
            (entries) => {
                const entry = entries[0];
                if (!entry?.isIntersecting) return;
                loadMore();
            },
            { root: null, rootMargin: "300px 0px" }
        );

        observer.observe(el);
        return () => observer.disconnect();
    }, [canObserve, loadMore]);

    return (
        <Root>
            <Breadcrumb title="News and Updates" subtitle="Latest News" summary="" />

            <section className="bg-slate-50 py-8 sm:py-10">
                <div className="mx-auto grid max-w-10/12 grid-cols-1 gap-6 lg:grid-cols-12">
                    {search_keyword ? (
                        <div className="rounded-xl border border-[#25004f]/15 bg-white px-4 py-3 text-sm lg:col-span-12">
                            <p className="font-semibold text-slate-700">
                                News search result for: <span className="text-[#25004f]">{search_keyword}</span>
                            </p>
                        </div>
                    ) : null}

                    <aside className="lg:col-span-4 xl:col-span-3">
                        <div className="sticky top-24">
                            <div className="overflow-hidden border border-white/60 bg-[#eef1f4]">
                                <Link
                                    href={withAppUrl(`/news${search_keyword ? `?keyword=${encodeURIComponent(search_keyword)}` : ""}`)}
                                    className={`flex items-center justify-between border-b border-white px-4 py-3 text-[18px] font-medium text-[#25004f] transition ${!active_category_slug ? "bg-[#cfd6de]" : "bg-[#d7dde3] hover:bg-[#cfd6de]"}`}
                                >
                                    <span>All Categories</span>
                                    <ChevronDownIcon className="h-5 w-5" />
                                </Link>

                                {categories.map((cat) => (
                                    <Link
                                        key={cat.id}
                                        href={withAppUrl(`/news?category=${cat.slug}${search_keyword ? `&keyword=${encodeURIComponent(search_keyword)}` : ""}`)}
                                        className={`flex items-center justify-between border-b border-white px-4 py-3 text-[18px] font-medium text-[#25004f] transition ${active_category_slug === cat.slug ? "bg-[#cfd6de]" : "bg-[#d7dde3] hover:bg-[#cfd6de]"}`}
                                    >
                                        <span>{cat.name}</span>
                                        <ChevronDownIcon className="h-5 w-5" />
                                    </Link>
                                ))}
                            </div>

                            <Link
                                href={withAppUrl(`/news${search_keyword ? `?keyword=${encodeURIComponent(search_keyword)}` : ""}`)}
                                className="mt-4 block w-full border border-slate-300 bg-white px-3 py-2 text-center text-sm font-semibold text-slate-700 transition hover:bg-slate-100"
                            >
                                Reset Category
                            </Link>
                        </div>
                    </aside>

                    <div className="lg:col-span-8 xl:col-span-9">
                        {items.length > 0 ? (
                            <div className="grid grid-cols-1 gap-6 sm:grid-cols-2 xl:grid-cols-3">
                                {items.map((news) => (
                                    <Link
                                        key={news.id}
                                        href={withAppUrl(`/news/${news.id}`)}
                                        className="group block overflow-hidden border border-slate-300 bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-xl"
                                    >
                                        <div className="relative">
                                            <img
                                                src={news.news_image_url}
                                                alt={news.title}
                                                className="h-52 w-full object-cover transition duration-300 group-hover:scale-[1.03]"
                                                loading="lazy"
                                            />
                                            {news.publish_date && (
                                                <time className="absolute left-3 top-3 rounded-md bg-white/95 px-2 py-1 text-xs font-semibold text-slate-700 shadow">
                                                    {formatPublishDate(news.publish_date)}
                                                </time>
                                            )}
                                        </div>

                                        <div className="p-4">
                                            <h3 className="line-clamp-2 text-lg font-extrabold text-slate-900">{news.title}</h3>
                                            {news.short_descripiton ? (
                                                <p className="mt-2 line-clamp-2 text-sm text-slate-600">{news.short_descripiton}</p>
                                            ) : null}
                                            <span className="mt-3 inline-block text-xs font-bold uppercase tracking-wide text-[#0f2f45]">
                                                Read Details
                                            </span>
                                        </div>
                                        <div className="h-1 bg-[#ff2a57]" />
                                    </Link>
                                ))}
                            </div>
                        ) : (
                            <div className="rounded-xl border border-slate-200 bg-white p-6 text-center text-sm text-slate-600">
                                No news found.
                            </div>
                        )}

                        {error ? (
                            <div className="mt-4 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">{error}</div>
                        ) : null}

                        <div className="mt-8 flex justify-center">
                            <div ref={sentinelRef} />
                            {isLoadingMore ? (
                                <div className="inline-flex items-center gap-3 rounded-full border border-slate-200 bg-white px-4 py-2 text-sm text-slate-600 shadow-sm">
                                    <span className="loading loading-spinner loading-sm" />
                                    <span>Loading more posts...</span>
                                </div>
                            ) : null}
                            {!hasMore && items.length > 0 ? (
                                <div className="rounded-full bg-slate-200 px-4 py-2 text-sm font-semibold text-slate-600">No more posts.</div>
                            ) : null}
                        </div>
                    </div>
                </div>
            </section>
        </Root>
    );
}
