import React, { useState } from 'react';
import { Link, usePage } from '@inertiajs/react';
import bgimg from '../../assets/logo/footer-workers-hands.png';
import {
    ChevronRightIcon,
    EnvelopeIcon,
    GlobeAltIcon,
    MapPinIcon,
    PhoneIcon,
} from '@heroicons/react/24/solid';
import { FaFacebookF, FaInstagram, FaLinkedinIn, FaXTwitter, FaYoutube } from 'react-icons/fa6';

const socialIcons = {
    facebook: FaFacebookF,
    twitter: FaXTwitter,
    x: FaXTwitter,
    youtube: FaYoutube,
    instagram: FaInstagram,
    linkedin: FaLinkedinIn,
};

const parseLinks = (text, fallback = []) => {
    const lines = String(text || '')
        .split(/\r?\n/)
        .map((line) => line.trim())
        .filter(Boolean);

    const parsed = lines
        .map((line) => {
            const [label, href] = line.split('|').map((part) => part?.trim());
            return label && href ? { label, href } : null;
        })
        .filter(Boolean);

    return parsed.length ? parsed : fallback;
};

const parseSocialLinks = (text, fallback = []) => {
    const lines = String(text || '')
        .split(/\r?\n/)
        .map((line) => line.trim())
        .filter(Boolean);

    const parsed = lines
        .map((line) => {
            const [type, label, href] = line.split('|').map((part) => part?.trim());
            return type && label && href ? { type: type.toLowerCase(), label, href } : null;
        })
        .filter(Boolean);

    return parsed.length ? parsed : fallback;
};

const csrfToken = () => {
    const cookie = document.cookie
        .split('; ')
        .find((row) => row.startsWith('XSRF-TOKEN='));

    return cookie ? decodeURIComponent(cookie.split('=')[1]) : '';
};

const clampNumber = (value, fallback, min, max) => {
    const parsed = Number.parseFloat(value);
    if (!Number.isFinite(parsed)) return fallback;
    return Math.min(max, Math.max(min, parsed));
};

const pixelValue = (value, fallback, min = 8, max = 800) => `${clampNumber(value, fallback, min, max)}px`;

const safeColor = (value, fallback) => {
    const color = String(value || '').trim();
    return /^#([0-9a-f]{3}|[0-9a-f]{6})$/i.test(color) ? color : fallback;
};

const hexToRgb = (hex) => {
    const normalized = hex.length === 4
        ? `#${hex[1]}${hex[1]}${hex[2]}${hex[2]}${hex[3]}${hex[3]}`
        : hex;
    const intValue = Number.parseInt(normalized.slice(1), 16);

    return `${(intValue >> 16) & 255}, ${(intValue >> 8) & 255}, ${intValue & 255}`;
};

const safeBackgroundSize = (value) => {
    const size = String(value || '').trim();
    return ['cover', 'contain', 'auto'].includes(size) ? size : 'cover';
};

const safeBackgroundPosition = (value) => {
    const position = String(value || '').trim();
    return /^[a-z0-9%.\-\s]+$/i.test(position) ? position : 'center bottom';
};

