import Root from "../component/layout/Root";
import Breadcrumb from "../component/breadcrumb";
import { usePage } from "@inertiajs/react";

const jobRows = [
    { title: "Executive Assistant", deadline: "2026-05-20", experience: "5.00+ years" },
    { title: "Finance Manager", deadline: "2026-05-30", experience: "2.00+ years" },
    { title: "HR Officer", deadline: "2026-05-22", experience: "1.00+ years" },
    { title: "MIS", deadline: "2026-05-02", experience: "5.00+ years" },
    { title: "HR Officer", deadline: "2026-05-08", experience: "5.00+ years" },
];

const parseJobLinks = (linksText, fallbackLink) => {
    const parsed = String(linksText || "")
        .split(/\r?\n/)
        .map((line) => line.trim())
        .filter(Boolean)
        .map((line) => {
            const [labelPart, ...urlParts] = line.split("|");
            const url = (urlParts.join("|") || labelPart).trim();
            const label = urlParts.length ? labelPart.trim() : "Apply";

            return url ? { label: label || "Apply", url } : null;
        })
        .filter(Boolean);

    if (parsed.length) {
        return parsed;
    }

    return [{ label: "Apply", url: fallbackLink }];
};

export default function Career() {
    const defaultPublicJobsLink = "https://hrm.oshefoundation.com/jobs/openings";
    const {
        header_settings = {},
        img,
        career_public_jobs_status,
        career_public_jobs_link,
        career_public_jobs_links_text,
    } = usePage().props;
    const showPublicJobs = String(career_public_jobs_status ?? "1") !== "0";
    const publicJobsLink = career_public_jobs_link || defaultPublicJobsLink;
    const jobLinks = parseJobLinks(career_public_jobs_links_text, publicJobsLink);
    const hrmBaseUrl = "https://hrm.oshefoundation.com";
    const logo = header_settings.logo || img;

    return (
        <Root>
             
            <Breadcrumb title="OSHE Foundation Careers" subtitle="Current openings" summary="Publicly shared active jobs are listed below." />

            <section className="bg-[#f4f7fa] py-10">
                <div className="mx-auto max-w-10/12 px-4">
                    {showPublicJobs ? (
                        <div className="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-[0_18px_55px_rgba(15,47,69,0.12)]">
                            <div className="flex flex-col gap-6 border-b border-slate-200 bg-gradient-to-r from-slate-50 via-white to-emerald-50 px-6 py-8 sm:flex-row sm:items-center sm:px-10">
                                {logo ? (
                                    <img src={logo} alt="OSHE Foundation" className="h-20 w-auto object-contain sm:h-24" />
                                ) : null}
                                <div>
                                    <h2 className="text-4xl font-black leading-tight text-[#203244] sm:text-5xl">
                                        Active Job Openings
                                    </h2>
                                    <p className="mt-3 text-lg font-bold text-slate-500 sm:text-xl">
                                        Publicly shared active jobs are listed below. Create your profile first, then apply for the relevant role.
                                    </p>
                                </div>
                            </div>

                            <div className="p-6 sm:p-10">
                                <div className="rounded-2xl border border-dashed border-blue-200 bg-slate-50 px-5 py-5 text-base font-medium leading-8 text-slate-600 sm:text-lg">
                                    <p>
                                        Create your candidate profile first:{" "}
                                        <a href={`${hrmBaseUrl}/profile/create`} target="_blank" rel="noreferrer" className="text-blue-600 hover:text-blue-700">
                                            {hrmBaseUrl}/profile/create
                                        </a>
                                    </p>
                                    <p>
                                        Candidate login:{" "}
                                        <a href={`${hrmBaseUrl}/candidate/login`} target="_blank" rel="noreferrer" className="text-blue-600 hover:text-blue-700">
                                            {hrmBaseUrl}/candidate/login
                                        </a>
                                    </p>
                                </div>

                                <div className="mt-6 overflow-hidden rounded-2xl border border-slate-200">
                                    <div className="overflow-x-auto">
                                        <table className="w-full min-w-[760px] border-collapse text-left">
                                            <thead className="bg-slate-50 text-sm font-black uppercase text-slate-500">
                                                <tr>
                                                    <th className="px-5 py-5">Position Name</th>
                                                    <th className="px-5 py-5">Deadline</th>
                                                    <th className="px-5 py-5">Total Required Experience</th>
                                                    <th className="px-5 py-5">Apply Link</th>
                                                </tr>
                                            </thead>
                                            <tbody className="divide-y divide-slate-200 text-base font-semibold text-slate-700 sm:text-lg">
                                                {jobRows.map((job, index) => (
                                                    <tr key={`${job.title}-${job.deadline}-${index}`} className="bg-white">
                                                        <td className="px-5 py-5">
                                                            <a href={jobLinks[0]?.url || publicJobsLink} target="_blank" rel="noreferrer" className="font-black text-blue-600 hover:text-blue-700">
                                                                {job.title}
                                                            </a>
                                                        </td>
                                                        <td className="px-5 py-5">{job.deadline}</td>
                                                        <td className="px-5 py-5">{job.experience}</td>
                                                        <td className="px-5 py-5">
                                                            <div className="flex flex-wrap gap-2">
                                                                {jobLinks.map((link) => (
                                                                    <a
                                                                        key={`${job.title}-${link.url}`}
                                                                        href={link.url}
                                                                        target="_blank"
                                                                        rel="noreferrer"
                                                                        className="rounded-full bg-blue-50 px-3 py-1.5 text-sm font-black text-blue-600 transition hover:bg-blue-100 hover:text-blue-700"
                                                                    >
                                                                        {link.label}
                                                                    </a>
                                                                ))}
                                                            </div>
                                                        </td>
                                                    </tr>
                                                ))}
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div className="mt-5 flex flex-wrap items-center gap-2 text-sm font-semibold text-slate-500">
                                    <span>Sources:</span>
                                    {jobLinks.map((link) => (
                                        <a key={`source-${link.url}`} href={link.url} target="_blank" rel="noreferrer" className="rounded-full bg-slate-100 px-3 py-1 text-blue-600 hover:text-blue-700">
                                            {link.label}
                                        </a>
                                    ))}
                                </div>
                            </div>
                        </div>
                    ) : (
                        <div className="rounded-2xl border border-slate-200 bg-white p-8 text-center text-slate-600 shadow-md">
                            No public jobs are available right now.
                        </div>
                    )}
                </div>
            </section>
        </Root>
    );
}
