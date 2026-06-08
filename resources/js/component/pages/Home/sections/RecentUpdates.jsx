import React, { useEffect, useState } from "react";
import axios from "axios";
import { Link } from "@inertiajs/react";

export default function RecentUpdates({ perPage = 6 }) {
    const [recentPosts, setRecentPosts] = useState([]);
    const [recentLoading, setRecentLoading] = useState(true);
    const [recentError, setRecentError] = useState("");

    useEffect(() => {
        let isMounted = true;
        setRecentLoading(true);
        setRecentError("");

        axios
            .get("/ajax/blog", { params: { per_page: perPage } })
            .then((res) => {
                if (!isMounted) return;
                const items = Array.isArray(res?.data?.items) ? res.data.items : [];
                setRecentPosts(items);
            })
            .catch((e) => {
                if (!isMounted) return;
                setRecentError(e?.response?.data?.message || "Failed to load recent posts.");
            })
            .finally(() => {
                if (!isMounted) return;
                setRecentLoading(false);
            });

        return () => {
            isMounted = false;
        };
    }, [perPage]);

    return (
        <section className="relative overflow-hidden bg-slate-50 py-10 sm:py-14">
            <div className="pointer-events-none absolute inset-0 bg-[radial-gradient(circle_at_10%_20%,rgba(15,47,69,0.08),transparent_40%),radial-gradient(circle_at_88%_10%,rgba(251,191,36,0.12),transparent_35%)]" />

            <div className="relative mx-auto max-w-10/12">
                <div className="mb-6 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                    <div className="flex flex-wrap items-center justify-between gap-3">
                        <div>
                            <h2 className="text-2xl font-black uppercase text-[#0f2f45] sm:text-3xl">
                                Recent Updates and Activities
                            </h2>
                            <p className="mt-1 text-sm text-slate-600">Latest stories from OSHE Foundation.</p>
                        </div>
                        <div className="rounded-full bg-[#0f2f45]/10 px-4 py-2 text-sm font-bold text-[#0f2f45]">
                           <Link href="/blog"> View All</Link>
                        </div>
                    </div>
                </div>

                {recentError && (
                    <div className="mb-4 rounded-xl border border-red-200 bg-red-50 p-4 text-center text-sm text-red-700">
                        {recentError}
                    </div>
                )}

                <div className="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-3">
                    {recentLoading &&
                        Array.from({ length: perPage }).map((_, idx) => (
                            <article key={idx} className="overflow-hidden rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                                <div className="skeleton h-44 w-full rounded-xl" />
                                <div className="mt-4 space-y-2">
                                    <div className="skeleton h-4 w-4/5" />
                                    <div className="skeleton h-4 w-3/5" />
                                </div>
                                <div className="mt-5">
                                    <div className="skeleton h-10 w-32 rounded-full" />
                                </div>
                            </article>
                        ))}

                    {!recentLoading &&
                        (recentPosts?.length ? recentPosts : []).map((post) => (
                            <article key={post.id} className="group overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-xl">
                                <div className="relative">
                                    <img
                                        src={post.image_url}
                                        alt={post.title}
                                        className="h-52 w-full object-cover transition duration-300 group-hover:scale-[1.03]"
                                        loading="lazy"
                                    />
                                    <div className="absolute inset-0 bg-gradient-to-t from-black/35 to-transparent" />
                                    {post.publish_date && (
                                        <time className="absolute left-3 top-3 rounded-md bg-white/90 px-2 py-1 text-xs font-semibold text-slate-700 shadow">
                                            {new Date(post.publish_date).toLocaleDateString()}
                                        </time>
                                    )}
                                </div>

                                <div className="p-5">
                                    <h3 className="line-clamp-2 text-lg font-bold text-slate-900">{post.title}</h3>
                                    <div className="mt-4">
                                        <Link
                                            href={`/blog/${post.slug || post.id}`}
                                            className="inline-flex items-center rounded-full bg-[#0f2f45] px-4 py-2 text-xs font-bold uppercase tracking-wide text-white transition hover:bg-[#153f5d]"
                                        >
                                            View Details
                                        </Link>
                                    </div>
                                </div>
                            </article>
                        ))}

                    {!recentLoading && !recentError && (recentPosts?.length || 0) === 0 && (
                        <div className="rounded-xl border border-slate-200 bg-white p-6 text-center text-slate-600 md:col-span-2 xl:col-span-3">
                            No recent posts found.
                        </div>
                    )}
                </div>
            </div>
        </section>
    );
}
