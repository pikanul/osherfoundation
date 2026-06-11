import { useState } from 'react';
import Root from '../component/layout/Root';
import Breadcrumb from '../component/Breadcrumb';

const organizationTypes = [
    'Development Partner / Donor',
    'Government Institution',
    'NGO / INGO',
    "Trade Union / Workers' Organization",
    'Employer / Business Association',
    'Private Company / CSR Partner',
    'Academic / Research Institution',
    'Civil Society Organization',
    'Community-Based Organization',
    'Media Organization',
    'Other',
];

const partnershipInterests = [
    'Occupational Safety and Health',
    'Labour Rights and Decent Work',
    'Trade Union Strengthening',
    'Social Protection and Livelihoods',
    'Gender Equality and GBV Prevention',
    'Environmental Justice and Just Transition',
    'Climate Justice and Community Resilience',
    'Corporate Social Responsibility and Green Jobs',
    'Migrant Labour and Workplace Inclusion',
    'Research and Knowledge Generation',
    'Training and Capacity Building',
    'Policy Advocacy and Legal Reform',
    'Emergency Response and Worker Support',
    'Other',
];

const collaborationTypes = [
    'Joint Project Implementation',
    'Funding / Grant Support',
    'Technical Partnership',
    'Research Collaboration',
    'Training Collaboration',
    'Advocacy and Campaign Partnership',
    'Event / Seminar / Dialogue Partnership',
    'CSR Collaboration',
    'Network / Coalition Building',
    'Other',
];

const timelines = ['Immediate', 'Within 3 months', 'Within 6 months', 'Within 1 year', 'Flexible / To be discussed'];

const FieldError = ({ errors, name }) => {
    const error = errors?.[name]?.[0];
    return error ? <p className="mt-1 text-sm font-semibold text-red-600">{error}</p> : null;
};

const csrfToken = () => {
    const cookie = document.cookie
        .split('; ')
        .find((row) => row.startsWith('XSRF-TOKEN='));

    return cookie ? decodeURIComponent(cookie.split('=')[1]) : '';
};

const TextInput = ({ label, name, required = false, type = 'text', errors, ...props }) => (
    <label className="block">
        <span className="text-sm font-extrabold text-slate-800">{label}{required && <span className="text-red-600"> *</span>}</span>
        <input
            name={name}
            type={type}
            required={required}
            className="mt-2 h-12 w-full rounded-md border border-slate-300 bg-white px-3 text-slate-800 outline-none transition focus:border-emerald-700 focus:ring-4 focus:ring-emerald-100"
            {...props}
        />
        <FieldError errors={errors} name={name} />
    </label>
);

const Textarea = ({ label, name, required = false, errors, rows = 4, ...props }) => (
    <label className="block">
        <span className="text-sm font-extrabold text-slate-800">{label}{required && <span className="text-red-600"> *</span>}</span>
        <textarea
            name={name}
            required={required}
            rows={rows}
            className="mt-2 w-full rounded-md border border-slate-300 bg-white px-3 py-3 text-slate-800 outline-none transition focus:border-emerald-700 focus:ring-4 focus:ring-emerald-100"
            {...props}
        />
        <FieldError errors={errors} name={name} />
    </label>
);

const Select = ({ label, name, options, required = false, errors }) => (
    <label className="block">
        <span className="text-sm font-extrabold text-slate-800">{label}{required && <span className="text-red-600"> *</span>}</span>
        <select
            name={name}
            required={required}
            className="mt-2 h-12 w-full rounded-md border border-slate-300 bg-white px-3 text-slate-800 outline-none transition focus:border-emerald-700 focus:ring-4 focus:ring-emerald-100"
        >
            <option value="">Select one</option>
            {options.map((option) => <option key={option} value={option}>{option}</option>)}
        </select>
        <FieldError errors={errors} name={name} />
    </label>
);

