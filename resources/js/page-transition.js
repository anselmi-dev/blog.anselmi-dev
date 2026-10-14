import gsap from 'gsap';

/**
 * Transición entre páginas con wire:navigate.
 *
 * Livewire cambia la página apenas tiene el HTML y no espera animaciones, así
 * que la salida se hace interceptando livewire:navigate: se cancela, se
 * desvanece el contenido ([data-page-transition]) y recién ahí se navega con
 * Livewire.navigate(). Al terminar (livewire:navigated) el contenido nuevo
 * entra desde abajo. Atrás/adelante del navegador no espera la salida.
 */
const OUT = { autoAlpha: 0, y: -10, duration: 0.2, ease: 'power1.in' };
const IN_FROM = { autoAlpha: 0, y: 14 };
const IN_TO = { autoAlpha: 1, y: 0, duration: 0.45, ease: 'power2.out', clearProps: 'opacity,visibility,transform' };

/** Si la navegación falla y la página no cambia, se vuelve a mostrar. */
const SAFETY_TIMEOUT = 5000;

let bound = false;
let skipNextIntercept = false;
let playInOnNavigated = false;
let safetyTimer = null;

function prefersReducedMotion() {
    return window.matchMedia('(prefers-reduced-motion: reduce)').matches;
}

function targets() {
    return [...document.querySelectorAll('[data-page-transition]')];
}

function isSamePage(url) {
    return url.pathname === window.location.pathname && url.search === window.location.search;
}

function restore() {
    gsap.to(targets(), { ...IN_TO, duration: 0.2 });
}

export function initPageTransition() {
    if (bound) {
        return;
    }

    bound = true;

    document.addEventListener('livewire:navigate', (event) => {
        if (skipNextIntercept) {
            skipNextIntercept = false;

            return;
        }

        const { url, history } = event.detail ?? {};
        const elements = targets();

        if (history || !url || !window.Livewire?.navigate || prefersReducedMotion() || elements.length === 0 || isSamePage(url)) {
            playInOnNavigated = !prefersReducedMotion();

            return;
        }

        event.preventDefault();
        playInOnNavigated = true;

        gsap.to(elements, {
            ...OUT,
            overwrite: true,
            onComplete: () => {
                skipNextIntercept = true;
                clearTimeout(safetyTimer);
                safetyTimer = setTimeout(restore, SAFETY_TIMEOUT);
                window.Livewire.navigate(url.toString());
            },
        });
    });

    document.addEventListener('livewire:navigated', () => {
        clearTimeout(safetyTimer);

        if (!playInOnNavigated) {
            return;
        }

        playInOnNavigated = false;
        gsap.fromTo(targets(), IN_FROM, IN_TO);
    });
}
