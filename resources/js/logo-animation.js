/**
 * Logo del header: al aparecer se dibuja el trazo y después entra el relleno;
 * al pasar el mouse (o enfocar el enlace) el trazo se vuelve a dibujar.
 * Sin JS o con prefers-reduced-motion el logo se ve quieto, tal cual.
 */
const EASE = 'cubic-bezier(.65,0,.35,1)';

function draw(logo, { intro = false } = {}) {
    const fill = logo.querySelector('[data-logo-fill]');
    const stroke = logo.querySelector('[data-logo-stroke]');
    const paths = [...stroke.querySelectorAll('path')];

    if (logo.dataset.logoDrawing === 'true') {
        return;
    }

    logo.dataset.logoDrawing = 'true';

    const drawDuration = intro ? 1100 : 750;
    const delay = intro ? 250 : 0;
    const stagger = intro ? 90 : 50;
    const total = delay + drawDuration + stagger * (paths.length - 1);

    // El trazo se dibuja sobre el relleno, que se apaga y vuelve al terminar.
    stroke.animate(
        [{ opacity: 1 }, { opacity: 1, offset: 0.85 }, { opacity: 0 }],
        { duration: total + 350, easing: 'linear' },
    );

    paths.forEach((path, i) => {
        path.style.strokeDasharray = '1';
        path.animate(
            [{ strokeDashoffset: 1 }, { strokeDashoffset: 0 }],
            { duration: drawDuration, delay: delay + i * stagger, easing: EASE, fill: 'both' },
        );
    });

    const fillAnimation = fill.animate(
        intro
            ? [{ opacity: 0 }, { opacity: 0, offset: 0.7 }, { opacity: 1 }]
            : [{ opacity: 1 }, { opacity: 0.12, offset: 0.25 }, { opacity: 0.12, offset: 0.7 }, { opacity: 1 }],
        { duration: total + 200, easing: 'ease-out' },
    );

    fillAnimation.onfinish = () => {
        logo.dataset.logoDrawing = 'false';
    };
}

export function initLogoAnimation(root = document) {
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        return;
    }

    root.querySelectorAll('[data-animated-logo]').forEach((logo) => {
        if (logo.dataset.logoBound === 'true' || ! logo.animate) {
            return;
        }

        logo.dataset.logoBound = 'true';
        draw(logo, { intro: true });

        const trigger = logo.closest('a') ?? logo;
        trigger.addEventListener('pointerenter', () => draw(logo));
        trigger.addEventListener('focus', () => draw(logo));
    });
}
