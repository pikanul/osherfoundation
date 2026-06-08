import React, { useState } from 'react';
import { usePage } from "@inertiajs/react";
import bgimg from '../../assets/logo/footer bg.jpg';
import { ChevronRightIcon } from "@heroicons/react/24/solid";
import { FaFacebookF, FaInstagram, FaLinkedinIn, FaXTwitter, FaYoutube } from 'react-icons/fa6';
import { Link } from '@inertiajs/react';

const Footer = () => {
    const { props } = usePage();
    const {
        pages = [],
        social_links = {},
        app_url,
        app_about
    } = props;
    const [email, setEmail] = useState('');
    const [subscribeStatus, setSubscribeStatus] = useState('idle');
    const [subscribeMessage, setSubscribeMessage] = useState('');

    const appUrl = (app_url || "").replace(/\/+$/, "");
    const withAppUrl = (path) => (appUrl ? `${appUrl}${path}` : path);

    const handleSubscribe = async (e) => {
        e.preventDefault();

        const trimmedEmail = email.trim();
        if (!trimmedEmail) {
            setSubscribeStatus('error');
            setSubscribeMessage('Email is required.');
            return;
        }

        setSubscribeStatus('sending');
        setSubscribeMessage('');

        const formData = new FormData();
        formData.append('email', trimmedEmail);

        try {
            const response = await fetch('/subscribe', {
                method: 'POST',
                body: formData,
                headers: {
                    Accept: 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                },
            });

            const data = await response.json();

            if (response.ok && data.type === 'success') {
                setSubscribeStatus('success');
                setSubscribeMessage(data.title || 'Successfully subscribed.');
                setEmail('');
                return;
            }

            const firstValidationError = data?.errors ? Object.values(data.errors)[0]?.[0] : null;
            setSubscribeStatus('error');
            setSubscribeMessage(firstValidationError || data?.title || 'Subscription failed. Please try again.');
        } catch (error) {
            setSubscribeStatus('error');
            setSubscribeMessage('Network error. Please try again.');
        }
    };

    return (
        <div className="bg-cover bg-center" style={{ backgroundImage: `url(${bgimg})` }}>
            <div className="bg-blue-900/70">
                <div className="mx-auto max-w-10/12  py-8 sm:py-10">
                    <div className="grid grid-cols-1 gap-5 sm:gap-6 md:grid-cols-2 md:gap-8 lg:grid-cols-3">

                        <div className="rounded-xl border border-white/15 bg-white/5 p-4 text-left backdrop-blur-sm sm:p-5 ">
                            <h2 className="text-xl font-bold text-yellow-300 sm:text-2xl">Quick Links</h2>
                            <ul className="mt-4 space-y-2.5 text-sm font-semibold text-white sm:text-base">
                                <li>
                                    <Link href={withAppUrl('/OrganizationProfile')} className="flex items-center justify-start gap-2 rounded-md px-2 py-1.5 transition hover:bg-white/10 md:justify-start">
                                        <ChevronRightIcon className="h-4 w-4 shrink-0" />
                                        <span>About US</span>
                                    </Link>
                                </li>
                                <li>
                                    <Link href={withAppUrl('/media')} className="flex items-center justify-start gap-2 rounded-md px-2 py-1.5 transition hover:bg-white/10 md:justify-start">
                                        <ChevronRightIcon className="h-4 w-4 shrink-0" />
                                        <span>Media</span>
                                    </Link>
                                </li>
                                <li>
                                    <Link href={withAppUrl('/career')} className="flex items-center justify-start gap-2 rounded-md px-2 py-1.5 transition hover:bg-white/10 md:justify-start">
                                        <ChevronRightIcon className="h-4 w-4 shrink-0" />
                                        <span>Career</span>
                                    </Link>
                                </li>
                                <li>
                                    <Link href={withAppUrl('/events')} className="flex items-center justify-start gap-2 rounded-md px-2 py-1.5 transition hover:bg-white/10 md:justify-start">
                                        <ChevronRightIcon className="h-4 w-4 shrink-0" />
                                        <span>Events</span>
                                    </Link>
                                </li>
                                <li>
                                    <Link href={withAppUrl('/contact')} className="flex items-center justify-start gap-2 rounded-md px-2 py-1.5 transition hover:bg-white/10 md:justify-start">
                                        <ChevronRightIcon className="h-4 w-4 shrink-0" />
                                        <span>Contact Us</span>
                                    </Link>
                                </li>
                            </ul>
                        </div>

                        <div className="rounded-xl border border-white/15 bg-white/5 p-4 text-white backdrop-blur-sm sm:p-5 md:px-6">
                            <p className="text-center text-sm leading-relaxed sm:text-base md:text-justify">
                                {app_about}
                            </p>
                        </div>

                        <div className="rounded-xl border border-white/15 bg-white/5 p-4 text-center backdrop-blur-sm sm:p-5 md:col-span-2 md:text-left lg:col-span-1">
                            <h1 className="text-xl font-bold text-yellow-300 sm:text-2xl">CONNECT WITH US:</h1>
                            <div className="mt-4 flex flex-wrap justify-center gap-3 text-3xl md:justify-start">
                                <a
                                    href={social_links.facebook}
                                    target="_blank"
                                    rel="noreferrer"
                                    aria-label="OSHE Foundation on Facebook"
                                    className="rounded-md focus:outline-none focus:ring-2 focus:ring-white/70"
                                >
                                    <FaFacebookF className="h-8 w-8 rounded-md bg-blue-700 p-1.5 text-white" />
                                </a>
                                <a
                                    href={social_links.twitter}
                                    target="_blank"
                                    rel="noreferrer"
                                    aria-label="OSHE Foundation on Twitter"
                                    className="rounded-lg"
                                >
                                    <FaXTwitter className="h-8 w-8 rounded-lg bg-white p-1.5 text-slate-900" />
                                </a>
                                <a
                                    href={social_links.youtube}
                                    target="_blank"
                                    rel="noreferrer"
                                    aria-label="OSHE Foundation on YouTube"
                                    className="rounded-lg focus:outline-none focus:ring-2 focus:ring-white/70"
                                >
                                    <FaYoutube className="h-8 w-8 rounded-lg bg-white p-1.5 text-red-600" />
                                </a>
                                <a
                                    href={social_links.instagram}
                                    target="_blank"
                                    rel="noreferrer"
                                    role="img"
                                    aria-label="Instagram"
                                    className="rounded-lg"
                                >
                                    <FaInstagram className="h-8 w-8 rounded-lg bg-white p-1.5 text-pink-600" />
                                </a>
                                <a
                                    href={social_links.linkedin}
                                    target="_blank"
                                    rel="noreferrer"
                                    aria-label="OSHE Foundation on LinkedIn"
                                    className="rounded-lg focus:outline-none focus:ring-2 focus:ring-white/70"
                                >
                                    <FaLinkedinIn className="h-8 w-8 rounded-lg bg-white p-1.5 text-blue-700" />
                                </a>
                            </div>

                            <div className="mt-6">
                                <h2 className="text-lg font-bold text-yellow-300">Email Subscription</h2>
                                <form onSubmit={handleSubscribe} className="mt-3 flex flex-col gap-2 sm:flex-row sm:items-center">
                                    <input
                                        type="email"
                                        name="email"
                                        value={email}
                                        onChange={(e) => setEmail(e.target.value)}
                                        placeholder="Enter your email"
                                        className="w-full rounded-md border border-white/40 bg-white px-3 py-2 text-sm text-gray-800 placeholder:text-gray-500 focus:outline-none focus:ring-2 focus:ring-yellow-300"
                                        required
                                    />
                                    <button
                                        type="submit"
                                        disabled={subscribeStatus === 'sending'}
                                        className="rounded-md bg-yellow-400 px-4 py-2 text-sm font-semibold text-gray-900 hover:bg-yellow-300 disabled:cursor-not-allowed disabled:opacity-70"
                                    >
                                        {subscribeStatus === 'sending' ? 'Submitting...' : 'Subscribe'}
                                    </button>
                                </form>

                                {subscribeStatus !== 'idle' && (
                                    <p className={`mt-2 text-sm ${subscribeStatus === 'success' ? 'text-green-300' : 'text-red-300'}`}>
                                        {subscribeMessage}
                                    </p>
                                )}
                            </div>
                        </div>
                    </div>
                </div>

                <div className="bg-neutral text-neutral-content">
                    <div className="mx-auto max-w-10/12 py-4">
                        <div className="flex flex-col items-center justify-between gap-3 text-center md:flex-row md:text-left">
                            <p className="text-sm">
                                Copyright &copy; 2003-{new Date().getFullYear()} OSHE Foundation
                            </p>
                            <nav className="flex flex-wrap items-center justify-center gap-x-4 gap-y-2 text-sm font-bold sm:text-base">
                                <a target="_blank" href="https://s3559.usc1.stableserver.net:2096/" className="hover:underline">
                                    WebMail
                                </a>
                                <Link href="/contact" className="hover:underline">Contact</Link>

                                {pages.map((page) => (
                                    <Link key={page.id} href={`/${page.slug}`} className="hover:underline">
                                        {page.name}
                                    </Link>
                                ))}
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
};

export default Footer;
