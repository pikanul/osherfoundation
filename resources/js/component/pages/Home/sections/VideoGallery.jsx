import React, { useEffect, useMemo, useState } from "react";
import { Link } from "@inertiajs/react";
import { PlayCircleIcon } from "@heroicons/react/24/solid";

function getYouTubeThumb(videoId) {
    if (!videoId) return "";
    return `https://img.youtube.com/vi/${videoId}/hqdefault.jpg`;
}

function getYouTubeEmbed(videoId) {
    if (!videoId) return "";
    return `https://www.youtube.com/embed/${videoId}`;
}

function extractYouTubeId(value) {
    if (!value) return null;
    const v = String(value).trim();
    if (/^[a-zA-Z0-9_-]{11}$/.test(v)) return v;

    try {
        const url = new URL(v);
        const host = url.hostname.toLowerCase();

        if (host.includes("youtu.be")) {
            const id = url.pathname.replace("/", "");
            return /^[a-zA-Z0-9_-]{11}$/.test(id) ? id : null;
        }

        if (host.includes("youtube.com") || host.includes("youtube-nocookie.com")) {
            const byQuery = url.searchParams.get("v");
            if (byQuery && /^[a-zA-Z0-9_-]{11}$/.test(byQuery)) return byQuery;

            const match = url.pathname.match(/\/(embed|shorts|live)\/([a-zA-Z0-9_-]{11})/);
            if (match) return match[2];
        }
    } catch (e) {
        return null;
    }

    return null;
}

