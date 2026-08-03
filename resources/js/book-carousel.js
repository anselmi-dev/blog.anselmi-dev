import gsap from 'gsap';

function prefersReducedMotion() {
    return window.matchMedia('(prefers-reduced-motion: reduce)').matches;
}

function parseNumber(value, fallback) {
    const n = Number.parseFloat(value ?? '');
    return Number.isFinite(n) ? n : fallback;
}

/**
 * Carrusel suave de libros: [data-book-carousel] + [data-book-slide] + [data-book-dot].
 */
export function initBookCarousels(root = document) {
    root.querySelectorAll('[data-book-carousel]').forEach((el) => {
        if (el.dataset.carouselBound === 'true') {
            return;
        }

        const slides = [...el.querySelectorAll('[data-book-slide]')];
        if (slides.length < 2) {
            return;
        }

        el.dataset.carouselBound = 'true';

        const dots = [...el.querySelectorAll('[data-book-dot]')];
        const intervalMs = parseNumber(el.dataset.carouselInterval, 4500);
        let index = 0;
        let timer = null;
        let animating = false;

        const setDots = (active) => {
            dots.forEach((dot, i) => {
                const isActive = i === active;
                dot.classList.toggle('bg-white', isActive);
                dot.classList.toggle('bg-white/35', ! isActive);
                dot.setAttribute('aria-current', isActive ? 'true' : 'false');
            });
        };

        gsap.set(slides, { autoAlpha: 0, y: 12 });
        gsap.set(slides[0], { autoAlpha: 1, y: 0 });
        setDots(0);

        const goTo = (nextIndex) => {
            if (animating || nextIndex === index) {
                return;
            }

            const prev = slides[index];
            const next = slides[nextIndex];
            index = nextIndex;
            animating = true;
            setDots(index);

            gsap
                .timeline({
                    defaults: { ease: 'power3.out' },
                    onComplete: () => {
                        animating = false;
                    },
                })
                .to(prev, { autoAlpha: 0, y: -14, duration: 0.45, ease: 'power2.in' }, 0)
                .fromTo(
                    next,
                    { autoAlpha: 0, y: 18 },
                    { autoAlpha: 1, y: 0, duration: 0.6 },
                    0.12,
                );
        };

        const next = () => goTo((index + 1) % slides.length);
        const prev = () => goTo((index - 1 + slides.length) % slides.length);

        const start = () => {
            stop();
            if (prefersReducedMotion()) {
                return;
            }
            timer = window.setInterval(next, intervalMs);
        };

        const stop = () => {
            if (timer) {
                window.clearInterval(timer);
                timer = null;
            }
        };

        const restart = () => {
            stop();
            start();
        };

        el.addEventListener('mouseenter', stop);
        el.addEventListener('focusin', stop);
        el.addEventListener('mouseleave', start);
        el.addEventListener('focusout', (event) => {
            if (! el.contains(event.relatedTarget)) {
                start();
            }
        });

        el.querySelector('[data-book-prev]')?.addEventListener('click', () => {
            prev();
            restart();
        });

        el.querySelector('[data-book-next]')?.addEventListener('click', () => {
            next();
            restart();
        });

        dots.forEach((dot, i) => {
            dot.addEventListener('click', () => {
                goTo(i);
                restart();
            });
        });

        const visibility = new IntersectionObserver(
            (entries) => {
                if (entries[0]?.isIntersecting) {
                    start();
                } else {
                    stop();
                }
            },
            { threshold: 0.25 },
        );

        visibility.observe(el);

        if (prefersReducedMotion()) {
            gsap.set(slides, { clearProps: 'all' });
            gsap.set(slides[0], { autoAlpha: 1 });
            slides.slice(1).forEach((slide) => {
                slide.hidden = true;
            });
        }
    });
}
