import gsap from 'gsap';

const PRESETS = {
    fade: { autoAlpha: 0 },
    'fade-up': { autoAlpha: 0, y: 28 },
    'fade-down': { autoAlpha: 0, y: -18 },
    'fade-scale': { autoAlpha: 0, scale: 0.96 },
};

const observers = new WeakMap();

function prefersReducedMotion() {
    return window.matchMedia('(prefers-reduced-motion: reduce)').matches;
}

function parseNumber(value, fallback) {
    const n = Number.parseFloat(value ?? '');
    return Number.isFinite(n) ? n : fallback;
}

function getPreset(name) {
    return PRESETS[name] ?? PRESETS['fade-up'];
}

function hasOpenOverlay() {
    return Boolean(
        document.querySelector(
            '[aria-modal="true"], dialog[open], [data-reveal-pause], .fixed.inset-0[role="dialog"]',
        ),
    );
}

/**
 * Anima un contenedor [data-reveal] y sus [data-reveal-item] (stagger).
 *
 * Atributos:
 * - data-reveal="fade|fade-up|fade-down|fade-scale" (default: fade-up)
 * - data-reveal-stagger="0.08"
 * - data-reveal-delay="0.1"
 * - data-reveal-duration="0.8"
 * - data-reveal-scroll — anima al entrar en viewport; se reinicia al salir
 * - data-reveal-once — con scroll: solo anima la primera vez
 * - data-reveal-threshold="0.12" — % visible para disparar (solo scroll)
 */
function revealElement(el) {
    if (el.dataset.revealBound === 'true') {
        return;
    }

    const presetName = el.getAttribute('data-reveal') || 'fade-up';
    const from = getPreset(presetName);
    const stagger = parseNumber(el.dataset.revealStagger, 0.08);
    const delay = parseNumber(el.dataset.revealDelay, 0);
    const duration = parseNumber(el.dataset.revealDuration, 0.8);
    const useScroll = el.hasAttribute('data-reveal-scroll');
    const once = el.hasAttribute('data-reveal-once');
    const threshold = parseNumber(el.dataset.revealThreshold, 0.12);

    const getTargets = () => {
        const items = [...el.querySelectorAll('[data-reveal-item]')].filter(
            (item) => item.closest('[data-reveal]') === el,
        );

        return items.length > 0 ? items : [el];
    };

    el.dataset.revealBound = 'true';

    // Contenedor visible de inmediato; animamos sus ítems (o el propio nodo).
    if (el.querySelector('[data-reveal-item]')) {
        gsap.set(el, { autoAlpha: 1 });
    }

    el.dataset.revealReady = 'true';

    const showTween = {
        autoAlpha: 1,
        x: 0,
        y: 0,
        scale: 1,
        duration,
        delay,
        ease: 'power3.out',
    };

    const showFinal = () => {
        const targets = getTargets();
        gsap.killTweensOf(targets);
        gsap.set(targets, { autoAlpha: 1, x: 0, y: 0, scale: 1 });
        el.dataset.revealDone = 'true';
    };

    const play = () => {
        const targets = getTargets();
        gsap.killTweensOf(targets);
        gsap.set(targets, { ...from, force3D: true });
        gsap.to(targets, {
            ...showTween,
            stagger: targets.length > 1 && targets[0] !== el ? stagger : 0,
        });
        el.dataset.revealDone = 'true';
    };

    const reset = () => {
        // No ocultar si hay un modal/lightbox abierto (p. ej. galería).
        if (hasOpenOverlay()) {
            return;
        }

        const targets = getTargets();
        gsap.killTweensOf(targets);
        gsap.set(targets, { ...from, force3D: true });
        el.dataset.revealDone = 'false';
    };

    if (! useScroll) {
        gsap.set(getTargets(), { ...from, force3D: true });
        const targets = getTargets();
        gsap.to(targets, {
            ...showTween,
            stagger: targets.length > 1 && targets[0] !== el ? stagger : 0,
            clearProps: 'transform',
            onComplete: () => {
                el.dataset.revealDone = 'true';
            },
        });
        return;
    }

    // Overlay abierto o ya revelado: mantener visible (evita flash al abrir lightbox).
    if (hasOpenOverlay() || el.dataset.revealDone === 'true') {
        showFinal();
    } else {
        gsap.set(getTargets(), { ...from, force3D: true });
    }

    const observer = new IntersectionObserver(
        (entries) => {
            const entry = entries[0];
            if (! entry) {
                return;
            }

            // Modales/lightbox: no resetear ni re-disparar (evita que la grilla “desaparezca”).
            if (hasOpenOverlay()) {
                return;
            }

            if (entry.isIntersecting) {
                // Evita un flash hide→show si ya está visible y el layout solo “tiembla”.
                if (el.dataset.revealDone === 'true') {
                    return;
                }

                play();
                if (once) {
                    observer.disconnect();
                    observers.delete(el);
                }
                return;
            }

            // Al salir del viewport (subir o bajar), vuelve al estado inicial.
            if (! once) {
                reset();
            }
        },
        {
            threshold,
            rootMargin: '0px 0px 10% 0px',
        },
    );

    observers.set(el, observer);
    observer.observe(el);
}

