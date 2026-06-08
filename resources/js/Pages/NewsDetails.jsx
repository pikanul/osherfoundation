import React, { useEffect, useMemo, useRef } from "react";
import { Link, usePage } from "@inertiajs/react";
import { FaFacebookF, FaLinkedinIn, FaWhatsapp } from "react-icons/fa";
import { FaXTwitter } from "react-icons/fa6";
import Root from "../component/layout/Root";
import Breadcrumb from "../component/breadcrumb";
import FlipPdfViewer from "../component/FlipPdfViewer";

const isImageUrl = (url) => {
    if (!url || typeof url !== "string") return false;
    return /\.(jpg|jpeg|png|webp|gif|avif|svg)(\?.*)?$/i.test(url);
};

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

export default function NewsDetails() {
    const { props } = usePage();
    const { newsCategory, lastestnews, show_news, share_links, search_keyword } = props;
    const appUrl = (props.app_url || "").replace(/\/+$/, "");
    const withAppUrl = (path) => (appUrl ? `${appUrl}${path}` : path);

    const activeNewsId = show_news?.id ?? null;
    const detailsTopRef = useRef(null);

    const latestItems = useMemo(() => (Array.isArray(lastestnews) ? lastestnews : []), [lastestnews]);
    const relatedItems = useMemo(
        () => latestItems.filter((item) => String(item?.id) !== String(activeNewsId)).slice(0, 4),
        [latestItems, activeNewsId]
    );

    const coverImageUrl = useMemo(() => {
        const image = show_news?.news_image_url || null;
        return image && isImageUrl(image) ? image : null;
    }, [show_news]);
    const pdfFileUrl = show_news?.pdf_file_url || null;
    const showPdfAfterCover = !!pdfFileUrl;

    const shareLinks = useMemo(() => share_links || {}, [share_links]);

    useEffect(() => {
        if (!detailsTopRef.current) return;
        detailsTopRef.current.scrollIntoView({ behavior: "smooth", block: "start" });
    }, [activeNewsId]);

    return (
        <Root>
            <Breadcrumb title="News and Updates" subtitle={newsCategory.name} summary="Read the selected newsletter and related updates." />

            <section className="bg-slate-50 py-8 sm:py-10">
                <div className="mx-auto w-full max-w-[1200px] px-4 sm:px-0">
                    {search_keyword ? (
                        <div className="mb-4 rounded-xl border border-[#25004f]/15 bg-white px-4 py-3 text-sm">
                            <p className="font-semibold text-slate-700">
                                News search result for: <span className="text-[#25004f]">{search_keyword}</span>
                            </p>
                        </div>
                    ) : null}

                    <div ref={detailsTopRef}>
                        <article className="border border-slate-200 bg-white p-4 shadow-sm sm:p-5">
                             <h1 className="text-2xl font-black leading-tight text-slate-900 sm:text-3xl">{show_news?.title}</h1>
                            {coverImageUrl ? (
                                <div className="mt-4 bg-slate-100">
                                    <img
                                        src={coverImageUrl}
                                        alt={show_news?.title || "News cover image"}
                                        className="max-h-[520px] w-full rounded-xl object-contain"
                                    />
                                </div>
                            ) : null}
                            {showPdfAfterCover ? (
                                <div className="mt-4 rounded-xl bg-white">
                                    <FlipPdfViewer
                                        src={pdfFileUrl}
                                        className="overflow-hidden rounded-lg border border-slate-200"
                                        style={{ minHeight: 560 }}
                                        options={{
                                            backgroundColor: "#ffffff",
                                            autoEnableOutline: false,
                                            autoEnableThumbnail: false,
                                            pageMode: "double",
                                            soundEnable: false,
                                        }}
                                    />
                                    <div className="mt-2 text-right">
                                        <a
                                            href={pdfFileUrl}
                                            target="_blank"
                                            rel="noreferrer"
                                            className="inline-flex items-center rounded-full bg-[#0f2f45] px-4 py-2 text-xs font-bold uppercase tracking-wide text-white"
                                        >
                                            Open PDF in New Tab
                                        </a>
                                    </div>
                                </div>
                            ) : null}

                            <div className="px-1 pt-5 sm:px-2">
                                <div className="mb-3 flex flex-wrap items-center gap-2">
                                    <span className="rounded-full bg-[#0f2f45]/10 px-3 py-1 text-xs font-bold uppercase tracking-wide text-[#0f2f45]">
                                        {newsCategory.name}
                                    </span>
                                    {show_news?.publish_date ? (
                                        <span className="text-xs font-semibold text-slate-500">
                                            Published: {formatPublishDate(show_news.publish_date)}
                                        </span>
                                    ) : null}
                                </div>

                            </div>

                            <div className="px-1 pt-5 sm:px-2 sm:pt-6">


                                {/* {show_news?.short_descripiton ? (
                                    <p className="mt-3 text-sm leading-relaxed text-slate-600 sm:text-base">{show_news.short_descripiton}</p>
                                ) : null} */}


                                <div className="mt-5 border-t border-slate-200 pt-4">
                                    {show_news?.long_description ? (
                                        <div className="prose rich-content max-w-none text-slate-700" dangerouslySetInnerHTML={{ __html: show_news.long_description }} />
                                    ) : (
                                        <p className="text-slate-600">No details available for this news.</p>
                                    )}
                                </div>

                                <div className="mt-4 border-t border-slate-200 pt-3">
                                    <div className="flex flex-wrap items-center justify-between gap-2">
                                        <h2 className="text-sm font-black uppercase tracking-wide text-slate-900">Share This News</h2>
                                        <div className="flex flex-wrap justify-end gap-2">
                                            {shareLinks.facebook && (
                                                <a href={shareLinks.facebook} target="_blank" rel="noreferrer" className="inline-flex items-center gap-2 rounded-full bg-[#1877F2] px-4 py-2 text-xs font-bold uppercase tracking-wide text-white">
                                                    <span className="inline-flex h-5 w-5 items-center justify-center rounded-full bg-white/20 text-[11px]"><FaFacebookF /></span>
                                                    Facebook
                                                </a>
                                            )}
                                            {shareLinks.twitter && (
                                                <a href={shareLinks.twitter} target="_blank" rel="noreferrer" className="inline-flex items-center gap-2 rounded-full bg-[#111827] px-4 py-2 text-xs font-bold uppercase tracking-wide text-white">
                                                    <span className="inline-flex h-5 w-5 items-center justify-center rounded-full bg-white/20 text-[11px]"><FaXTwitter /></span>
                                                    X
                                                </a>
                                            )}
                                            {shareLinks.linkedin && (
                                                <a href={shareLinks.linkedin} target="_blank" rel="noreferrer" className="inline-flex items-center gap-2 rounded-full bg-[#0A66C2] px-4 py-2 text-xs font-bold uppercase tracking-wide text-white">
                                                    <span className="inline-flex h-5 w-5 items-center justify-center rounded-full bg-white/20 text-[10px]"><FaLinkedinIn /></span>
                                                    LinkedIn
                                                </a>
                                            )}
                                            {shareLinks.whatsapp && (
                                                <a href={shareLinks.whatsapp} target="_blank" rel="noreferrer" className="inline-flex items-center gap-2 rounded-full bg-[#25D366] px-4 py-2 text-xs font-bold uppercase tracking-wide text-white">
                                                    <span className="inline-flex h-5 w-5 items-center justify-center rounded-full bg-white/20 text-[11px]"><FaWhatsapp /></span>
                                                    WhatsApp
                                                </a>
                                            )}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </article>
                    </div>

                    <div className="mt-10 overflow-hidden border border-[#0f2f45]/15 bg-gradient-to-br from-[#f9fcff] via-white to-[#eef6ff] p-5 shadow-[0_20px_55px_rgba(15,47,69,.1)] sm:p-6">
                        <div className="mb-5 flex flex-wrap items-end justify-between gap-3 border-b border-[#0f2f45]/10 pb-4">
                            <div>
                                <p className="text-[11px] font-extrabold uppercase tracking-[0.14em] text-[#0f2f45]/70">Curated List</p>
                                <h2 className="text-2xl font-black text-[#0f2f45]">Related News</h2>
                                <p className="text-sm text-slate-500">Stories connected to this topic from {newsCategory.name}.</p>
                            </div>
                            <span className="inline-flex items-center rounded-full bg-[#0f2f45] px-3 py-1 text-xs font-bold uppercase tracking-wide text-white">
                                {relatedItems.length} Items
                            </span>
                        </div>

                        {relatedItems.length > 0 ? (
                            <div className="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
                                {relatedItems.map((news, idx) => (
                                    <Link
                                        key={news.id}
                                        href={withAppUrl(`/news/${news.id}`)}
                                        className="group block overflow-hidden rounded-2xl border border-[#0f2f45]/12 bg-white p-3 shadow-sm transition duration-200 hover:-translate-y-1 hover:border-[#0f2f45]/35 hover:shadow-lg"
                                    >
                                        <div className="relative">
                                            <span className="absolute left-2 top-2 z-10 inline-flex h-7 min-w-7 items-center justify-center rounded-full bg-black/70 px-2 text-xs font-black text-white">
                                                {idx + 1}
                                            </span>

                                            <img
                                                src={news.news_image_url}
                                                alt={news.title}
                                                className="h-44 w-full rounded-xl object-cover transition duration-200 group-hover:scale-[1.02]"
                                                loading="lazy"
                                            />
                                            <div className="mt-3">
                                                <h3 className="line-clamp-2 text-sm font-extrabold leading-5 text-slate-900 group-hover:text-[#0f2f45]">{news.title}</h3>
                                                <p className="mt-1 text-xs font-semibold text-slate-500">{formatPublishDate(news.publish_date)}</p>
                                            </div>
                                        </div>
                                    </Link>
                                ))}
                            </div>
                        ) : (
                            <p className="text-sm text-slate-500">No related news found.</p>
                        )}
                    </div>
                </div>
            </section>
        </Root>
    );
}