const Footer = () => {
    const { props } = usePage();
    const {
        app_url,
        footer_settings = {},
    } = props;
    const [email, setEmail] = useState('');
    const [subscribeStatus, setSubscribeStatus] = useState('idle');
    const [subscribeMessage, setSubscribeMessage] = useState('');

    const appUrl = (app_url || '').replace(/\/+$/, '');
    const withAppUrl = (path) => {
        if (!path || path === '#') return path || '#';
        if (/^(https?:)?\/\//i.test(path) || path.startsWith('mailto:') || path.startsWith('tel:')) return path;
        const normalizedPath = path.startsWith('/') ? path : `/${path}`;
        return appUrl ? `${appUrl}${normalizedPath}` : normalizedPath;
    };

    const quickLinks = parseLinks(footer_settings.quick_links_text, [
        { label: 'About Us', href: '/OrganizationProfile' },
        { label: 'Media', href: '/news' },
        { label: 'Career', href: '/career' },
        { label: 'Events', href: '/Events' },
    ]);
    const socialLinks = parseSocialLinks(footer_settings.social_links_text, [
        { type: 'facebook', label: 'Facebook', href: '#' },
        { type: 'twitter', label: 'X', href: '#' },
        { type: 'youtube', label: 'YouTube', href: '#' },
        { type: 'instagram', label: 'Instagram', href: '#' },
        { type: 'linkedin', label: 'LinkedIn', href: '#' },
    ]);
    const footerStartColor = safeColor(footer_settings.overlay_start_color, '#2e4b98');
    const footerEndColor = safeColor(footer_settings.overlay_end_color, '#4264b2');
    const footerBaseColor = safeColor(footer_settings.overlay_base_color, '#26418c');
    const footerStyleVars = {
        backgroundImage: `url(${footer_settings.background_image || bgimg})`,
        '--footer-bg-size': safeBackgroundSize(footer_settings.background_size),
        '--footer-bg-position': safeBackgroundPosition(footer_settings.background_position),
        '--footer-overlay-start': hexToRgb(footerStartColor),
        '--footer-overlay-end': hexToRgb(footerEndColor),
        '--footer-overlay-base': hexToRgb(footerBaseColor),
        '--footer-overlay-start-opacity': clampNumber(footer_settings.overlay_start_opacity, 0.62, 0, 1),
        '--footer-overlay-end-opacity': clampNumber(footer_settings.overlay_end_opacity, 0.56, 0, 1),
        '--footer-overlay-base-opacity': clampNumber(footer_settings.overlay_base_opacity, 0.5, 0, 1),
        '--footer-text-color': safeColor(footer_settings.text_color, '#ffffff'),
        '--footer-accent-color': safeColor(footer_settings.accent_color, '#ffd51f'),
        '--footer-separator-color': safeColor(footer_settings.separator_color, '#ffffff'),
        '--footer-main-height': pixelValue(footer_settings.main_height, 330, 180, 700),
        '--footer-bottom-height': pixelValue(footer_settings.bottom_height, 58, 32, 160),
        '--footer-heading-font-size': pixelValue(footer_settings.heading_font_size, 21, 12, 48),
        '--footer-body-font-size': pixelValue(footer_settings.body_font_size, 17, 10, 36),
        '--footer-link-font-size': pixelValue(footer_settings.link_font_size, 18, 10, 36),
        '--footer-bottom-font-size': pixelValue(footer_settings.bottom_font_size, 16, 10, 30),
        '--footer-social-size': pixelValue(footer_settings.social_icon_size, 40, 24, 90),
        '--footer-column-gap': pixelValue(footer_settings.column_gap, 44, 0, 140),
    };

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
                    'X-XSRF-TOKEN': csrfToken(),
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
        <footer
            className="oshe-footer"
            style={footerStyleVars}
        >
            <div className="oshe-footer-overlay">
                <div className="oshe-footer-main mx-auto max-w-[1660px] px-5 sm:px-8">
                    <div className="oshe-footer-grid grid gap-8 md:grid-cols-2 xl:gap-0">
                        <section className="oshe-footer-column oshe-footer-column-quick">
                            <h2 className="oshe-footer-heading">{footer_settings.quick_links_title || 'Quick Links'}</h2>
                            <ul className="oshe-footer-quick-list">
                                {quickLinks.map((link) => (
                                    <li key={`${link.label}-${link.href}`}>
                                        <Link href={withAppUrl(link.href)} className="oshe-footer-link">
                                            <ChevronRightIcon className="text-[#ffd51f]" />
                                            <span>{link.label}</span>
                                        </Link>
                                    </li>
                                ))}
                            </ul>
                        </section>

                        <section className="oshe-footer-column oshe-footer-column-contact xl:border-l xl:border-white/55">
                            <h2 className="oshe-footer-heading">{footer_settings.contact_title || 'Contact Us'}</h2>
                            <div className="oshe-footer-contact-list text-white">
                                {footer_settings.address && (
                                    <div className="oshe-footer-contact-item">
                                        <MapPinIcon className="oshe-footer-contact-icon" />
                                        <p>{String(footer_settings.address).split(/\r?\n/).map((line, index) => (
                                            <React.Fragment key={`${line}-${index}`}>
                                                {line}{index < String(footer_settings.address).split(/\r?\n/).length - 1 && <br />}
                                            </React.Fragment>
                                        ))}</p>
                                    </div>
                                )}
                                {footer_settings.phone && (
                                    <a href={`tel:${footer_settings.phone}`} className="oshe-footer-contact-item">
                                        <PhoneIcon className="oshe-footer-contact-icon" />
                                        <span>{footer_settings.phone}</span>
                                    </a>
                                )}
                                {footer_settings.email && (
                                    <a href={`mailto:${footer_settings.email}`} className="oshe-footer-contact-item">
                                        <EnvelopeIcon className="oshe-footer-contact-icon" />
                                        <span>{footer_settings.email}</span>
                                    </a>
                                )}
                                {footer_settings.website && (
                                    <a href={withAppUrl(/^https?:\/\//i.test(footer_settings.website) ? footer_settings.website : `https://${footer_settings.website}`)} className="oshe-footer-contact-item" target="_blank" rel="noreferrer">
                                        <GlobeAltIcon className="oshe-footer-contact-icon" />
                                        <span>{footer_settings.website}</span>
                                    </a>
                                )}
                            </div>
                        </section>

                        <section className="oshe-footer-column oshe-footer-column-subscribe xl:border-l xl:border-white/55">
                            <h2 className="oshe-footer-heading">{footer_settings.subscription_title || 'Email Subscription'}</h2>
                            <p className="oshe-footer-subscription-text text-white">
                                {footer_settings.subscription_description || 'Stay updated with our latest news and initiatives.'}
                            </p>
                            <form onSubmit={handleSubscribe} className="oshe-footer-subscribe-form flex flex-col gap-2 sm:flex-row">
                                <input
                                    type="email"
                                    name="email"
                                    value={email}
                                    onChange={(e) => setEmail(e.target.value)}
                                    placeholder="Enter your email"
                                    className="oshe-footer-email-input min-w-0 flex-1 border border-white/40 bg-white font-medium text-slate-800 outline-none placeholder:text-slate-500 focus:ring-4 focus:ring-yellow-300/35"
                                    required
                                />
                                <button
                                    type="submit"
                                    disabled={subscribeStatus === 'sending'}
                                    className="oshe-footer-subscribe-button bg-[#ffd51f] font-extrabold text-slate-950 transition hover:bg-yellow-300 disabled:cursor-not-allowed disabled:opacity-70"
                                >
                                    {subscribeStatus === 'sending' ? 'Submitting...' : (footer_settings.subscribe_button_text || 'Subscribe')}
                                </button>
                            </form>
                            {subscribeStatus !== 'idle' && (
                                <p className={`mt-3 text-sm font-bold ${subscribeStatus === 'success' ? 'text-emerald-200' : 'text-red-200'}`}>
                                    {subscribeMessage}
                                </p>
                            )}
                        </section>

                        <section className="oshe-footer-column oshe-footer-column-follow xl:border-l xl:border-white/55">
                            <h2 className="oshe-footer-heading">{footer_settings.follow_title || 'Follow Us'}</h2>
                            <div className="oshe-footer-social-list flex flex-wrap">
                                {socialLinks.map((link) => {
                                    const Icon = socialIcons[link.type] || GlobeAltIcon;
                                    return (
                                        <a
                                            key={`${link.type}-${link.href}`}
                                            href={withAppUrl(link.href)}
                                            target={link.href === '#' ? undefined : '_blank'}
                                            rel={link.href === '#' ? undefined : 'noreferrer'}
                                            aria-label={link.label}
                                            className={`oshe-social oshe-social-${link.type}`}
                                        >
                                            <Icon className="h-6 w-6" />
                                        </a>
                                    );
                                })}
                            </div>
                        </section>
                    </div>
                </div>

                <div className="oshe-footer-bottom border-t border-white/55 px-5 text-center font-medium text-white">
                    <div className="mx-auto flex max-w-[1660px] items-center justify-center">
                        <p>{footer_settings.copyright_text || `© ${new Date().getFullYear()} OSHE Foundation. All Rights Reserved.`}</p>
                    </div>
                </div>
            </div>

            <style>{`
                .oshe-footer {
                    background-size: cover;
                    background-position: center 62%;
                    color: #ffffff;
                    font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
                }

                .oshe-footer-overlay {
                    background:
                        linear-gradient(90deg, rgba(46, 75, 152, 0.62), rgba(66, 100, 178, 0.56)),
                        rgba(38, 65, 140, 0.5);
                    backdrop-filter: saturate(1.1);
                }

                .oshe-footer-main {
                    height: 330px;
                    display: flex;
                    align-items: center;
                    overflow: hidden;
                }

                .oshe-footer-grid {
                    width: 100%;
                }

                .oshe-footer-main > .grid {
                    align-items: start;
                }

                .oshe-footer-column {
                    min-height: 235px;
                }

                .oshe-footer-column-quick {
                    padding-left: 4px;
                    padding-right: 28px;
                }

                .oshe-footer-column-contact {
                    padding-left: 48px;
                    padding-right: 34px;
                }

                .oshe-footer-column-subscribe {
                    padding-left: 44px;
                    padding-right: 32px;
                }

                .oshe-footer-column-follow {
                    padding-left: 46px;
                }

                .oshe-footer-bottom {
                    height: 58px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-size: 16px;
                    line-height: 1.35;
                }

                .oshe-footer-heading {
                    position: relative;
                    color: #ffd51f;
                    font-size: 21px;
                    font-weight: 950;
                    line-height: 1.15;
                    text-transform: uppercase;
                    letter-spacing: 0;
                }

                .oshe-footer-heading::after {
                    content: "";
                    display: block;
                    width: 50px;
                    height: 3px;
                    margin-top: 12px;
                    border-radius: 999px;
                    background: #ffd51f;
                    box-shadow: 0 0 14px rgba(255, 213, 31, 0.45);
                }

                .oshe-footer-quick-list {
                    display: grid;
                    gap: 14px;
                    margin-top: 34px;
                }

                .oshe-footer-link {
                    display: inline-flex;
                    align-items: center;
                    gap: 14px;
                    color: #ffffff;
                    font-size: 18px;
                    font-weight: 800;
                    line-height: 1.25;
                    transition: color 180ms ease, transform 180ms ease;
                }

                .oshe-footer-link svg {
                    width: 14px;
                    height: 14px;
                    stroke-width: 4;
                }

                .oshe-footer-link:hover {
                    color: #ffd51f;
                    transform: translateX(3px);
                }

                .oshe-footer-contact-list {
                    display: grid;
                    gap: 14px;
                    margin-top: 24px;
                }

                .oshe-footer-contact-item {
                    display: flex;
                    align-items: flex-start;
                    gap: 15px;
                    color: #ffffff;
                    font-size: 17px;
                    font-weight: 500;
                    line-height: 1.5;
                    min-width: 0;
                }

                .oshe-footer-contact-item span,
                .oshe-footer-contact-item p {
                    min-width: 0;
                    overflow-wrap: normal;
                    word-break: normal;
                }

                .oshe-footer-contact-icon {
                    margin-top: 4px;
                    width: 19px;
                    height: 19px;
                    flex: 0 0 auto;
                    color: #ffd51f;
                }

                .oshe-footer-subscription-text {
                    max-width: 300px;
                    margin-top: 28px;
                    font-size: 17px;
                    font-weight: 500;
                    line-height: 1.45;
                }

                .oshe-footer-subscribe-form {
                    margin-top: 38px;
                }

                .oshe-footer-email-input {
                    width: 210px;
                    height: 46px;
                    border-radius: 9px;
                    padding: 0 16px;
                    font-size: 16px;
                    line-height: 1;
                }

                .oshe-footer-subscribe-button {
                    width: 112px;
                    height: 44px;
                    border-radius: 8px;
                    font-size: 15px;
                    line-height: 1;
                }

                .oshe-footer-social-list {
                    gap: 12px;
                    margin-top: 74px;
                }

                .oshe-social {
                    display: inline-grid;
                    place-items: center;
                    width: 40px;
                    height: 40px;
                    border-radius: 8px;
                    background: #ffffff;
                    color: #1f2937;
                    box-shadow: 0 10px 22px rgba(0, 0, 0, 0.12);
                    transition: transform 180ms ease, box-shadow 180ms ease;
                }

                .oshe-social svg {
                    width: 24px;
                    height: 24px;
                }

                .oshe-social:hover {
                    transform: translateY(-3px);
                    box-shadow: 0 15px 26px rgba(0, 0, 0, 0.2);
                }

                .oshe-social-facebook {
                    background: #315ded;
                    color: #ffffff;
                }

                .oshe-social-youtube {
                    color: #ff0000;
                }

                .oshe-social-instagram {
                    color: #e1306c;
                }

                .oshe-social-linkedin {
                    color: #0a66c2;
                }

                @media (max-width: 1279px) {
                    .oshe-footer-main {
                        height: auto;
                        min-height: 144px;
                        padding-top: 28px;
                        padding-bottom: 28px;
                        overflow: visible;
                    }

                    .oshe-footer-bottom {
                        height: auto;
                        min-height: 44px;
                        padding-top: 12px;
                        padding-bottom: 12px;
                        font-size: 15px;
                    }

                    .oshe-footer-column {
                        min-height: 0;
                    }

                    .oshe-footer-column-quick,
                    .oshe-footer-column-contact,
                    .oshe-footer-column-subscribe,
                    .oshe-footer-column-follow {
                        padding-left: 0;
                        padding-right: 0;
                    }

                    .oshe-footer-heading {
                        font-size: 22px;
                    }

                    .oshe-footer-heading::after {
                        width: 54px;
                        height: 3px;
                        margin-top: 11px;
                    }

                    .oshe-footer-quick-list,
                    .oshe-footer-contact-list {
                        gap: 14px;
                        margin-top: 24px;
                    }

                    .oshe-footer-link {
                        gap: 12px;
                        font-size: 18px;
                    }

                    .oshe-footer-contact-item {
                        gap: 12px;
                        font-size: 17px;
                    }

                    .oshe-footer-contact-item span,
                    .oshe-footer-contact-item p {
                        overflow-wrap: anywhere;
                    }

                    .oshe-footer-contact-icon {
                        width: 19px;
                        height: 19px;
                        margin-top: 4px;
                    }

                    .oshe-footer-subscription-text {
                        max-width: 100%;
                        margin-top: 24px;
                        font-size: 17px;
                    }

                    .oshe-footer-subscribe-form,
                    .oshe-footer-social-list {
                        margin-top: 24px;
                    }

                    .oshe-footer-email-input,
                    .oshe-footer-subscribe-button {
                        width: auto;
                        height: 46px;
                        font-size: 16px;
                    }

                    .oshe-social {
                        width: 42px;
                        height: 42px;
                    }

                    .oshe-social svg {
                        width: 24px;
                        height: 24px;
                    }
                }

                @media (min-width: 1280px) {
                    .oshe-footer-grid {
                        grid-template-columns:
                            minmax(190px, 0.82fr)
                            minmax(330px, 1.2fr)
                            minmax(320px, 1.08fr)
                            minmax(210px, 0.9fr);
                    }
                }

                @media (min-width: 1280px) and (max-width: 1535px) {
                    .oshe-footer-main {
                        height: 300px;
                    }

                    .oshe-footer-column {
                        min-height: 210px;
                    }

                    .oshe-footer-column-quick {
                        padding-right: 20px;
                    }

                    .oshe-footer-column-contact {
                        padding-left: 34px;
                        padding-right: 24px;
                    }

                    .oshe-footer-column-subscribe {
                        padding-left: 32px;
                        padding-right: 22px;
                    }

                    .oshe-footer-column-follow {
                        padding-left: 32px;
                    }

                    .oshe-footer-heading {
                        font-size: 19px;
                    }

                    .oshe-footer-quick-list {
                        gap: 12px;
                        margin-top: 27px;
                    }

                    .oshe-footer-link {
                        font-size: 16px;
                        gap: 11px;
                    }

                    .oshe-footer-contact-list {
                        gap: 12px;
                    }

                    .oshe-footer-contact-item {
                        gap: 12px;
                        font-size: 15px;
                    }

                    .oshe-footer-contact-icon {
                        width: 17px;
                        height: 17px;
                    }

                    .oshe-footer-subscription-text {
                        font-size: 15px;
                    }

                    .oshe-footer-subscribe-form {
                        margin-top: 30px;
                    }

                    .oshe-footer-email-input {
                        width: 170px;
                        height: 42px;
                        font-size: 15px;
                    }

                    .oshe-footer-subscribe-button {
                        width: 98px;
                        height: 40px;
                        font-size: 14px;
                    }

                    .oshe-footer-social-list {
                        margin-top: 58px;
                    }

                    .oshe-social {
                        width: 36px;
                        height: 36px;
                    }

                    .oshe-social svg {
                        width: 21px;
                        height: 21px;
                    }

                    .oshe-footer-bottom {
                        height: 52px;
                        font-size: 15px;
                    }
                }
            `}</style>
        </footer>
    );
};

export default Footer;
