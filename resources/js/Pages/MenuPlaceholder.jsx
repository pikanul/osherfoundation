import Root from '../component/layout/Root';
import Breadcrumb from '../component/Breadcrumb';
import { usePage } from '@inertiajs/react';

export default function MenuPlaceholder({ title = 'OSHE Foundation', summary = '', heroOnly = false }) {
    const { thematic_priority_settings = {} } = usePage().props;
    const heroImage = thematic_priority_settings?.hero_image || '';

    if (heroOnly) {
        return (
            <Root>
                <main className="thematic-priority-page">
                    {heroImage && (
                        <section className="thematic-priority-image-shell" aria-label={title}>
                            <img
                                src={heroImage}
                                alt={title}
                                className="thematic-priority-image"
                                draggable="false"
                            />
                        </section>
                    )}

                    <style>{`
                        .thematic-priority-page {
                            min-height: calc(100vh - 1px);
                            background: #ffffff;
                        }

                        .thematic-priority-image-shell {
                            display: flex;
                            align-items: flex-start;
                            justify-content: center;
                            width: 100%;
                            min-height: calc(100vh - 1px);
                            overflow-x: hidden;
                            overflow-y: auto;
                            background: #ffffff;
                        }

                        .thematic-priority-image {
                            display: block;
                            width: 100%;
                            height: auto;
                            max-width: none;
                            object-fit: contain;
                            object-position: top center;
                            user-select: none;
                        }

                        @media (min-aspect-ratio: 3/2) {
                            .thematic-priority-image {
                                width: 100%;
                                height: auto;
                                object-fit: contain;
                            }
                        }

                        @media (max-width: 900px) {
                            .thematic-priority-image-shell {
                                justify-content: flex-start;
                                overflow-x: auto;
                                overscroll-behavior-x: contain;
                            }

                            .thematic-priority-image {
                                width: auto;
                                max-width: none;
                                min-width: 1180px;
                                height: calc(100vh - 1px);
                                object-fit: contain;
                            }
                        }
                    `}</style>
                </main>
            </Root>
        );
    }

    return (
        <Root>
            {!heroOnly && <Breadcrumb title={title} subtitle="OSHE Foundation" summary={summary} />}
            <main className="bg-gradient-to-b from-white via-emerald-50/50 to-white px-4 py-12">
                {heroImage && (
                    <section className="mx-auto mb-8 max-w-6xl overflow-hidden rounded-md border border-emerald-100 bg-white shadow-sm">
                        <img
                            src={heroImage}
                            alt={`${title} hero image`}
                            className="h-auto w-full object-cover"
                        />
                    </section>
                )}

                {!heroOnly && (
                    <section className="mx-auto max-w-5xl rounded-md border border-emerald-100 bg-white p-8 text-center shadow-sm sm:p-12">
                        <p className="text-sm font-bold uppercase tracking-[0.22em] text-emerald-700">OSHE Foundation</p>
                        <h1 className="mt-4 text-3xl font-black text-slate-900 sm:text-4xl">{title}</h1>
                        <p className="mx-auto mt-4 max-w-2xl text-base leading-7 text-slate-600">
                            {summary || 'This section is ready in the website menu. The page content can be added from the admin panel or connected to a dedicated page later.'}
                        </p>
                    </section>
                )}
            </main>
        </Root>
    );
}
