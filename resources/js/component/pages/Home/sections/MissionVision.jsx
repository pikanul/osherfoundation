import React from "react";
import { usePage } from "@inertiajs/react";

export default function MissionVision() {
    const { props } = usePage();
    const { mission_title, vision_title, mission, vision } = props;

    return (
        <section className="relative overflow-hidden bg-slate-50 py-10 sm:py-14">
            <div className="pointer-events-none absolute inset-0 bg-[radial-gradient(circle_at_10%_20%,rgba(15,47,69,0.08),transparent_40%),radial-gradient(circle_at_85%_10%,rgba(234,179,8,0.12),transparent_35%)]" />

            <div className="relative mx-auto max-w-10/12">
                <div className="mb-8 text-center sm:mb-10">
                    <p className="inline-flex items-center rounded-full border border-[#0f2f45]/20 bg-white px-4 py-1 text-xs font-extrabold uppercase tracking-[0.15em] text-[#0f2f45] shadow-sm">
                        OSHE Foundation
                    </p>
                    <h2 className="mt-3 text-3xl font-black text-[#0f2f45] sm:text-4xl">
                        Mission and Vision
                    </h2>
                    {/* <p className="mx-auto mt-2 max-w-2xl text-sm text-slate-600 sm:text-base">
                        Our purpose and direction guide every initiative we take for safer, healthier workplaces.
                    </p> */}
                </div>

                <div className="grid grid-cols-1 gap-6 lg:grid-cols-2">
                    <article className="group rounded-2xl border border-[#0f2f45]/10 bg-white p-6 shadow-md transition duration-300 hover:-translate-y-1 hover:shadow-xl sm:p-8">
                        <div className="mb-4 inline-flex h-10 w-10 items-center justify-center rounded-full bg-[#0f2f45] text-sm font-black text-white">
                            M
                        </div>
                        <h3 className="text-2xl font-black text-[#0f2f45] sm:text-3xl">
                            {mission_title}
                        </h3>
                        <div className="mt-4 h-1 w-16 rounded-full bg-amber-400 transition-all duration-300 group-hover:w-24" />
                        <div className="prose prose-slate mt-5 max-w-none text-base leading-relaxed text-slate-700">
                            <div dangerouslySetInnerHTML={{ __html: mission }} />
                        </div>
                    </article>

                    <article className="group rounded-2xl border border-[#0f2f45]/10 bg-white p-6 shadow-md transition duration-300 hover:-translate-y-1 hover:shadow-xl sm:p-8">
                        <div className="mb-4 inline-flex h-10 w-10 items-center justify-center rounded-full bg-amber-400 text-sm font-black text-[#0f2f45]">
                            V
                        </div>
                        <h3 className="text-2xl font-black text-[#0f2f45] sm:text-3xl">
                            {vision_title}
                        </h3>
                        <div className="mt-4 h-1 w-16 rounded-full bg-[#0f2f45] transition-all duration-300 group-hover:w-24" />
                        <div className="prose prose-slate mt-5 max-w-none text-base leading-relaxed text-slate-700">
                            <div dangerouslySetInnerHTML={{ __html: vision }} />
                        </div>
                    </article>
                </div>
            </div>
        </section>
    );
}
