import Root from '../component/layout/Root';
import Breadcrumb from '../component/breadcrumb';
import React, { useMemo, useState } from "react";
import { usePage } from "@inertiajs/react";

export default function TeamOshe() {
    const [filter, setFilter] = useState("all"); // all | leader | general
    const [selected, setSelected] = useState(null);

    const { props } = usePage();
    const teamMembers = props?.teamMembers ?? [];

    const filteredMembers = useMemo(() => {
        if (filter === "all") return teamMembers;
        return teamMembers.filter((member) => member?.type === filter);
    }, [filter, teamMembers]);

    const filters = [
        { key: "all", label: "All Members" },
        { key: "leader", label: "Leadership" },
        { key: "general", label: "Team Members" },
    ];

    return (
        <Root>
            <Breadcrumb title="Team OSHE" subtitle="People behind the mission" summary="Get to know the team members of OSHE Foundation." />

            <section className="relative overflow-hidden bg-slate-50 py-8 sm:py-10">
                <div className="pointer-events-none absolute inset-0 bg-[radial-gradient(circle_at_15%_20%,rgba(15,47,69,0.08),transparent_35%),radial-gradient(circle_at_85%_10%,rgba(250,204,21,0.12),transparent_30%)]" />

                <div className="relative mx-auto max-w-10/12">
                    <div className="mb-6 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                        <div className="flex flex-wrap items-center justify-between gap-3">
                            <div>
                                <h2 className="text-xl font-black text-slate-900 sm:text-2xl">Meet Our Team</h2>
                                <p className="mt-1 text-sm text-slate-600">Dedicated professionals driving OSHE Foundation's mission forward.</p>
                            </div>
                            <div className="rounded-full bg-[#0f2f45]/10 px-4 py-2 text-sm font-bold text-[#0f2f45]">
                                {filteredMembers.length} Member{filteredMembers.length === 1 ? "" : "s"}
                            </div>
                        </div>

                        <div className="mt-5 flex flex-wrap gap-2">
                            {filters.map((item) => (
                                <button
                                    key={item.key}
                                    className={`rounded-full border px-4 py-2 text-sm font-semibold transition ${
                                        filter === item.key
                                            ? "border-[#0f2f45] bg-[#0f2f45] text-white shadow"
                                            : "border-slate-300 bg-white text-slate-700 hover:border-[#0f2f45]/40 hover:bg-slate-50"
                                    }`}
                                    onClick={() => setFilter(item.key)}
                                    type="button"
                                >
                                    {item.label}
                                </button>
                            ))}
                        </div>
                    </div>

                    <div className="mx-auto grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                        {filteredMembers.map((member) => (
                            <article
                                key={member.id}
                                onClick={() => setSelected(member)}
                                className="group cursor-pointer overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-xl"
                            >
                                <div className="relative">
                                    <img
                                        src={member.image_url}
                                        alt={member.name}
                                        className="h-64 w-full object-cover transition duration-300 group-hover:scale-[1.02]"
                                    />
                                    <div className="absolute inset-x-0 bottom-0 h-20 bg-gradient-to-t from-black/40 to-transparent" />
                                </div>
                                <div className="p-4">
                                    <h3 className="line-clamp-1 text-lg font-bold text-slate-900">{member.name}</h3>
                                    <p className="mt-1 line-clamp-1 text-sm font-medium text-slate-600">{member.designation}</p>
                                    <span className="mt-3 inline-block text-xs font-bold uppercase tracking-wide text-[#0f2f45]">
                                        View Profile
                                    </span>
                                </div>
                            </article>
                        ))}
                    </div>

                    {filteredMembers.length === 0 && (
                        <div className="mt-6 rounded-xl border border-slate-200 bg-white p-6 text-center text-slate-600">
                            No team member found for this filter.
                        </div>
                    )}
                </div>
            </section>

            {selected && (
                <div
                    className="fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4 backdrop-blur-[2px]"
                    onClick={() => setSelected(null)}
                    role="dialog"
                    aria-modal="true"
                >
                    <div
                        className="relative w-[94vw] max-w-[760px] rounded-2xl border border-slate-200 bg-white p-5 shadow-2xl sm:p-7"
                        onClick={(e) => e.stopPropagation()}
                    >
                        <button
                            className="absolute right-3 top-3 h-9 w-9 rounded-full border border-slate-300 text-xl text-slate-600 transition hover:bg-red-50 hover:text-red-600"
                            onClick={() => setSelected(null)}
                            type="button"
                            aria-label="Close"
                        >
                            x
                        </button>

                        <div className="grid grid-cols-1 gap-5 sm:grid-cols-[180px_1fr] sm:items-start">
                            <img
                                src={selected.image_url}
                                alt={selected.name}
                                className="h-40 w-40 rounded-2xl border-4 border-slate-100 object-cover shadow sm:h-44 sm:w-44"
                            />

                            <div className="text-left">
                                <h2 className="text-xl font-black text-slate-900 sm:text-2xl">{selected.name}</h2>
                                <p className="mt-1 text-sm font-semibold text-slate-600">{selected.designation}</p>

                                <div className="mt-4 space-y-1 text-sm text-slate-700">
                                    {!!selected.email && <p><span className="font-semibold text-slate-900">Email:</span> {selected.email}</p>}
                                    {!!selected.phone && <p><span className="font-semibold text-slate-900">Phone:</span> {selected.phone}</p>}
                                </div>
                            </div>
                        </div>

                                {!!selected.short_des && (
                                    <div className="prose rich-content mt-4 max-w-none text-sm leading-relaxed text-slate-700 sm:text-base" dangerouslySetInnerHTML={{ __html: selected.short_des }} ></div>
                                )}
                    </div>
                </div>
            )}
        </Root>
    );
}