const CheckboxGroup = ({ title, name, options, errors }) => (
    <div>
        <p className="text-sm font-extrabold text-slate-800">{title}<span className="text-red-600"> *</span></p>
        <div className="mt-3 grid gap-2 sm:grid-cols-2">
            {options.map((option) => (
                <label key={option} className="flex items-start gap-2 rounded-md border border-slate-200 bg-white p-3 text-sm font-semibold text-slate-700">
                    <input name={`${name}[]`} value={option} type="checkbox" className="mt-1 h-4 w-4 rounded border-slate-300 text-emerald-700 focus:ring-emerald-600" />
                    <span>{option}</span>
                </label>
            ))}
        </div>
        <FieldError errors={errors} name={name} />
    </div>
);

export default function PartnerWithUs() {
    const [status, setStatus] = useState('idle');
    const [message, setMessage] = useState('');
    const [errors, setErrors] = useState({});

    const handleSubmit = async (event) => {
        event.preventDefault();
        setStatus('sending');
        setMessage('');
        setErrors({});

        try {
            const response = await fetch('/partner-with-us/store', {
                method: 'POST',
                body: new FormData(event.currentTarget),
                headers: {
                    Accept: 'application/json',
                    'X-XSRF-TOKEN': csrfToken(),
                },
            });
            const data = await response.json();

            if (response.ok && data.type === 'success') {
                setStatus('success');
                setMessage(data.title);
                event.currentTarget.reset();
                return;
            }

            setStatus('error');
            setErrors(data.errors || {});
            setMessage(data.message || data.title || 'Please review the form and try again.');
        } catch (error) {
            setStatus('error');
            setMessage('Network error. Please try again.');
        }
    };

    return (
        <Root>
            <Breadcrumb
                title="Partner With Us"
                subtitle="OSHE Foundation"
                summary="Collaborate with OSHE Foundation to promote worker safety, rights, social protection and sustainable development."
            />

            <main className="bg-slate-50">
                <section className="mx-auto max-w-6xl px-4 py-14">
                    <div className="rounded-md border border-emerald-100 bg-white p-6 shadow-sm sm:p-8">
                        <p className="text-sm font-black uppercase tracking-[0.2em] text-emerald-700">Partner With Us</p>
                        <h1 className="mt-3 text-3xl font-black text-slate-950 sm:text-4xl">Partner With Us</h1>
                        <p className="mt-5 max-w-5xl text-base leading-8 text-slate-600">
                            OSHE Foundation welcomes collaboration with organizations, institutions, networks,
                            development partners, private sector actors, civil society organizations, academic
                            institutions, and community-based groups committed to promoting workers' rights,
                            occupational safety and health, decent work, social protection, environmental justice,
                            and sustainable development.
                        </p>
                        <p className="mt-4 rounded-md bg-emerald-50 px-4 py-3 text-sm font-bold text-emerald-900">
                            Please complete the form below. Our team will review your information and contact you for further discussion.
                        </p>
                    </div>

                    <form onSubmit={handleSubmit} className="mt-8 space-y-6 rounded-md border border-slate-200 bg-white p-5 shadow-sm sm:p-8">
                        <section>
                            <h2 className="text-xl font-black text-slate-950">Partner Information</h2>
                            <div className="mt-5 grid gap-5 md:grid-cols-2">
                                <TextInput label="Organization / Institution Name" name="organization_name" required errors={errors} />
                                <Select label="Type of Organization" name="organization_type" options={organizationTypes} required errors={errors} />
                                <TextInput label="Country" name="country" required errors={errors} />
                                <TextInput label="Website / Social Media Link" name="website_url" type="url" errors={errors} />
                                <div className="md:col-span-2">
                                    <Textarea label="Address" name="address" errors={errors} rows={3} />
                                </div>
                            </div>
                        </section>

                        <section className="border-t border-slate-200 pt-6">
                            <h2 className="text-xl font-black text-slate-950">Contact Person</h2>
                            <div className="mt-5 grid gap-5 md:grid-cols-2">
                                <TextInput label="Full Name" name="contact_name" required errors={errors} />
                                <TextInput label="Designation" name="designation" required errors={errors} />
                                <TextInput label="Email Address" name="email" type="email" required errors={errors} />
                                <TextInput label="Phone / WhatsApp Number" name="phone" required errors={errors} />
                            </div>
                        </section>

                        <section className="border-t border-slate-200 pt-6">
                            <CheckboxGroup title="Area of Partnership Interest" name="partnership_interests" options={partnershipInterests} errors={errors} />
                        </section>

                        <section className="border-t border-slate-200 pt-6">
                            <h2 className="text-xl font-black text-slate-950">Partnership Details</h2>
                            <div className="mt-5 space-y-5">
                                <Textarea label="Briefly describe your proposed partnership idea or area of collaboration" name="partnership_idea" required errors={errors} rows={5} />
                                <CheckboxGroup title="What type of support or collaboration are you interested in?" name="collaboration_types" options={collaborationTypes} errors={errors} />
                                <div className="grid gap-5 md:grid-cols-3">
                                    <TextInput label="Target sector or worker group, if any" name="target_sector" errors={errors} />
                                    <TextInput label="Preferred geographic area of collaboration" name="geographic_area" errors={errors} />
                                    <Select label="Expected timeline" name="expected_timeline" options={timelines} errors={errors} />
                                </div>
                            </div>
                        </section>

                        <section className="border-t border-slate-200 pt-6">
                            <h2 className="text-xl font-black text-slate-950">Document Upload</h2>
                            <label className="mt-5 block">
                                <span className="text-sm font-extrabold text-slate-800">Upload concept note, proposal, organization profile, or relevant document</span>
                                <input
                                    name="document"
                                    type="file"
                                    accept=".pdf,.doc,.docx,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document"
                                    className="mt-2 w-full rounded-md border border-dashed border-slate-300 bg-slate-50 px-3 py-3 text-sm text-slate-700 file:mr-4 file:rounded-md file:border-0 file:bg-emerald-700 file:px-4 file:py-2 file:font-bold file:text-white"
                                />
                                <span className="mt-2 block text-xs font-semibold text-slate-500">Accepted: PDF, DOC, DOCX. Maximum size: 10 MB.</span>
                                <FieldError errors={errors} name="document" />
                            </label>
                        </section>

                        <section className="border-t border-slate-200 pt-6">
                            <h2 className="text-xl font-black text-slate-950">Consent</h2>
                            <div className="mt-4 space-y-3">
                                <label className="flex gap-3 rounded-md border border-slate-200 bg-slate-50 p-4 text-sm font-semibold text-slate-700">
                                    <input name="accuracy_consent" value="1" type="checkbox" required className="mt-1 h-4 w-4 rounded border-slate-300 text-emerald-700 focus:ring-emerald-600" />
                                    <span>I confirm that the information provided is accurate and that OSHE Foundation may contact me regarding this partnership inquiry.</span>
                                </label>
                                <FieldError errors={errors} name="accuracy_consent" />
                                <label className="flex gap-3 rounded-md border border-slate-200 bg-slate-50 p-4 text-sm font-semibold text-slate-700">
                                    <input name="processing_consent" value="1" type="checkbox" required className="mt-1 h-4 w-4 rounded border-slate-300 text-emerald-700 focus:ring-emerald-600" />
                                    <span>I agree that OSHE Foundation may store and process this information for partnership communication and follow-up purposes.</span>
                                </label>
                                <FieldError errors={errors} name="processing_consent" />
                            </div>
                        </section>

                        {message && (
                            <div className={`rounded-md px-4 py-3 text-sm font-bold ${status === 'success' ? 'bg-emerald-50 text-emerald-800' : 'bg-red-50 text-red-700'}`}>
                                {message}
                            </div>
                        )}

                        <button
                            type="submit"
                            disabled={status === 'sending'}
                            className="inline-flex h-12 items-center rounded-md bg-emerald-700 px-7 text-sm font-extrabold text-white shadow-sm transition hover:bg-emerald-800 disabled:cursor-not-allowed disabled:opacity-60"
                        >
                            {status === 'sending' ? 'Submitting...' : 'Submit Partnership Inquiry'}
                        </button>
                    </form>
                </section>
            </main>
        </Root>
    );
}
