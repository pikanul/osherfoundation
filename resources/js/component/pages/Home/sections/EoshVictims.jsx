import React from "react";
import { usePage } from "@inertiajs/react";

export default function EoshVictims() {
    const { props } = usePage();
    const { eosh_victims_title, eosh_victims_description, eosh_victims_image, eosh_victims_image_id } = props;
    const hasImage = Number(eosh_victims_image_id) > 0;

    return (
        <section className="relative overflow-hidden bg-slate-50 py-10 sm:py-14">
            <div className="pointer-events-none absolute inset-0 bg-[radial-gradient(circle_at_10%_20%,rgba(15,47,69,0.08),transparent_40%),radial-gradient(circle_at_85%_10%,rgba(251,191,36,0.12),transparent_35%)]" />

            <div className="relative mx-auto max-w-10/12">
                <div className="mb-6 text-center sm:mb-8">
                    <h2 className="text-3xl font-black uppercase tracking-wide text-[#0f2f45] sm:text-4xl">
                        {eosh_victims_title}
                    </h2>
                    <div className="mx-auto mt-4 h-1.5 w-24 rounded-full bg-amber-400" />
                </div>

                {hasImage && (
                    <div className="overflow-hidden rounded-2xl border border-slate-200 bg-white p-3 shadow-lg sm:p-4">
                        <img
                            className="h-[260px] w-full rounded-xl object-cover sm:h-[420px] lg:h-[620px]"
                            src={eosh_victims_image}
                            alt="EOSH victims"
                        />
                    </div>
                )}

                <div className={`${hasImage ? 'mt-6' : ''} rounded-2xl border border-[#0f2f45]/20 bg-gradient-to-br from-[#0f2f45] to-[#153c56] p-6 text-slate-100 shadow-xl sm:p-8`}>
                    <div className="prose prose-invert max-w-none text-base leading-relaxed sm:text-lg">
                        <div dangerouslySetInnerHTML={{ __html: eosh_victims_description }} />
                    </div>
                </div>
            </div>
        </section>
    );
}