/**
 * Cuenta de 0 al valor de data-count al entrar en viewport.
 *
 * - data-count="12"
 * - data-count-prefix="+" (opcional)
 * - data-count-suffix="" (opcional)
 * - data-count-duration="1.2" (opcional)
 * - data-count-once — solo la primera vez
 */
function countElement(el) {
    if (el.dataset.countBound === 'true') {
        return;
    }

    const target = parseNumber(el.dataset.count, NaN);
    if (! Number.isFinite(target)) {
        return;
    }

    const prefix = el.dataset.countPrefix ?? '';
    const suffix = el.dataset.countSuffix ?? '';
    const duration = parseNumber(el.dataset.countDuration, 1.2);
    const once = el.hasAttribute('data-count-once');
    const state = { value: 0 };

    const render = (value) => {
        el.textContent = `${prefix}${Math.round(value)}${suffix}`;
    };

    el.dataset.countBound = 'true';
    render(0);

    const play = () => {
        gsap.killTweensOf(state);
        state.value = 0;
        render(0);
        gsap.to(state, {
            value: target,
            duration,
            ease: 'power2.out',
            onUpdate: () => render(state.value),
        });
        el.dataset.countDone = 'true';
    };

    const reset = () => {
        if (hasOpenOverlay()) {
            return;
        }

        gsap.killTweensOf(state);
        state.value = 0;
        render(0);
        el.dataset.countDone = 'false';
    };

    const observer = new IntersectionObserver(
        (entries) => {
            const entry = entries[0];
            if (! entry || hasOpenOverlay()) {
                return;
            }

            if (entry.isIntersecting) {
                if (el.dataset.countDone === 'true') {
                    return;
                }

                play();
                if (once) {
                    observer.disconnect();
                    observers.delete(el);
                }
                return;
            }

            if (! once) {
                reset();
            }
        },
        {
            threshold: 0.35,
            rootMargin: '0px 0px 10% 0px',
        },
    );

    observers.set(el, observer);
    observer.observe(el);
}

/**
 * Inicializa todas las animaciones de reveal dentro de `root`.
 */
export function initMotion(root = document) {
    const nodes = root.querySelectorAll('[data-reveal]');
    const counters = root.querySelectorAll('[data-count]');

    if (prefersReducedMotion()) {
        nodes.forEach((el) => {
            el.dataset.revealReady = 'true';
            el.dataset.revealDone = 'true';
            el.dataset.revealBound = 'true';
            gsap.set(el.querySelectorAll('[data-reveal-item]'), { clearProps: 'all' });
            gsap.set(el, { clearProps: 'all' });
        });
        counters.forEach((el) => {
            const target = parseNumber(el.dataset.count, 0);
            const prefix = el.dataset.countPrefix ?? '';
            const suffix = el.dataset.countSuffix ?? '';
            el.textContent = `${prefix}${Math.round(target)}${suffix}`;
            el.dataset.countBound = 'true';
            el.dataset.countDone = 'true';
        });
        return;
    }

    nodes.forEach((el) => revealElement(el));
    counters.forEach((el) => countElement(el));
}

export function refreshScrollTriggers() {
    // Compat: los reveals con scroll usan IntersectionObserver.
}

export { gsap };
