import Root from "../component/layout/Root";
import { useState } from "react";
import Breadcrumb from "../component/breadcrumb";

export default function Career() {
    const [status, setStatus] = useState("");
    const [message, setMessage] = useState("");
    const [errors, setErrors] = useState({});

    const handleSubmit = async (e) => {
        e.preventDefault();
        setStatus("sending");
        setMessage("");
        setErrors({});

        const formData = new FormData(e.target);

        try {
            const response = await fetch("/carrearStore", {
                method: "POST",
                body: formData,
                headers: {
                    Accept: "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')?.content || "",
                },
            });
            const data = await response.json();

            if (response.ok && data?.type === "success") {
                setStatus("success");
                setMessage(data?.title || "Successfully submitted.");
                e.target.reset();
                if (data?.url) window.location.href = data.url;
                return;
            }

            setStatus("error");
            setMessage(data?.message || data?.title || "Failed to submit.");
            setErrors(data?.errors || {});
        } catch (error) {
            setStatus("error");
            setMessage("Network error. Please try again.");
        }
    };

    const inputClass =
        "mt-1 w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-800 outline-none transition focus:border-amber-500 focus:ring-2 focus:ring-amber-200";

    return (
        <Root>
             
            <Breadcrumb  title=" OSHE Foundation Careers" subtitle="Build safer workplaces. Build a meaningful career." summary="We review every profile carefully. Submit your CV and let us know how you can contribute." />

            <section className="bg-[#f4f7fa] py-10">
                <div className="mx-auto grid max-w-10/12 gap-6  lg:grid-cols-12 ">
                    <aside className="lg:col-span-4">
                        <div className="sticky top-24 rounded-2xl bg-white p-6 shadow-md">
                            <h2 className="text-lg font-extrabold text-slate-900">Before You Apply</h2>
                            <div className="mt-5 space-y-4 text-sm text-slate-600">
                                <div>
                                    <p className="font-bold text-slate-800">1. Keep it clear</p>
                                    <p>Use a short subject and focused summary.</p>
                                </div>
                                <div>
                                    <p className="font-bold text-slate-800">2. Upload PDF CV</p>
                                    <p>Only PDF is accepted by the system.</p>
                                </div>
                                <div>
                                    <p className="font-bold text-slate-800">3. Stay reachable</p>
                                    <p>Provide active email and phone number.</p>
                                </div>
                            </div>
                        </div>
                    </aside>

                    <div className="lg:col-span-8">
                        <div className="rounded-2xl border border-slate-200 bg-white p-6 shadow-md sm:p-8">
                            <div className="mb-6 border-b border-slate-100 pb-4">
                                <h3 className="text-2xl font-black text-slate-900">Career Application Form</h3>
                                <p className="mt-1 text-sm text-slate-600">Complete all required fields and upload your CV.</p>
                            </div>

                            <form onSubmit={handleSubmit} className="grid grid-cols-1 gap-4 md:grid-cols-2">
                                <div>
                                    <label className="text-sm font-semibold text-slate-800">Full Name</label>
                                    <input name="name" type="text" className={inputClass} required />
                                    {errors?.name && <p className="mt-1 text-xs text-red-600">{errors.name[0]}</p>}
                                </div>

                                <div>
                                    <label className="text-sm font-semibold text-slate-800">Email Address</label>
                                    <input name="email" type="email" className={inputClass} required />
                                    {errors?.email && <p className="mt-1 text-xs text-red-600">{errors.email[0]}</p>}
                                </div>

                                <div>
                                    <label className="text-sm font-semibold text-slate-800">Phone</label>
                                    <input name="phone" type="text" className={inputClass} />
                                    {errors?.phone && <p className="mt-1 text-xs text-red-600">{errors.phone[0]}</p>}
                                </div>

                                <div>
                                    <label className="text-sm font-semibold text-slate-800">Subject</label>
                                    <input name="subject" type="text" className={inputClass} placeholder="Example: Program Officer Application" />
                                    {errors?.subject && <p className="mt-1 text-xs text-red-600">{errors.subject[0]}</p>}
                                </div>

                                <div className="md:col-span-2">
                                    <label className="text-sm font-semibold text-slate-800">Description</label>
                                    <textarea name="description" rows="6" className={inputClass} required />
                                    {errors?.description && <p className="mt-1 text-xs text-red-600">{errors.description[0]}</p>}
                                </div>

                                <div className="md:col-span-2">
                                    <label className="text-sm font-semibold text-slate-800">CV File (PDF only)</label>
                                    <input
                                        name="file_name"
                                        type="file"
                                        accept=".pdf,application/pdf"
                                        className="mt-1 w-full rounded-lg border border-dashed border-slate-400 bg-slate-50 px-3 py-2.5 text-sm file:mr-3 file:rounded-md file:border-0 file:bg-[#0f2f45] file:px-3 file:py-2 file:text-xs file:font-semibold file:text-white hover:file:bg-[#133b56]"
                                        required
                                    />
                                    {errors?.file_name && <p className="mt-1 text-xs text-red-600">{errors.file_name[0]}</p>}
                                </div>

                                <div className="md:col-span-2 pt-2">
                                    <button
                                        type="submit"
                                        disabled={status === "sending"}
                                        className="w-full rounded-lg bg-amber-400 px-4 py-3 text-sm font-bold text-slate-900 transition hover:bg-amber-300 disabled:cursor-not-allowed disabled:opacity-60"
                                    >
                                        {status === "sending" ? "Submitting..." : "Submit Application"}
                                    </button>
                                </div>
                            </form>

                            {message && (
                                <p className={`mt-4 rounded-md px-3 py-2 text-sm ${status === "success" ? "bg-green-50 text-green-700" : "bg-red-50 text-red-600"}`}>
                                    {message}
                                </p>
                            )}
                        </div>
                    </div>
                </div>
            </section>
        </Root>
    );
}