export default function VideoGallery({ videos = [], ajaxUrl = null, seeMoreHref = null }) {
    const [remoteVideos, setRemoteVideos] = useState(null);
    const [isFetching, setIsFetching] = useState(Boolean(ajaxUrl));
    const [isPreloading, setIsPreloading] = useState(false);
    const [loadError, setLoadError] = useState(null);
    const [activeVideoId, setActiveVideoId] = useState(null);

    const effectiveVideos = useMemo(() => {
        if (ajaxUrl) return remoteVideos || [];
        return videos || [];
    }, [ajaxUrl, remoteVideos, videos]);

    const videoItems = useMemo(
        () =>
            (effectiveVideos || []).map((v, idx) => ({
                key: `${v.id || "video"}-${idx}`,
                id: v.id,
                videoId: v.video_id || extractYouTubeId(v.video_url || v.id),
                title: v.title || "Video",
                thumb: v.image_url || getYouTubeThumb(v.video_id || extractYouTubeId(v.video_url || v.id)),
                embed: v.embed_url || getYouTubeEmbed(v.video_id || extractYouTubeId(v.video_url || v.id)),
            })),
        [effectiveVideos]
    );

    useEffect(() => {
        if (!ajaxUrl) return;

        let isMounted = true;
        const controller = new AbortController();

        const load = async () => {
            setIsFetching(true);
            setLoadError(null);
            try {
                const r = await fetch(ajaxUrl, {
                    method: "GET",
                    headers: { Accept: "application/json" },
                    signal: controller.signal,
                });
                const data = await r.json().catch(() => ({}));
                if (!r.ok) throw new Error(data?.message || "Failed to load videos");

                if (!isMounted) return;
                setRemoteVideos(data?.items || data?.videos || []);
                setIsPreloading(true);
            } catch (e) {
                if (!isMounted) return;
                if (e?.name === "AbortError") return;
                setLoadError(e?.message || "Failed to load videos");
                setRemoteVideos([]);
                setIsPreloading(false);
            } finally {
                if (!isMounted) return;
                setIsFetching(false);
            }
        };

        load();

        return () => {
            isMounted = false;
            controller.abort();
        };
    }, [ajaxUrl]);

    useEffect(() => {
        let isMounted = true;

        if (!videoItems.length) {
            setIsPreloading(false);
            return () => {
                isMounted = false;
            };
        }

        setIsPreloading(true);
        const preload = async () => {
            try {
                await Promise.all(
                    videoItems.map(
                        (v) =>
                            new Promise((resolve) => {
                                const img = new Image();
                                img.onload = resolve;
                                img.onerror = resolve;
                                img.src = v.thumb;
                            })
                    )
                );
            } finally {
                if (!isMounted) return;
                setIsPreloading(false);
            }
        };

        preload();
        return () => {
            isMounted = false;
        };
    }, [videoItems]);

    const isLoading = isFetching || isPreloading;

    return (
        <section className="relative overflow-hidden bg-slate-50 py-10 sm:py-14">
            <div className="pointer-events-none absolute inset-0 bg-[radial-gradient(circle_at_10%_20%,rgba(15,47,69,0.08),transparent_40%),radial-gradient(circle_at_88%_10%,rgba(251,191,36,0.12),transparent_35%)]" />

            <div className="relative mx-auto max-w-10/12">
                <div className="mb-6 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                    <div className="flex flex-wrap items-center justify-between gap-3">
                        <div>
                            <h2 className="text-2xl font-black uppercase text-[#0f2f45] sm:text-3xl">Projects and Campaigns</h2>
                            <p className="mt-1 text-sm text-slate-600">Watch highlights, stories, and media updates from OSHE Foundation.</p>
                        </div>
                        <div className="rounded-full bg-[#0f2f45]/10 px-4 py-2 text-sm font-bold text-[#0f2f45]">
                            {videoItems.length} Video{videoItems.length === 1 ? "" : "s"}
                        </div>
                    </div>
                </div>

                {loadError ? <div className="mb-4 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">{loadError}</div> : null}

                <div className="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-3">
                    {isLoading &&
                        Array.from({ length: Math.max(6, videoItems.length || 6) }).map((_, idx) => (
                            <article key={idx} className="overflow-hidden rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                                <div className="skeleton h-52 w-full rounded-xl" />
                                <div className="mt-4 space-y-2">
                                    <div className="skeleton h-4 w-4/5" />
                                    <div className="skeleton h-4 w-2/3" />
                                </div>
                            </article>
                        ))}

                    {!isLoading &&
                        !videoItems.length && (
                            <div className="rounded-xl border border-slate-200 bg-white py-10 text-center text-slate-600 md:col-span-2 xl:col-span-3">No videos found.</div>
                        )}

                    {!isLoading &&
                        videoItems.map((v) => (
                            <button
                                key={v.key}
                                type="button"
                                onClick={() => setActiveVideoId(v.videoId)}
                                className="group overflow-hidden rounded-2xl border border-slate-200 bg-white text-left shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-xl"
                            >
                                <div className="relative">
                                    <img src={v.thumb} alt={v.title} className="h-52 w-full object-cover transition duration-300 group-hover:scale-[1.03]" loading="lazy" />
                                    <div className="absolute inset-0 bg-gradient-to-t from-black/45 to-transparent" />
                                    <div className="absolute inset-0 flex items-center justify-center">
                                        <span className="relative inline-flex h-16 w-16 items-center justify-center sm:h-18 sm:w-18">
                                            <span className="absolute inline-flex h-16 w-16 animate-ping rounded-full bg-white/35 sm:h-18 sm:w-18" />
                                            <span className="absolute inline-flex h-14 w-14 rounded-full bg-[#0f2f45]/35 blur-md sm:h-16 sm:w-16" />
                                            <span className="inline-flex h-16 w-16 items-center justify-center rounded-full bg-white/95 shadow-xl ring-4 ring-white/45 transition duration-300 group-hover:scale-110">
                                                <span className="inline-flex h-10 w-10 items-center justify-center rounded-full bg-[#0f2f45]/10">
                                                    <PlayCircleIcon className="h-7 w-7 text-[#0f2f45] sm:h-8 sm:w-8" />
                                                </span>
                                            </span>
                                        </span>
                                    </div>
                                </div>

                                <div className="p-4">
                                    <h3 className="line-clamp-2 text-base font-bold text-slate-900">{v.title}</h3>
                                    <span className="mt-3 inline-block text-xs font-bold uppercase tracking-wide text-[#0f2f45]">Watch Video</span>
                                </div>
                            </button>
                        ))}
                </div>

                <div className="pb-5 pt-8 text-center">
                    {seeMoreHref ? (
                        <Link href={seeMoreHref} className="inline-flex items-center rounded-full bg-[#0f2f45] px-6 py-3 text-sm font-bold uppercase tracking-wide text-white transition hover:bg-[#153f5d]">
                            View All Videos
                        </Link>
                    ) : null}
                </div>

                {activeVideoId && (
                    <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/60 px-4 backdrop-blur-[2px]" role="dialog" aria-modal="true">
                        <div className="absolute inset-0" onClick={() => setActiveVideoId(null)} />
                        <div className="relative w-full max-w-5xl overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-2xl">
                            <div className="flex items-center justify-between border-b border-slate-200 p-3 sm:p-4">
                                <div className="font-semibold text-[#0f2f45]">YouTube Video</div>
                                <button
                                    type="button"
                                    onClick={() => setActiveVideoId(null)}
                                    className="rounded-md border border-slate-300 px-3 py-1.5 text-sm font-semibold text-slate-700 transition hover:bg-red-50 hover:text-red-600"
                                >
                                    Close
                                </button>
                            </div>
                            <div className="aspect-video w-full bg-black">
                                <iframe
                                    className="h-full w-full"
                                    src={getYouTubeEmbed(activeVideoId)}
                                    title="YouTube video player"
                                    frameBorder="0"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                    referrerPolicy="strict-origin-when-cross-origin"
                                    allowFullScreen
                                />
                            </div>
                        </div>
                    </div>
                )}
            </div>
        </section>
    );
}
