import { HomeIcon, ChevronRightIcon } from '@heroicons/react/24/solid';

export default function Breadcrumb({ title = '', subtitle = '', summary = '' }) {
    return (
        <section>
            <div className="bg-[#ececef]">
                <div className="mx-auto max-w-10/12">
                    <div className="inline-flex items-center gap-2 bg-[#f7f7f8] px-5 py-3 text-sm font-semibold text-[#25004f] [clip-path:polygon(0_0,90%_0,100%_50%,90%_100%,0_100%)] sm:px-6 sm:py-4 sm:[clip-path:polygon(0_0,92%_0,100%_50%,92%_100%,0_100%)]">
                        <HomeIcon className="h-4 w-4" />
                        <ChevronRightIcon className="h-3 w-3" />
                        <span className="max-w-[190px] truncate sm:max-w-none">{title}</span>
                    </div>
                </div>
            </div>
        </section>
    );
}
