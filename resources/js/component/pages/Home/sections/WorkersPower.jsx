import React from "react";
import { usePage } from "@inertiajs/react";

export default function WorkersPower() {
    const { props } = usePage();
    const { workpower_title, workpower_description } = props;

    return (
        <section className="relative overflow-hidden py-10 sm:py-14">
            <div className="absolute inset-0 bg-[linear-gradient(135deg,#0f2f45_0%,#133b56_55%,#1f5f88_100%)]" />
            <div className="pointer-events-none absolute inset-0 bg-[radial-gradient(circle_at_15%_20%,rgba(251,191,36,0.22),transparent_35%),radial-gradient(circle_at_90%_15%,rgba(125,211,252,0.2),transparent_30%)]" />

            <div className="relative mx-auto max-w-10/12">
                <div className="rounded-2xl border border-white/20 bg-white/10 p-6 shadow-2xl backdrop-blur-sm sm:p-8">
                    <h2 className="text-center text-3xl font-black tracking-wide text-white sm:text-4xl lg:text-5xl">
                        {workpower_title}
                    </h2>

                    <div className="mx-auto mt-5 h-1.5 w-24 rounded-full bg-amber-300" />

                    <div className="prose prose-invert mx-auto mt-6 max-w-none text-base leading-relaxed text-slate-100 sm:text-lg">
                        <div dangerouslySetInnerHTML={{ __html: workpower_description }} />
                    </div>
                </div>
            </div>
        </section>
    );
}
