import React, { useEffect, useRef, useState } from 'react';
import { Link, router, usePage } from '@inertiajs/react';
import {
    Bars3Icon,
    BookOpenIcon,
    BriefcaseIcon,
    CalendarDaysIcon,
    ChevronDownIcon,
    CursorArrowRaysIcon,
    EnvelopeIcon,
    HomeIcon,
    MagnifyingGlassIcon,
    PhoneIcon,
    PlayCircleIcon,
    UserGroupIcon,
    UsersIcon,
    XMarkIcon
} from '@heroicons/react/24/solid';

const validColor = (value, fallback) => (/^#([0-9a-f]{3}|[0-9a-f]{6})$/i.test(value || '') ? value : fallback);

const normalizeDefault = (value, fallback, oldDefault) => {
    const normalized = String(value || '').toLowerCase();

    if (!normalized || normalized === String(oldDefault || '').toLowerCase()) {
        return fallback;
    }

    return value;
};

const defaultMenuItemsText = `Home | /
- Home Page | /
About OSHE | /OrganizationProfile
- Organization Profile | /OrganizationProfile
- Mission & Vision | /OurMissionandVision
- OSHE's Core Values | /oshes-core-values
- National Policy Contributions | /national-policy-contributions
- Governance Structure | /governance-structure
- Board of Trustees | /board-of-trustees
- Executive Team | /executive-team
- Team OSHE | /TeamOshe
- Annual Reports | /annual-reports
What We Do | /what-we-do/occupational-safety-and-health
- Occupational Safety & Health (OSH) | /what-we-do/occupational-safety-and-health
- Labour Rights & Decent Work | /what-we-do/labour-rights-decent-work
- Social Protection | /what-we-do/social-protection
- Environmental Sustainability | /what-we-do/environmental-sustainability
- Climate Change & Just Transition | /what-we-do/climate-change-just-transition
- Trade Union Strengthening | /what-we-do/trade-union-strengthening
- Research & Advocacy | /what-we-do/research-advocacy
- Capacity Building & Training | /what-we-do/capacity-building-training
Sectoral Coverage | /sectoral-coverage
Thematic Priorities | /thematic-priorities/occupational-safety-health
Programs & Projects | /OngoingProject
- Ongoing Projects | /OngoingProject
- Completed Projects | /PastProject
- Project Database | /project-database
- Interactive Bangladesh Project Map | /bangladesh-project-map
- Project Success Stories | /project-success-stories
Partners & Donors | /ProjectPartners
Media & Resource Center | /news
- All News & Resources | /news
- Photo Gallery | /photo-gallery
- Video Gallery | /videos
- Publications | /news?category=publications
- Newsletter | /news?category=newsletter
- Meeting Reports | /news?category=meeting-report
- Partner Reports | /news?category=partners-report
- Training Reports | /news?category=training-report
- Day Observations | /news?category=day-observation
Career | /career
- Career Opportunities | /career
Contact Us | /contact
- Contact Information | /contact
- Office Location | /office-location
- Feedback & Complaints | /feedback-complaints
- Newsletter Subscription | /newsletter-subscription`;

const menuIcons = {
    home: HomeIcon,
    'about oshe': UserGroupIcon,
    'what we do': BriefcaseIcon,
    'sectoral coverage': CursorArrowRaysIcon,
    'thematic priorities': BookOpenIcon,
    'programs & projects': CalendarDaysIcon,
    'partners & donors': UsersIcon,
    'media & resource center': PlayCircleIcon,
    career: BriefcaseIcon,
    'contact us': EnvelopeIcon,
};

const resourceLinkMap = {
    '/publications': '/news?category=publications',
    '/newsletter': '/news?category=newsletter',
    '/meeting-reports': '/news?category=meeting-report',
    '/partner-reports': '/news?category=partners-report',
    '/training-reports': '/news?category=training-report',
    '/day-observations': '/news?category=day-observation',
};

const normalizeMenuHref = (href) => resourceLinkMap[href] || href;

const ensureRequiredMenuLinks = (items) => items.map((item) => {
    if (item.label === 'Sectoral Coverage') {
        return {
            ...item,
            href: '/sectoral-coverage',
            type: 'link',
            links: [],
        };
    }

    if (item.label === 'Thematic Priorities') {
        return {
            ...item,
            type: 'link',
            links: [],
        };
    }

    if (item.label === 'Career') {
        return {
            ...item,
            href: '/career',
            links: [{ label: 'Career Opportunities', href: '/career' }],
            type: 'dropdown',
        };
    }

    if (item.label === 'Partners & Donors') {
        return {
            ...item,
            href: '/ProjectPartners',
            type: 'link',
            links: [],
        };
    }

    if (item.label !== 'Media & Resource Center') {
        return item;
    }

    const links = item.links || [];
    const hasAllResources = links.some((link) => link.href === '/news');

    if (hasAllResources) {
        return item;
    }

    return {
        ...item,
        href: item.href === '/photo-gallery' ? '/news' : item.href,
        links: [
            { label: 'All News & Resources', href: '/news' },
            ...links,
        ],
    };
});

const parseMenuItems = (menuText) => {
    const items = [];

    String(menuText || defaultMenuItemsText)
        .split(/\r?\n/)
        .forEach((rawLine) => {
            const line = rawLine.trim();

            if (!line || line.startsWith('#')) {
                return;
            }

            const isChild = line.startsWith('-');
            const cleanLine = isChild ? line.replace(/^-\s*/, '') : line;
            const [labelPart, hrefPart = '#'] = cleanLine.split('|').map((part) => part.trim());

            if (!labelPart) {
                return;
            }

            const href = labelPart.toLowerCase() === 'partner with us' && ['/contact', '/ProjectPartners'].includes(hrefPart)
                ? '/partner-with-us'
                : hrefPart;

            const link = {
                label: labelPart,
                href: normalizeMenuHref(href || '#'),
            };

            if (isChild && items.length) {
                items[items.length - 1].links.push(link);
                return;
            }

            items.push({
                ...link,
                type: 'dropdown',
                links: [],
                icon: menuIcons[labelPart.toLowerCase()] || BookOpenIcon,
            });
        });

    return ensureRequiredMenuLinks(items.map((item) => ({
        ...item,
        type: item.links.length ? 'dropdown' : 'link',
    })));
};

const Navbar = () => {
    const { props, url } = usePage();
    const {
        app_url,
        img,
        app_tagline,
        header_settings = {},
    } = props;
    const [mobileOpen, setMobileOpen] = useState(false);
    const [mobileDropdown, setMobileDropdown] = useState(null);
    const [searchOpen, setSearchOpen] = useState(false);
    const [searchKeyword, setSearchKeyword] = useState('');
    const [openDropdown, setOpenDropdown] = useState(null);
    const [pinnedDropdown, setPinnedDropdown] = useState(null);
    const desktopNavRef = useRef(null);

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
    const headerTagline = header_settings.tagline || app_tagline || 'Building Safer & Sustainable Workplaces for All';
    const accentColor = validColor(normalizeDefault(header_settings.top_bar_color, '#008f7a', '#064a86'), '#008f7a');
    const navBackgroundColor = validColor(normalizeDefault(header_settings.nav_background_color, '#007760', '#ffffff'), '#007760');
    const navTextColor = validColor(normalizeDefault(header_settings.nav_text_color, '#ffffff', '#0b3769'), '#ffffff');
    const navActiveColor = validColor(normalizeDefault(header_settings.nav_active_color, '#17b7ad', '#f6f8fb'), '#17b7ad');
    const taglineColor = validColor(normalizeDefault(header_settings.tagline_color, '#09265c', '#111111'), '#09265c');
    const headerBackgroundColor = validColor(header_settings.background_color, '#ffffff');

    const navPalette = [
        { bg: '#ffffff', active: '#e7f7f3', text: '#0f2638' },
    ];

    const navItems = parseMenuItems(header_settings.menu_items_text).map((item, index) => ({
        ...item,
        color: navPalette[index] || navPalette[navPalette.length - 1],
    }));

    const isActive = (path) => {
        const safePath = String(path || '').split('?')[0];

        return safePath === '/' ? url === '/' : url.startsWith(safePath);
    };

    useEffect(() => {
        const closeOnOutsideClick = (event) => {
            if (!desktopNavRef.current?.contains(event.target)) {
                setOpenDropdown(null);
                setPinnedDropdown(null);
            }
        };

        const closeOnEscape = (event) => {
            if (event.key === 'Escape') {
                setOpenDropdown(null);
                setPinnedDropdown(null);
            }
        };

        document.addEventListener('mousedown', closeOnOutsideClick);
        document.addEventListener('keydown', closeOnEscape);

        return () => {
            document.removeEventListener('mousedown', closeOnOutsideClick);
            document.removeEventListener('keydown', closeOnEscape);
        };
    }, []);

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

    const ContactItem = ({ icon: Icon, children }) => {
        if (!children) {
            return null;
        }

        return (
            <span className="inline-flex items-center gap-2 whitespace-nowrap">
                <Icon className="h-4 w-4" />
                {children}
            </span>
        );
    };

    const renderTagline = () => {
        const safeTagline = String(headerTagline || '');
        const match = safeTagline.match(/^(.*?)\b(Safer\s*&\s*Sustainable)\b(.*)$/i);

        if (!match) {
            return safeTagline;
        }

        return (
            <>
                {match[1]}
                <span className="text-[#008f7a]">{match[2]}</span>
                {match[3]}
            </>
        );
    };

    const Dropdown = ({ item }) => {
        const Icon = item.icon;
        const isOpen = openDropdown === item.label;
        const isDropdownActive = isActive(item.href) || item.links.some((link) => isActive(link.href));

        const toggleDropdown = () => {
            const nextOpen = isOpen && pinnedDropdown === item.label ? null : item.label;

            setOpenDropdown(nextOpen);
            setPinnedDropdown(nextOpen);
        };

        return (
        <li
            className={`relative flex min-h-[48px] flex-1 items-stretch ${isDropdownActive ? 'oshe-active-li' : ''}`}
            onMouseEnter={() => {
                setOpenDropdown(item.label);
                setPinnedDropdown((current) => (current === item.label ? current : null));
            }}
            onMouseLeave={() => {
                if (pinnedDropdown !== item.label) {
                    setOpenDropdown(null);
                }
            }}
        >
            <div
                className="oshe-nav-item relative inline-flex min-h-[48px] w-full items-center justify-center gap-1.5 px-2 py-1 text-center text-[12px] font-bold leading-tight transition 2xl:text-[13px]"
                style={{
                    color: item.color.text,
                    backgroundColor: isDropdownActive ? item.color.active : item.color.bg,
                    boxShadow: isDropdownActive ? `inset 0 -3px 0 ${accentColor}` : undefined,
                }}
            >
                <Link
                    href={withAppUrl(item.href)}
                    className="inline-flex min-w-0 flex-1 cursor-pointer items-center justify-center gap-1.5 rounded-md focus:outline-none focus-visible:ring-4 focus-visible:ring-white/45"
                    onClick={() => {
                        setOpenDropdown(null);
                        setPinnedDropdown(null);
                    }}
                >
                    {Icon && <Icon className="h-4 w-4 shrink-0 text-emerald-700 2xl:h-5 2xl:w-5" />}
                    <span className="max-w-[118px]">{item.label}</span>
                </Link>
                <button
                    type="button"
                    onClick={toggleDropdown}
                    className="inline-flex h-8 w-5 shrink-0 cursor-pointer items-center justify-center rounded-md focus:outline-none focus-visible:ring-4 focus-visible:ring-white/45"
                    aria-label={`Open ${item.label} submenu`}
                    aria-haspopup="menu"
                    aria-expanded={isOpen}
                >
                    <ChevronDownIcon className={`h-4 w-4 transition-transform duration-200 ${isOpen ? 'rotate-180' : ''}`} />
                </button>
            </div>
            <ul
                className={`absolute left-0 top-full z-40 min-w-80 rounded-b-md border border-slate-200 bg-white p-2 text-slate-800 shadow-2xl transition duration-200 ${
                    isOpen ? 'pointer-events-auto visible translate-y-0 opacity-100' : 'pointer-events-none invisible translate-y-2 opacity-0'
                }`}
                role="menu"
            >
                {item.links.map((link) => (
                    <li key={link.href}>
                        <Link
                            href={withAppUrl(link.href)}
                            onClick={() => {
                                setOpenDropdown(null);
                                setPinnedDropdown(null);
                            }}
                            className={`block rounded-md px-3 py-2.5 text-[15px] font-semibold transition hover:bg-emerald-50 hover:text-emerald-900 ${isActive(link.href) ? 'bg-emerald-50 text-emerald-900' : ''}`}
                            role="menuitem"
                        >
                            {link.label}
                        </Link>
                    </li>
                ))}
            </ul>
        </li>
        );
    };

    const DesktopLink = ({ item }) => (
        <li className={`flex min-h-[48px] flex-1 items-stretch ${isActive(item.href) ? 'oshe-active-li' : ''}`}>
            <Link
                href={withAppUrl(item.href)}
                className="oshe-nav-item relative inline-flex min-h-[48px] w-full items-center justify-center gap-1.5 px-2 py-1 text-center text-[12px] font-bold leading-tight transition 2xl:text-[13px]"
                style={{
                    color: item.color.text,
                    backgroundColor: isActive(item.href) ? item.color.active : item.color.bg,
                    boxShadow: isActive(item.href) ? `inset 0 -3px 0 ${accentColor}` : undefined,
                }}
            >
                {item.icon && <item.icon className="h-4 w-4 shrink-0 text-emerald-700 2xl:h-5 2xl:w-5" />}
                <span className="max-w-[118px]">{item.label}</span>
            </Link>
        </li>
    );

    return (
        <>
            <header className="sticky top-0 z-50 bg-white shadow-[0_10px_30px_rgba(15,38,56,0.12)] relative">
                <div className="hidden xl:block" style={{ backgroundColor: headerBackgroundColor }}>
                    <div className="oshe-top-strip" style={{ background: `linear-gradient(90deg, ${navBackgroundColor}, ${accentColor})` }}>
                        <div className="mx-auto flex h-10 max-w-[1440px] items-center justify-between gap-4 px-6 text-sm font-semibold text-white">
                            <div className="flex min-w-0 items-center gap-5">
                                <ContactItem icon={PhoneIcon}>{header_settings.phone}</ContactItem>
                                <ContactItem icon={EnvelopeIcon}>{header_settings.email}</ContactItem>
                            </div>
                            <div className="flex items-center gap-2">
                                <button
                                    type="button"
                                    onClick={() => setSearchOpen((prev) => !prev)}
                                    className="inline-flex h-8 items-center gap-2 rounded-md border border-white/25 px-3 text-sm transition hover:bg-white/12 focus:outline-none focus-visible:ring-4 focus-visible:ring-white/35"
                                    aria-label="Search"
                                >
                                    {searchOpen ? <XMarkIcon className="h-4 w-4" /> : <MagnifyingGlassIcon className="h-4 w-4" />}
                                    <span>{header_settings.search_text || 'Search'}</span>
                                </button>
                                {header_settings.partner_button_text && (
                                    <Link
                                        href={withAppUrl(header_settings.partner_button_link || '/partner-with-us')}
                                        className="inline-flex h-8 items-center rounded-md bg-white px-4 text-sm font-extrabold text-emerald-800 shadow-sm transition hover:bg-emerald-50"
                                    >
                                        {header_settings.partner_button_text}
                                    </Link>
                                )}
                            </div>
                        </div>
                    </div>

                    <div className="oshe-header-scene">
                        <div className="mx-auto flex h-[92px] max-w-[1440px] items-center gap-7 px-6">
                            <Link
                                href={withAppUrl('/')}
                                className="relative z-10 flex h-full w-[330px] shrink-0 items-center"
                            >
                                <img
                                    className="h-[86px] w-full object-contain object-left"
                                    src={headerLogo}
                                    alt="OSHE Foundation"
                                />
                            </Link>

                            <span className="h-[58px] w-px shrink-0 bg-emerald-700/20" aria-hidden="true" />

                            <div className="relative z-10 flex min-w-0 flex-1 flex-col items-end justify-center text-right">
                                <p className="text-right text-[30px] font-black leading-tight tracking-normal" style={{ color: taglineColor }}>
                                    {renderTagline()}
                                </p>
                                <p className="mt-2 max-w-4xl text-right text-sm font-semibold leading-5 text-slate-500">
                                    Occupational safety, health, rights and sustainable workplaces for workers across Bangladesh.
                                </p>
                            </div>
                        </div>
                    </div>

                    <nav
                        ref={desktopNavRef}
                        className="relative z-20 border-y border-slate-200 bg-white"
                        style={{ background: '#ffffff' }}
                    >
                        <div className="mx-auto max-w-[1440px] px-4">
                            <ul className="flex min-h-[48px] items-stretch justify-between">
                                {navItems.map((item) => (
                                    item.type === 'dropdown'
                                        ? <Dropdown key={item.label} item={item} />
                                        : <DesktopLink key={item.href} item={item} />
                                ))}
                            </ul>
                        </div>
                    </nav>
                </div>

                <div className="xl:hidden" style={{ backgroundColor: headerBackgroundColor }}>
                    <div className="oshe-header-scene flex min-h-[88px] items-center justify-between gap-4 px-4">
                        <Link href={withAppUrl('/')} className="relative z-10 inline-flex min-w-0 items-center">
                            <img className="max-h-14 w-auto object-contain" src={headerLogo} alt="OSHE Foundation" />
                        </Link>
                        <div className="relative z-10 flex items-center gap-2">
                            <button
                                type="button"
                                onClick={() => setSearchOpen((prev) => !prev)}
                                className="inline-flex h-10 w-10 items-center justify-center rounded-md border border-emerald-100 bg-white/80 text-emerald-800 shadow-sm"
                                aria-label="Search"
                            >
                                {searchOpen ? <XMarkIcon className="h-5 w-5" /> : <MagnifyingGlassIcon className="h-5 w-5" />}
                            </button>
                            <button
                                type="button"
                                onClick={() => setMobileOpen((prev) => !prev)}
                                className="inline-flex h-10 w-10 items-center justify-center rounded-md text-white shadow-sm"
                                style={{ backgroundColor: accentColor }}
                                aria-label="Toggle menu"
                                aria-expanded={mobileOpen}
                            >
                                <Bars3Icon className="h-6 w-6" />
                            </button>
                        </div>
                    </div>
                    <div
                        className="h-2"
                        style={{ background: `linear-gradient(90deg, ${navBackgroundColor}, ${accentColor})` }}
                    />
                </div>

                {searchOpen && (
                    <div className="absolute left-0 top-full z-40 w-full border-t border-slate-200 bg-white/95 px-4 py-5 shadow-md backdrop-blur">
                        <form onSubmit={submitSearch} className="mx-auto flex w-full max-w-[760px]">
                            <input
                                type="text"
                                value={searchKeyword}
                                onChange={(e) => setSearchKeyword(e.target.value)}
                                placeholder={header_settings.search_text || 'Search'}
                                className="h-12 w-full rounded-l-md border border-slate-300 bg-white px-4 text-base text-slate-700 outline-none focus:border-emerald-700"
                            />
                            <button
                                type="submit"
                                className="inline-flex h-12 w-14 items-center justify-center rounded-r-md border border-l-0 border-slate-300 text-white"
                                style={{ backgroundColor: navBackgroundColor }}
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
                    className={`absolute right-0 top-0 h-full w-[88%] max-w-sm overflow-y-auto border-l border-emerald-900/20 bg-white shadow-2xl transition-transform duration-300 ${mobileOpen ? 'translate-x-0' : 'translate-x-full'}`}
                    role="dialog"
                    aria-modal="true"
                    aria-label="Mobile navigation"
                >
                    <div className="sticky top-0 flex items-center justify-between border-b border-slate-200 bg-white px-4 py-3">
                        <img className="max-h-12 w-auto object-contain" src={headerLogo} alt="OSHE Foundation" />
                        <button
                            type="button"
                            onClick={() => setMobileOpen(false)}
                            className="inline-flex h-9 w-9 items-center justify-center rounded-md border border-slate-300 text-slate-700"
                            aria-label="Close menu"
                        >
                            <XMarkIcon className="h-5 w-5" />
                        </button>
                    </div>

                    <div className="border-b border-slate-200 px-4 py-4 bg-emerald-50">
                        <p className="text-sm font-bold leading-6" style={{ color: taglineColor }}>{headerTagline}</p>
                        <div className="mt-3 space-y-2 text-sm font-semibold text-emerald-900">
                            <ContactItem icon={PhoneIcon}>{header_settings.phone}</ContactItem>
                            <ContactItem icon={EnvelopeIcon}>{header_settings.email}</ContactItem>
                        </div>
                    </div>

                    <div className="p-4">
                        <ul className="space-y-2 text-sm font-semibold text-slate-800">
                            {navItems.map((item) => {
                                const isItemOpen = mobileDropdown === item.label;
                                const itemActive = isActive(item.href) || item.links?.some((link) => isActive(link.href));
                                const Icon = item.icon;

                                return (
                                <li key={item.label} className="overflow-hidden rounded-md border border-slate-200 bg-white">
                                    {item.type === 'dropdown' ? (
                                        <>
                                            <button
                                                type="button"
                                                onClick={() => setMobileDropdown((current) => (current === item.label ? null : item.label))}
                                                className={`flex w-full items-center justify-between gap-3 px-3 py-3 text-left transition ${itemActive ? 'bg-emerald-50 text-emerald-900' : 'hover:bg-emerald-50'}`}
                                                aria-expanded={isItemOpen}
                                            >
                                                <span className="inline-flex min-w-0 items-center gap-2">
                                                    {Icon && <Icon className="h-5 w-5 shrink-0 text-emerald-700" />}
                                                    <span>{item.label}</span>
                                                </span>
                                                <ChevronDownIcon className={`h-4 w-4 shrink-0 transition-transform duration-200 ${isItemOpen ? 'rotate-180' : ''}`} />
                                            </button>
                                            <ul className={`grid transition-[grid-template-rows] duration-200 ${isItemOpen ? 'grid-rows-[1fr]' : 'grid-rows-[0fr]'}`}>
                                                <li className="overflow-hidden">
                                                    <div className="space-y-1 border-t border-slate-100 p-2">
                                                        {item.links.map((link) => (
                                                            <Link
                                                                key={link.href}
                                                                href={withAppUrl(link.href)}
                                                                onClick={() => setMobileOpen(false)}
                                                                className={`block rounded-md px-3 py-2.5 transition hover:bg-emerald-50 ${isActive(link.href) ? 'bg-emerald-50 text-emerald-900' : ''}`}
                                                            >
                                                                {link.label}
                                                            </Link>
                                                        ))}
                                                    </div>
                                                </li>
                                            </ul>
                                        </>
                                    ) : (
                                        <Link
                                            href={withAppUrl(item.href)}
                                            onClick={() => setMobileOpen(false)}
                                            className={`flex items-center gap-2 px-3 py-3 transition hover:bg-emerald-50 ${itemActive ? 'bg-emerald-50 text-emerald-900' : ''}`}
                                        >
                                            {Icon && <Icon className="h-5 w-5 shrink-0 text-emerald-700" />}
                                            {item.label}
                                        </Link>
                                    )}
                                </li>
                                );
                            })}
                        </ul>
                    </div>
                </aside>
            </div>

            <style>{`
                .oshe-top-strip {
                    border-top: 1px solid rgba(255, 255, 255, 0.18);
                }

                .oshe-header-scene {
                    position: relative;
                    overflow: hidden;
                    background:
                        linear-gradient(90deg, rgba(0, 143, 122, 0.08), transparent 28%),
                        linear-gradient(180deg, rgba(250, 253, 252, 0.96), rgba(255, 255, 255, 1));
                }

                .oshe-header-scene::before {
                    content: "";
                    position: absolute;
                    inset: 0 0 auto;
                    height: 4px;
                    background:
                        linear-gradient(90deg, rgba(0, 143, 122, 0.95), rgba(23, 183, 173, 0.7), rgba(141, 198, 63, 0.85));
                }

                .oshe-header-scene::after {
                    content: "";
                    position: absolute;
                    right: -120px;
                    top: -180px;
                    width: 420px;
                    height: 420px;
                    border-radius: 999px;
                    opacity: 0.16;
                    background:
                        radial-gradient(circle, rgba(0, 143, 122, 0.95), transparent 66%);
                }

                .oshe-nav-item {
                    border-left: 1px solid rgba(15, 38, 56, 0.08);
                    text-shadow: none;
                }

                .oshe-nav-item:hover {
                    background: #f0faf7 !important;
                    color: #006d5a !important;
                }

                .oshe-active-li {
                    position: relative;
                    z-index: 1;
                }

                .oshe-active-li .oshe-nav-item {
                    font-weight: 950;
                }
            `}</style>
        </>
    );
};

export default Navbar;
