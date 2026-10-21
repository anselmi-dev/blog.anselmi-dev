import gsap from 'gsap';

/**
 * Transición entre páginas con wire:navigate.
 *
 * Livewire cambia la página apenas tiene el HTML y no espera animaciones, así
 * que la salida se hace interceptando livewire:navigate: se cancela, se
 * desvanece toda la web ([data-page-transition]: header, contenido y pie) y
 * recién ahí se navega con Livewire.navigate(). Al terminar
 * (livewire:navigated) la página nueva entra desde abajo. Atrás/adelante del
 * navegador no espera la salida, pero sí anima la entrada.
 */
const OUT = { autoAlpha: 0, y: -28, scale: 0.985, filter: 'blur(6px)', duration: 0.35, ease: 'power2.in' };
const IN_FROM = { autoAlpha: 0, y: 36, scale: 0.985, filter: 'blur(6px)' };
const IN_TO = {
    autoAlpha: 1,
    y: 0,
    scale: 1,
    filter: 'blur(0px)',
    duration: 0.6,
    ease: 'power3.out',
    clearProps: 'opacity,visibility,transform,filter',
};

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

/** Escala desde el centro de lo que se ve, no del centro de toda la página. */
function viewportOrigin() {
    return `50% ${window.scrollY + window.innerHeight / 2}px`;
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
            transformOrigin: viewportOrigin(),
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
        gsap.fromTo(targets(), { ...IN_FROM, transformOrigin: viewportOrigin() }, IN_TO);
    });
}
