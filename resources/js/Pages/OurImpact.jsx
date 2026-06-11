import { useEffect, useState } from 'react';
import Root from '../component/layout/Root';
import { usePage } from '@inertiajs/react';

const counters = [
    { value: 20, suffix: '+', color: '#073c78' },
    { value: 100, suffix: '+', color: '#08753d' },
    { value: 5000, suffix: '+', color: '#073c78' },
    { value: 50, suffix: '+', color: '#08753d' },
    { value: null, label: 'Millions', color: '#073c78' },
];

function AnimatedValue({ counter, delay }) {
    const [value, setValue] = useState(0);

    useEffect(() => {
        if (counter.value === null) {
            return undefined;
        }

        let frameId;
        let startTime;
        const duration = 1500;
        const startDelay = delay;

        const tick = (timestamp) => {
            if (!startTime) {
                startTime = timestamp + startDelay;
            }

            const elapsed = Math.max(0, timestamp - startTime);
            const progress = Math.min(elapsed / duration, 1);
            const eased = 1 - Math.pow(1 - progress, 3);

            setValue(Math.round(counter.value * eased));

            if (progress < 1) {
                frameId = window.requestAnimationFrame(tick);
            }
        };

        frameId = window.requestAnimationFrame(tick);

        return () => window.cancelAnimationFrame(frameId);
    }, [counter.value, delay]);

    return (
        <span
            className="inline-flex min-w-[1.95em] items-center justify-center rounded-md bg-white/95 px-[0.08em] pb-[0.04em] text-[clamp(22px,3.05vw,56px)] font-black leading-none tracking-normal"
            style={{
                color: counter.color,
                boxShadow: '0 0 14px rgba(255,255,255,0.92)',
            }}
        >
            {counter.value === null ? counter.label : `${value}${counter.suffix}`}
        </span>
    );
}

export default function OurImpact() {
    const { impact_image } = usePage().props;

    return (
        <Root>
            <main className="bg-white">
                <section className="flex min-h-screen w-full items-center justify-center overflow-hidden bg-white">
                    <div
                        className="relative overflow-hidden"
                        style={{
                            width: 'min(100vw, calc(100vh * 1.8713))',
                            aspectRatio: '1716 / 917',
                        }}
                    >
                        <img
                            src={impact_image}
                            alt="Our Impact"
                            className="absolute inset-0 h-full w-full object-contain"
                        />
                        <div className="pointer-events-none absolute left-[7.2%] right-[7.2%] top-[45.1%] grid grid-cols-5 items-center gap-[2.7%]">
                            {counters.map((counter, index) => (
                                <div key={counter.label || counter.value} className="flex justify-center">
                                    <AnimatedValue counter={counter} delay={index * 130} />
                                </div>
                            ))}
                        </div>
                    </div>
                </section>
            </main>
        </Root>
    );
}
