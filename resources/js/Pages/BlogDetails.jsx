import React from 'react';
import { Link, usePage } from '@inertiajs/react';
import Root from '../component/layout/Root';
import Breadcrumb from '../component/breadcrumb';

const formatPublishDate = (value) => {
    if (!value) return '';
    try {
        return new Date(value).toLocaleDateString([], {
            year: 'numeric',
            month: 'short',
            day: 'numeric',
        });
    } catch (e) {
        return value;
    }
};

export default function BlogDetails() {
    const { props } = usePage();
    const blog = props.blog || null;
    const relatedBlogs = Array.isArray(props.related_blogs) ? props.related_blogs : [];
    const appUrl = (props.app_url || '').replace(/\/+$/, '');
    const withAppUrl = (path) => (appUrl ? `${appUrl}${path}` : path);

    if (!blog) {
        return (
            <Root>
                <section className="bg-slate-50 py-10">
                    <div className="mx-auto max-w-10/12 rounded-xl border border-slate-200 bg-white p-6 text-center text-slate-600">
                        Blog not found.
                    </div>
                </section>
            </Root>
        );
    }

    return (
        <Root>
            <Breadcrumb title="Blog Details" subtitle={blog.title} summary="" />

            <section className="bg-slate-50 py-8 sm:py-10">
                <div className="mx-auto w-full max-w-[1200px] px-4 sm:px-0">
                    <article className="overflow-hidden border border-slate-200 bg-white shadow-sm">
                        <img
                            src={blog.image_url}
                            alt={blog.title}
                            className="max-h-[520px] w-full object-cover"
                        />

                        <div className="p-5 sm:p-6">
                            <div className="mb-3 flex flex-wrap items-center justify-between gap-2">
                                <span className="rounded-full bg-[#0f2f45]/10 px-3 py-1 text-xs font-bold uppercase tracking-wide text-[#0f2f45]">
                                    Blog Post
                                </span>
                                {blog.publish_date ? (
                                    <span className="text-xs font-semibold text-slate-500">
                                        Published: {formatPublishDate(blog.publish_date)}
                                    </span>
                                ) : null}
                            </div>

                            <h1 className="text-2xl font-black leading-tight text-slate-900 sm:text-3xl">{blog.title}</h1>

                            {blog.short_description ? (
                                <p className="mt-3 text-sm leading-relaxed text-slate-600 sm:text-base">
                                    {blog.short_description}
                                </p>
                            ) : null}

                            {blog.attachment_url ? (
                                <a
                                    href={blog.attachment_url}
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    className="mt-5 inline-block rounded-md bg-[#0f2f45] px-4 py-2 text-sm font-semibold text-white transition hover:bg-[#133b56]"
                                >
                                    Open Attachment
                                </a>
                            ) : null}

                            <div className="mt-5 border-t border-slate-200 pt-4">
                                {blog.description ? (
                                    <div
                                        className="prose rich-content max-w-none text-slate-700"
                                        dangerouslySetInnerHTML={{ __html: blog.description }}
                                    />
                                ) : (
                                    <p className="text-slate-600">No details available for this blog.</p>
                                )}
                            </div>
                        </div>
                    </article>

                    {relatedBlogs.length > 0 ? (
                        <div className="mt-8 border border-slate-200 bg-white p-4 shadow-sm sm:p-5">
                            <div className="mb-4 flex items-center justify-between gap-3">
                                <h2 className="text-xl font-black text-slate-900">Related Blogs</h2>
                                <Link href={withAppUrl('/blog')} className="text-sm font-semibold text-[#0f2f45] hover:underline">
                                    Back to Blog
                                </Link>
                            </div>

                            <div className="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                                {relatedBlogs.map((item) => (
                                    <Link
                                        key={item.id}
                                        href={withAppUrl(`/blog/${item.slug || item.id}`)}
                                        className="group block overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-xl"
                                    >
                                        <img
                                            src={item.image_url}
                                            alt={item.title}
                                            className="h-44 w-full object-cover transition duration-300 group-hover:scale-[1.03]"
                                            loading="lazy"
                                        />
                                        <div className="p-4">
                                            <h3 className="line-clamp-2 text-sm font-bold text-slate-900">{item.title}</h3>
                                            {item.publish_date ? (
                                                <p className="mt-2 text-xs font-semibold text-slate-500">
                                                    {formatPublishDate(item.publish_date)}
                                                </p>
                                            ) : null}
                                        </div>
                                    </Link>
                                ))}
                            </div>
                        </div>
                    ) : null}
                </div>
            </section>
        </Root>
    );
}
