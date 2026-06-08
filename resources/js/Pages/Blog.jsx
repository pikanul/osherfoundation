import React, { useCallback, useEffect, useMemo, useRef, useState } from "react";
import { Link, usePage } from "@inertiajs/react";
import axios from "axios";
import { ChevronDownIcon } from "@heroicons/react/24/solid";
import Root from "../component/layout/Root";
import Breadcrumb from '../component/breadcrumb';

export default function Blog() {
    const { props, url } = usePage();
    const blogCategories = Array.isArray(props.blog_categories) ? props.blog_categories : [];
    const appUrl = (props.app_url || "").replace(/\/+$/, "");
    const withAppUrl = (path) => (appUrl ? `${appUrl}${path}` : path);
    const keywordFromUrl = useMemo(() => {
        const queryString = typeof url === "string" && url.includes("?") ? url.split("?")[1] : "";
        const queryParams = new URLSearchParams(queryString);
        return (queryParams.get("keyword") || "").trim();
    }, [url]);

    const [items, setItems] = useState([]);
    const [hasMore, setHasMore] = useState(true);
    const [cursor, setCursor] = useState({ beforeId: null, beforePublishDate: null });
    const [isInitialLoading, setIsInitialLoading] = useState(true);
    const [isLoadingMore, setIsLoadingMore] = useState(false);
    const [error, setError] = useState("");

    const [selectedCategoryId, setSelectedCategoryId] = useState("");
    const [keywordQuery, setKeywordQuery] = useState(keywordFromUrl);

    const sentinelRef = useRef(null);

    const canLoadMore = useMemo(
        () => hasMore && !isInitialLoading && !isLoadingMore,
        [hasMore, isInitialLoading, isLoadingMore]
    );

    const fetchPage = useCallback(
        async ({ beforeId = null, beforePublishDate = null, append = false, categoryId = "", keyword = "" } = {}) => {
            setError("");
            try {
                const response = await axios.get("/ajax/blog", {
                    params: {
                        per_page: 9,
                        before_id: beforeId,
                        before_publish_date: beforePublishDate,
                        category_id: categoryId || undefined,
                        keyword: keyword || undefined,
                    },
                });

                const nextItems = Array.isArray(response?.data?.items) ? response.data.items : [];
                const nextHasMore = Boolean(response?.data?.has_more);
                const nextBeforeId = response?.data?.next_before_id ?? null;
                const nextBeforePublishDate = response?.data?.next_before_publish_date ?? null;

                setItems((prev) => (append ? [...prev, ...nextItems] : nextItems));
                setHasMore(nextHasMore);
                setCursor({ beforeId: nextBeforeId, beforePublishDate: nextBeforePublishDate });
            } catch (e) {
                setError(e?.response?.data?.message || "Failed to load blogs.");
            }
        },
        []
    );

    const loadInitial = useCallback(async () => {
        setIsInitialLoading(true);
        await fetchPage({ append: false, categoryId: selectedCategoryId, keyword: keywordQuery });
        setIsInitialLoading(false);
    }, [fetchPage, selectedCategoryId, keywordQuery]);

    const loadMore = useCallback(async () => {
        if (!canLoadMore) return;
        setIsLoadingMore(true);
        await fetchPage({
            beforeId: cursor.beforeId,
            beforePublishDate: cursor.beforePublishDate,
            append: true,
            categoryId: selectedCategoryId,
            keyword: keywordQuery,
        });
        setIsLoadingMore(false);
    }, [canLoadMore, cursor.beforeId, cursor.beforePublishDate, fetchPage, selectedCategoryId, keywordQuery]);

    useEffect(() => {
        loadInitial();
    }, [loadInitial]);

    useEffect(() => {
        setKeywordQuery(keywordFromUrl);
    }, [keywordFromUrl]);

    useEffect(() => {
        const el = sentinelRef.current;
        if (!el) return;
        if (!hasMore) return;

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
    }, [hasMore, loadMore]);

    const onResetFilters = () => {
        setSelectedCategoryId("");
    };

    return (
        <Root>
            <Breadcrumb title="OSHE Foundation Blog" subtitle="Insights, stories, and updates" summary="Explore our latest articles and field updates." />

            <section className="bg-slate-50 py-8 sm:py-10">
                <div className="mx-auto grid max-w-10/12 grid-cols-1 gap-6 lg:grid-cols-12">
                    <aside className="lg:col-span-4 xl:col-span-3">
                        <div className="sticky top-24">
                            <div className="overflow-hidden border border-white/60 bg-[#eef1f4]">
                                <label className="flex cursor-pointer items-center justify-between border-b border-white bg-[#d7dde3] px-4 py-3 text-[18px] font-medium text-[#25004f] transition hover:bg-[#cfd6de]">
                                    <input
                                        type="radio"
                                        name="blog-category-filter"
                                        value=""
                                        checked={selectedCategoryId === ""}
                                        onChange={(e) => setSelectedCategoryId(e.target.value)}
                                        className="sr-only"
                                    />
                                    <span>All Categories</span>
                                    <ChevronDownIcon className="h-5 w-5" />
                                </label>

                                {blogCategories.map((category) => (
                                    <label
                                        key={category.id}
                                        className={`flex cursor-pointer items-center justify-between border-b border-white px-4 py-3 text-[18px] font-medium text-[#25004f] transition ${selectedCategoryId === String(category.id) ? "bg-[#cfd6de]" : "bg-[#d7dde3] hover:bg-[#cfd6de]"}`}
                                    >
                                        <input
                                            type="radio"
                                            name="blog-category-filter"
                                            value={String(category.id)}
                                            checked={selectedCategoryId === String(category.id)}
                                            onChange={(e) => setSelectedCategoryId(e.target.value)}
                                            className="sr-only"
                                        />
                                        <span>{category.name}</span>
                                        <ChevronDownIcon className="h-5 w-5" />
                                    </label>
                                ))}
                            </div>
                            <button
                                type="button"
                                onClick={onResetFilters}
                                className="mt-4 w-full border border-slate-300 bg-white px-3 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-100"
                            >
                                Reset Category
                            </button>
                        </div>
                    </aside>

                    <div className="lg:col-span-8 xl:col-span-9">
                        {keywordQuery && (
                            <div className="mb-4 flex flex-wrap items-center justify-between gap-3 rounded-xl border border-[#25004f]/15 bg-white px-4 py-3 text-sm">
                                <p className="font-semibold text-slate-700">
                                    Showing results for: <span className="text-[#25004f]">{keywordQuery}</span>
                                </p>
                                <Link href={withAppUrl('/blog')} className="font-bold text-[#25004f] hover:underline">
                                    Clear keyword
                                </Link>
                            </div>
                        )}


                        {isInitialLoading && (
                            <div className="flex items-center justify-center rounded-2xl border border-slate-200 bg-white py-20">
                                <div className="flex items-center gap-3 text-slate-700">
                                    <span className="loading loading-spinner loading-md" />
                                    <span>Loading blogs...</span>
                                </div>
                            </div>
                        )}

                        {!isInitialLoading && error && (
                            <div className="mb-4 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                                {error}{" "}
                                <button
                                    type="button"
                                    onClick={loadInitial}
                                    className="font-semibold underline hover:no-underline"
                                >
                                    Retry
                                </button>
                            </div>
                        )}

                        {!isInitialLoading && !error && items.length === 0 && (
                            <div className="rounded-xl border border-slate-200 bg-white p-6 text-center text-sm text-slate-600">
                                No blog posts found.
                            </div>
                        )}

                        {!isInitialLoading && items.length > 0 && (
                            <div className="grid grid-cols-1 gap-6 sm:grid-cols-2 xl:grid-cols-3">
                                {items.map((post) => (
                                    <Link
                                        key={post.id}
                                        href={withAppUrl(`/blog/${post.slug || post.id}`)}
                                        className="group block overflow-hidden border border-slate-200 bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-xl"
                                    >
                                        <div className="relative">
                                            <img
                                                src={post.image_url}
                                                alt={post.title}
                                                className="h-52 w-full object-cover transition duration-300 group-hover:scale-[1.03]"
                                                loading="lazy"
                                            />
                                            <div className="absolute inset-x-0 bottom-0 h-24 bg-gradient-to-t from-black/50 to-transparent" />
                                            {post.publish_date && (
                                                <time className="absolute left-3 top-3 rounded-md bg-white/90 px-2 py-1 text-xs font-semibold text-slate-700 shadow">
                                                    {new Date(post.publish_date).toLocaleDateString()}
                                                </time>
                                            )}
                                        </div>
                                        <div className="p-4">
                                            <h3 className="line-clamp-2 text-base font-bold text-slate-900">{post.title}</h3>
                                            {post.short_description && (
                                                <p className="mt-2 line-clamp-3 text-sm text-slate-600">
                                                    {post.short_description}
                                                </p>
                                            )}
                                            <span className="mt-3 inline-block text-xs font-bold uppercase tracking-wide text-[#0f2f45]">
                                                Read Details
                                            </span>
                                        </div>
                                        <div className="h-1 bg-[#ff2a57]" />
                                    </Link>
                                ))}
                            </div>
                        )}

                        <div className="mt-10 flex justify-center">
                            <div ref={sentinelRef} />
                            {isLoadingMore && (
                                <div className="inline-flex items-center gap-3 rounded-full border border-slate-200 bg-white px-4 py-2 text-sm text-slate-600 shadow-sm">
                                    <span className="loading loading-spinner loading-sm" />
                                    <span>Loading more posts...</span>
                                </div>
                            )}
                            {!hasMore && items.length > 0 && (
                                <div className="rounded-full bg-slate-200 px-4 py-2 text-sm font-semibold text-slate-600">No more posts.</div>
                            )}
                        </div>
                    </div>
                </div>
            </section>
        </Root>
    );
}
