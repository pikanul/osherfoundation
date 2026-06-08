import Root from '../component/layout/Root'
import { useState } from 'react';
import { usePage } from "@inertiajs/react";
import Breadcrumb from '../component/breadcrumb';

export default function Contact() {
    const [status, setStatus] = useState("");
    const [message, setMessage] = useState("");
    const { props } = usePage();
    const { maps } = props;

  const handleSubmit = async (e) => {
    e.preventDefault();
    setStatus("sending");

    const formData = new FormData(e.target);

    try {
        const response = await fetch("/contact-store", {
            method: "POST",
            body: formData,
            headers: { Accept: "application/json" },
        });

        const data = await response.json();

        // 🔥 FIX HERE
        if (data.type === "success") {
            setStatus("success");
            setMessage(data.message || data.title || "✅ Success");
            e.target.reset();
        } else {
            setStatus("error");
            setMessage(data.message || data.title || "❌ Something went wrong");
        }

    } catch (error) {
        setStatus("error");
        setMessage("❌ Network error");
    }
};

    return (
        <Root>
            <Breadcrumb title="Contact OSHE Foundation" subtitle="We'd love to hear from you!" summary="Whether you have questions, feedback, or just want to say hello, feel free to reach out to us using the form below." />

            <div className="bg-gray-100 flex justify-center items-center py-4 ">
                <div className="w-full max-w-10/12 bg-white rounded-xl shadow-lg overflow-hidden grid md:grid-cols-2">

                    {/* 🗺 Map */}
                    <div className="w-full h-[400px] md:h-auto">
                        <iframe
                            title="Map"
                            src={maps}
                            className="w-full h-full border-0"
                            allowFullScreen
                            loading="lazy"
                        ></iframe>
                    </div>

                    {/* ✉ Form */}
                    <div className="bg-yellow-50 p-8 flex flex-col justify-center">
                        <h2 className="text-2xl font-semibold mb-6 text-gray-800">
                            Get in Touch
                        </h2>

                        <form onSubmit={handleSubmit} className="flex flex-col gap-4">
                            <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <input name="name" type="text" placeholder="Name" className="p-3 border rounded-md" required />
                                <input name="email" type="email" placeholder="Email" className="p-3 border rounded-md" required />
                            </div>

                            <input name="subject" type="text" placeholder="Subject" className="p-3 border rounded-md" required />

                            <textarea name="message" rows="4" placeholder="Message" className="p-3 border rounded-md" required />

                            <button
                                type="submit"
                                disabled={status === "sending"}
                                className="mt-2 bg-yellow-400 hover:bg-yellow-500 py-3 rounded-md disabled:opacity-60"
                            >
                                {status === "sending" ? "Sending..." : "SEND"} ✈
                            </button>
                        </form>

                        {/* ✅ Message */}
                        {status && (
                            <p className={`mt-4 font-medium ${
                                status === "success" ? "text-green-600" : "text-red-600"
                            }`}>
                                {message}
                            </p>
                        )}
                    </div>
                </div>
            </div>
        </Root>
    )
}
