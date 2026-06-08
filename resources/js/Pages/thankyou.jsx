import Root from '../component/layout/Root'
import { Link, usePage } from "@inertiajs/react";
import Breadcrumb from '../component/breadcrumb';

export default function Contact() {
    const { app_url } = usePage().props;
    const appUrl = (app_url || "").replace(/\/+$/, "");
    const withAppUrl = (path) => (appUrl ? `${appUrl}${path}` : path);

    return (
        <Root>
            <Breadcrumb title="Thank You" subtitle="Submission received" summary="We have received your message and will respond soon." />
            <div className="py-30 flex items-center justify-center bg-gray-100 px-4">

                <div className="bg-white shadow-lg rounded-xl p-8 max-w-md w-full text-center">

                    <h2 className="text-3xl font-bold text-green-600 mb-3">
                        ✅ Thank You!
                    </h2>

                    <p className="text-gray-600 mb-6">
                        Thank you for your message. We will get back to you soon.
                    </p>

                    <Link
                        href={withAppUrl('/')}
                        className="inline-block bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md transition"
                    >
                        ⬅ Back To Home
                    </Link>

                </div>

            </div>
        </Root>
    )
}

