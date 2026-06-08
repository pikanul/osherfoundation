import React, { useState } from 'react';
import { Link, router, usePage } from '@inertiajs/react';
import {
    Bars3Icon,
    ChevronDownIcon,
    EnvelopeIcon,
    MagnifyingGlassIcon,
    PhoneIcon,
    XMarkIcon
} from '@heroicons/react/24/solid';

const validColor = (value, fallback) => (/^#([0-9a-f]{3}|[0-9a-f]{6})$/i.test(value || '') ? value : fallback);

const Navbar = () => {
    const { props, url } = usePage();
    const {
        newsscategories = [],
        app_url,
        projectcategories = [],
        img,
        social_links = {},
        app_tagline,
        header_settings = {},
        pages = []
    } = props;
    const [mobileOpen, setMobileOpen] = useState(false);
    const [searchOpen, setSearchOpen] = useState(false);
    const [searchKeyword, setSearchKeyword] = useState('');

    const appUrl = (app_url || '').replace(/\/+$/, '');
    const withAppUrl = (path) => {
        if (!path) {
            return '#';
        }

        if (/^https?:\/\//i.test(path)) {
            return path;
        }

        return appUrl ? `${appUrl}${path.startsWith('/') ? path : `/${path}`}` : path;
    };

    const headerLogo = header_settings.logo || img;
    const headerTagline = header_settings.tagline || app_tagline;
    const topBarColor = validColor(header_settings.top_bar_color, '#064a86');
    const topTextColor = validColor(header_settings.top_text_color, '#ffffff');
    const headerBackgroundColor = validColor(header_settings.background_color, '#ffffff');
    const taglineColor = validColor(header_settings.tagline_color, '#111111');
    const navBackgroundColor = validColor(header_settings.nav_background_color, '#ffffff');
    const navTextColor = validColor(header_settings.nav_text_color, '#0b3769');
    const navActiveColor = validColor(header_settings.nav_active_color, '#f6f8fb');

    const aboutLinks = [
        { label: 'Our Mission and Vision', href: '/OurMissionandVision' },
        { label: 'OSHE Strength', href: '/OSHEStrength' },
        { label: 'Team OSHE', href: '/TeamOshe' },
        { label: 'Organization Profile', href: '/OrganizationProfile' },
        { label: 'Career', href: '/career' },
        ...pages.map((page) => ({
            label: page.name,
            href: `/${page.slug}`
        }))
    ];

    const projectLinks = [
        ...projectcategories.map((category) => ({
            label: `${category.name} Project`,
            href: `/project/${category.slug}`
        })),
        { label: 'Events', href: '/Events' }
    ];

    const mediaLinks = [
        { label: 'Photo Gallery', href: '/photo-gallery' },
        { label: 'Video Gallery', href: '/videos' },
        ...newsscategories.map((category) => ({
            label: category.name,
            href: `/news?category=${category.slug}`
        }))
    ];

    const navItemStyle = (path) => ({
        color: navTextColor,
        backgroundColor: path === '/' ? (url === '/' ? navActiveColor : 'transparent') : (url.startsWith(path) ? navActiveColor : 'transparent')
    });

    const navLinkClass = 'inline-flex h-14 items-center px-5 text-[15px] font-bold transition hover:brightness-95';
    const dropdownTriggerClass = 'group inline-flex h-14 cursor-pointer items-center gap-1.5 px-5 text-[15px] font-bold transition hover:brightness-95';
    const dropdownMenuClass = 'absolute left-0 top-full z-30 hidden min-w-72 border border-slate-200 bg-white p-2 text-slate-800 shadow-2xl group-hover:block';

    const submitSearch = (e) => {
        e.preventDefault();
        const keyword = searchKeyword.trim();
        router.get(
            withAppUrl('/news'),
            keyword ? { keyword } : {},
            { preserveState: false, preserveScroll: false }
        );
        setSearchOpen(false);
        setMobileOpen(false);
    };

    const Dropdown = ({ label, links }) => (
        <li className="group relative">
            <span className={dropdownTriggerClass} style={{ color: navTextColor }}>
                {label}
                <ChevronDownIcon className="h-3.5 w-3.5 transition-transform duration-200 group-hover:rotate-180" />
            </span>
            <ul className={dropdownMenuClass}>
                {links.map((item) => (
                    <li key={item.href}>
                        <Link href={withAppUrl(item.href)} className="block px-3 py-2.5 text-[15px] font-semibold transition hover:bg-blue-50 hover:text-blue-900">
                            {item.label}
                        </Link>
                    </li>
                ))}
            </ul>
        </li>
    );

    return (
        <>
            <header className="relative z-50 border-b border-slate-200 bg-white shadow-sm">
                <div className="hidden xl:block" style={{ backgroundColor: headerBackgroundColor }}>
                    <div className="grid grid-cols-[330px_1fr] items-stretch">
                        <Link href={withAppUrl('/')} className="flex min-h-[116px] items-center px-10">
                            <img className="max-h-24 w-auto object-contain" src={headerLogo} alt="OSHE Foundation" />
                        </Link>

                        <div className="flex min-w-0 flex-col">
                            <div
                                className="ml-auto flex h-[50px] min-w-[58%] items-center justify-end gap-8 px-10 text-sm font-semibold"
                                style={{
                                    backgroundColor: topBarColor,
                                    color: topTextColor,
                                    clipPath: 'polygon(34px 0, 100% 0, 100% 100%, 0 100%)'
                                }}
                            >
                                {header_settings.phone && (
                                    <span className="inline-flex items-center gap-2 whitespace-nowrap">
                                        <PhoneIcon className="h-4 w-4" />
                                        {header_settings.phone}
                                    </span>
                                )}
                                {header_settings.email && (
                                    <span className="inline-flex items-center gap-2 whitespace-nowrap">
                                        <EnvelopeIcon className="h-4 w-4" />
                                        {header_settings.email}
                                    </span>
                                )}
                                <button
                                    type="button"
                                    onClick={() => setSearchOpen((prev) => !prev)}
                                    className="inline-flex items-center gap-2 whitespace-nowrap"
                                    style={{ color: topTextColor }}
                                >
                                    {searchOpen ? <XMarkIcon className="h-5 w-5" /> : <MagnifyingGlassIcon className="h-5 w-5" />}
                                    {header_settings.search_text || 'Search'}
                                </button>
                                <span className="h-5 w-px bg-white/35" />
                                <Link
                                    href={withAppUrl(header_settings.partner_button_link || '/ProjectPartners')}
                                    className="whitespace-nowrap text-base font-medium"
                                    style={{ color: topTextColor }}
                                >
                                    {header_settings.partner_button_text || 'Partner With Us'}
                                </Link>
                            </div>

                            <div className="flex flex-1 items-center justify-end px-10">
                                <p className="text-right text-[30px] font-medium leading-tight tracking-normal" style={{ color: taglineColor }}>
                                    {headerTagline}
                                </p>
                            </div>
                        </div>
                    </div>

                    <nav className="border-t border-slate-200" style={{ backgroundColor: navBackgroundColor }}>
                        <ul className="mx-auto flex max-w-full items-center justify-between text-sm">
                            <li>
                                <Link href={withAppUrl('/')} className={navLinkClass} style={navItemStyle('/')}>
                                    {header_settings.nav_home_text || 'Home'}
                                </Link>
                            </li>
                            <Dropdown label={header_settings.nav_about_text || 'About OSHE'} links={aboutLinks} />
                            <Dropdown label={header_settings.nav_work_text || 'Our Work'} links={projectLinks} />
                            <Dropdown label={header_settings.nav_programs_text || 'Programs & Projects'} links={projectLinks} />
                            <Dropdown label={header_settings.nav_research_text || 'Research & Publications'} links={mediaLinks} />
                            <Dropdown label={header_settings.nav_media_text || 'Media Center'} links={mediaLinks} />
                            <li>
                                <Link href={withAppUrl('/ProjectPartners')} className={navLinkClass} style={navItemStyle('/ProjectPartners')}>
                                    {header_settings.nav_partners_text || 'Partners'}
                                </Link>
                            </li>
                            <li>
                                <Link href={withAppUrl('/news')} className={navLinkClass} style={navItemStyle('/news')}>
                                    {header_settings.nav_news_events_text || 'News & Events'}
                                </Link>
                            </li>
                            <li>
                                <Link href={withAppUrl('/contact')} className={navLinkClass} style={navItemStyle('/contact')}>
                                    {header_settings.nav_contact_text || 'Contact Us'}
                                </Link>
                            </li>
                        </ul>
                    </nav>
                </div>

                <div className="flex items-center justify-between px-4 py-3 xl:hidden" style={{ backgroundColor: headerBackgroundColor }}>
                    <Link href={withAppUrl('/')} className="inline-flex items-center">
                        <img className="max-h-16 w-auto" src={headerLogo} alt="OSHE Foundation" />
                    </Link>
                    <button
                        type="button"
                        onClick={() => setMobileOpen((prev) => !prev)}
                        className="inline-flex h-10 w-10 items-center justify-center"
                        style={{ color: navTextColor }}
                        aria-label="Toggle menu"
                        aria-expanded={mobileOpen}
                    >
                        <Bars3Icon className="h-7 w-7" />
                    </button>
                </div>

                {searchOpen && (
                    <div className="absolute left-0 top-full z-40 hidden w-full border-t border-slate-200 bg-[#f4f7fa] py-8 shadow-md xl:block">
                        <form onSubmit={submitSearch} className="mx-auto flex w-full max-w-[720px]">
                            <input
                                type="text"
                                value={searchKeyword}
                                onChange={(e) => setSearchKeyword(e.target.value)}
                                placeholder={header_settings.search_text || 'Search'}
                                className="h-12 w-full border border-[#9cb0c0] bg-white px-4 text-base text-slate-700 outline-none focus:border-[#064a86]"
                            />
                            <button
                                type="submit"
                                className="inline-flex h-12 w-12 items-center justify-center border border-l-0 border-[#9cb0c0] bg-white text-[#064a86]"
                                aria-label="Submit search"
                            >
                                <MagnifyingGlassIcon className="h-6 w-6" />
                            </button>
                        </form>
                    </div>
                )}
            </header>

            <div className={`fixed inset-0 z-[60] transition-opacity duration-300 xl:hidden ${mobileOpen ? 'pointer-events-auto opacity-100' : 'pointer-events-none opacity-0'}`}>
                <button
                    type="button"
                    aria-label="Close menu"
                    onClick={() => setMobileOpen(false)}
                    className={`absolute inset-0 bg-black/45 transition-opacity duration-300 ${mobileOpen ? 'opacity-100' : 'opacity-0'}`}
                />

                <aside
                    className={`absolute right-0 top-0 h-full w-[88%] max-w-sm overflow-y-auto border-l border-blue-900/20 bg-white shadow-2xl transition-transform duration-300 ${mobileOpen ? 'translate-x-0' : 'translate-x-full'}`}
                    role="dialog"
                    aria-modal="true"
                    aria-label="Mobile navigation"
                >
                    <div className="sticky top-0 flex items-center justify-between border-b border-slate-200 bg-white px-4 py-3">
                        <p className="text-sm font-bold uppercase tracking-wide" style={{ color: navTextColor }}>Menu</p>
                        <button
                            type="button"
                            onClick={() => setMobileOpen(false)}
                            className="rounded-md border border-slate-300 px-2 py-1 text-xs font-bold text-slate-700"
                        >
                            Close
                        </button>
                    </div>

                    <div className="p-4">
                        <form onSubmit={submitSearch} className="mb-5 flex">
                            <input
                                type="text"
                                value={searchKeyword}
                                onChange={(e) => setSearchKeyword(e.target.value)}
                                placeholder={header_settings.search_text || 'Search'}
                                className="h-12 w-full border border-[#9cb0c0] bg-[#f4f7fa] px-4 text-base text-slate-700 outline-none focus:border-[#064a86]"
                            />
                            <button
                                type="submit"
                                className="inline-flex h-12 w-12 items-center justify-center border border-l-0 border-[#9cb0c0] bg-[#f4f7fa]"
                                style={{ color: navTextColor }}
                                aria-label="Submit search"
                            >
                                <MagnifyingGlassIcon className="h-6 w-6" />
                            </button>
                        </form>

                        <ul className="space-y-2 text-sm font-semibold" style={{ color: navTextColor }}>
                            <li><Link href={withAppUrl('/')} className="block rounded-md px-3 py-2 hover:bg-blue-100">{header_settings.nav_home_text || 'Home'}</Link></li>
                            <li className="rounded-md border border-slate-200 p-2">
                                <p className="px-1 pb-1 text-xs font-bold uppercase tracking-wide">{header_settings.nav_about_text || 'About OSHE'}</p>
                                <ul className="space-y-1">
                                    {aboutLinks.map((item) => (
                                        <li key={item.href}>
                                            <Link href={withAppUrl(item.href)} className="block rounded-md px-3 py-2 hover:bg-blue-100">{item.label}</Link>
                                        </li>
                                    ))}
                                </ul>
                            </li>
                            <li className="rounded-md border border-slate-200 p-2">
                                <p className="px-1 pb-1 text-xs font-bold uppercase tracking-wide">{header_settings.nav_programs_text || 'Programs & Projects'}</p>
                                <ul className="space-y-1">
                                    {projectLinks.map((item) => (
                                        <li key={item.href}>
                                            <Link href={withAppUrl(item.href)} className="block rounded-md px-3 py-2 hover:bg-blue-100">{item.label}</Link>
                                        </li>
                                    ))}
                                </ul>
                            </li>
                            <li className="rounded-md border border-slate-200 p-2">
                                <p className="px-1 pb-1 text-xs font-bold uppercase tracking-wide">{header_settings.nav_media_text || 'Media Center'}</p>
                                <ul className="space-y-1">
                                    {mediaLinks.map((item) => (
                                        <li key={item.href}>
                                            <Link href={withAppUrl(item.href)} className="block rounded-md px-3 py-2 hover:bg-blue-100">{item.label}</Link>
                                        </li>
                                    ))}
                                </ul>
                            </li>
                            <li><Link href={withAppUrl('/ProjectPartners')} className="block rounded-md px-3 py-2 hover:bg-blue-100">{header_settings.nav_partners_text || 'Partners'}</Link></li>
                            <li><Link href={withAppUrl('/news')} className="block rounded-md px-3 py-2 hover:bg-blue-100">{header_settings.nav_news_events_text || 'News & Events'}</Link></li>
                            <li><Link href={withAppUrl('/contact')} className="block rounded-md px-3 py-2 hover:bg-blue-100">{header_settings.nav_contact_text || 'Contact Us'}</Link></li>
                            <li>
                                <Link href={withAppUrl(header_settings.partner_button_link || '/ProjectPartners')} className="mt-3 block rounded-md px-3 py-2 text-white" style={{ backgroundColor: topBarColor }}>
                                    {header_settings.partner_button_text || 'Partner With Us'}
                                </Link>
                            </li>
                        </ul>
                    </div>
                </aside>
            </div>
        </>
    );
};

export default Navbar;
