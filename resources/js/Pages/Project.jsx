import React, { useCallback, useEffect, useMemo, useRef, useState } from "react";
import { Link, usePage } from "@inertiajs/react";
import axios from "axios";
import Root from "../component/layout/Root";
import Breadcrumb from '../component/breadcrumb';

export default function Project() {
    const { props } = usePage();
    const { project_categories, active_category, initial_projects, projects_has_more, projects_before_id } = props;

    const [items, setItems] = useState(Array.isArray(initial_projects) ? initial_projects : []);
    const [hasMore, setHasMore] = useState(Boolean(projects_has_more));
    const [beforeId, setBeforeId] = useState(typeof projects_before_id === "number" ? projects_before_id : null);
    const [isLoadingMore, setIsLoadingMore] = useState(false);
    const [error, setError] = useState("");

    const sentinelRef = useRef(null);

    const activeSlug = active_category?.slug || null;

    useEffect(() => {
        setItems(Array.isArray(initial_projects) ? initial_projects : []);
        setHasMore(Boolean(projects_has_more));
        setBeforeId(typeof projects_before_id === "number" ? projects_before_id : null);
        setError("");
        setIsLoadingMore(false);
    }, [activeSlug, initial_projects, projects_before_id, projects_has_more]);

    const loadMore = useCallback(async () => {
        if (!activeSlug) return;
        if (!hasMore || isLoadingMore) return;

        setIsLoadingMore(true);
        setError("");

        try {
            const response = await axios.get(`/ajax/project/${activeSlug}`, {
                params: { per_page: 20, before_id: beforeId },
            });

            const nextItems = Array.isArray(response?.data?.items) ? response.data.items : [];
            const nextHasMore = Boolean(response?.data?.has_more);
            const nextBeforeId = response?.data?.next_before_id ?? null;

            setItems((prev) => [...prev, ...nextItems]);
            setHasMore(nextHasMore);
            setBeforeId(nextBeforeId);
        } catch (e) {
            setError(e?.response?.data?.message || "Failed to load more projects.");
        } finally {
            setIsLoadingMore(false);
        }
    }, [activeSlug, beforeId, hasMore, isLoadingMore]);

    const canObserve = useMemo(() => Boolean(activeSlug) && hasMore, [activeSlug, hasMore]);

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
            { root: null, rootMargin: "350px 0px" }
        );

        observer.observe(el);
        return () => observer.disconnect();
    }, [canObserve, loadMore]);

    return (
        <Root>
            <Breadcrumb title="Projects" subtitle={active_category?.name || "Project"} summary="Browse project categories and implementation updates." />

            <section className="bg-slate-50 py-8 sm:py-10">
                <div className="mx-auto max-w-10/12">
                    <div className="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                        <div className="flex flex-wrap items-center justify-between gap-3">
                            <div>
                                <h2 className="text-xl font-black text-slate-900 sm:text-2xl">Project Directory</h2>
                                <p className="mt-1 text-sm text-slate-600">Filter by category and explore implementation details.</p>
                            </div>
                            <div className="rounded-full bg-blue-50 px-4 py-2 text-sm font-bold text-blue-900">
                                {items.length} Project{items.length === 1 ? "" : "s"}
                            </div>
                        </div>

                        <div className="mt-5 flex flex-wrap gap-2">
                            {(project_categories || []).map((cat) => {
                                const isActive = activeSlug && cat.slug === activeSlug;
                                return (
                                    <Link
                                        key={cat.id}
                                        href={`/project/${cat.slug}`}
                                        preserveScroll
                                        className={[
                                            "rounded-full border px-4 py-2 text-sm font-semibold transition",
                                            isActive
                                                ? "border-[#0f2f45] bg-[#0f2f45] text-white shadow"
                                                : "border-slate-300 bg-white text-slate-700 hover:border-[#0f2f45]/40 hover:bg-slate-50",
                                        ].join(" ")}
                                        aria-current={isActive ? "page" : undefined}
                                    >
                                        {cat.name}
                                    </Link>
                                );
                            })}
                        </div>
                    </div>

                    {!active_category && (
                        <div className="mt-6 rounded-xl border border-amber-200 bg-amber-50 p-5 text-center text-amber-800">
                            No project categories found.
                        </div>
                    )}

                    {active_category && items.length === 0 && (
                        <div className="mt-6 rounded-xl border border-slate-200 bg-white p-7 text-center text-slate-600">
                            No projects found in this category.
                        </div>
                    )}

                    {items.length > 0 && (
                        <>
                            <div className="mt-6 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                                <div className="hidden overflow-x-auto md:block">
                                    <table className="w-full text-left">
                                        <thead className="bg-slate-100 text-xs uppercase tracking-wide text-slate-600">
                                            <tr>
                                                <th className="px-6 py-4">Project Name</th>
                                                <th className="px-6 py-4">Funded By</th>
                                                <th className="px-6 py-4">Duration</th>
                                            </tr>
                                        </thead>
                                        <tbody className="divide-y divide-slate-100">
                                            {items.map((p) => (
                                                <tr key={p.id} className="transition hover:bg-slate-50">
                                                    <td className="px-6 py-4 font-semibold text-slate-900">
                                                        <div className="max-w-xl line-clamp-2">{p.name}</div>
                                                    </td>
                                                    <td className="px-6 py-4 text-sm text-slate-600">{p.funded_by || "-"}</td>
                                                    <td className="px-6 py-4 text-sm text-slate-600">{p.duration || "-"}</td>
                                                </tr>
                                            ))}
                                        </tbody>
                                    </table>
                                </div>

                                <div className="space-y-3 p-4 md:hidden">
                                    {items.map((p) => (
                                        <article key={p.id} className="rounded-xl border border-slate-200 bg-slate-50 p-4">
                                            <h3 className="text-sm font-bold text-slate-900">{p.name}</h3>
                                            <div className="mt-3 space-y-1.5 text-sm text-slate-600">
                                                <p><span className="font-semibold text-slate-800">Funded By:</span> {p.funded_by || "-"}</p>
                                                <p><span className="font-semibold text-slate-800">Duration:</span> {p.duration || "-"}</p>
                                            </div>
                                        </article>
                                    ))}
                                </div>
                            </div>
                        </>
                    )}

                    {error && <div className="mt-6 rounded-xl border border-red-200 bg-red-50 p-4 text-center text-sm text-red-700">{error}</div>}

                    <div className="mt-10 flex justify-center">
                        <div ref={sentinelRef} />
                        {isLoadingMore && (
                            <div className="inline-flex items-center gap-3 rounded-full border border-slate-200 bg-white px-4 py-2 text-sm text-slate-600 shadow-sm">
                                <span className="loading loading-spinner loading-sm" />
                                <span>Loading more projects...</span>
                            </div>
                        )}
                        {!hasMore && items.length > 0 && (
                            <div className="rounded-full bg-slate-200 px-4 py-2 text-sm font-semibold text-slate-600">No more projects.</div>
                        )}
                    </div>
                </div>
            </section>
        </Root>
    );
}
