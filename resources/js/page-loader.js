import gsap from 'gsap';

const MIN_DURATION_MS = 900;

function prefersReducedMotion() {
    return window.matchMedia('(prefers-reduced-motion: reduce)').matches;
}

/**
 * Pantalla de carga minimalista: [data-page-loader] con [data-page-loader-bar]
 * y [data-page-loader-value]. El porcentaje sigue a las imágenes y fuentes de
 * la página y nunca termina antes de MIN_DURATION_MS, para que se lea.
 */
export function initPageLoaders(root = document) {
    root.querySelectorAll('[data-page-loader]').forEach((loader) => {
        if (loader.dataset.loaderBound === 'true') {
            return;
        }

        loader.dataset.loaderBound = 'true';

        const bar = loader.querySelector('[data-page-loader-bar]');
        const value = loader.querySelector('[data-page-loader-value]');
        const images = [...document.querySelectorAll('main img')].filter((img) => img.loading !== 'lazy');
        // La escena 3D de herramientas avisa con "tools-wheel:ready" cuando está lista.
        const waitsForWheel = document.querySelector('[data-tools-wheel]') !== null;
        const tasks = images.length + 1 + (waitsForWheel ? 1 : 0);
        let done = 0;
        let shown = 0;
        const startedAt = performance.now();

        const markDone = () => {
            done = Math.min(tasks, done + 1);
        };

        images.forEach((img) => {
            if (img.complete) {
                markDone();
            } else {
                img.addEventListener('load', markDone, { once: true });
                img.addEventListener('error', markDone, { once: true });
            }
        });

        (document.fonts?.ready ?? Promise.resolve()).then(markDone);

        if (waitsForWheel) {
            document.addEventListener('tools-wheel:ready', markDone, { once: true });
        }

        // Por si alguna imagen externa nunca responde.
        const safety = window.setTimeout(() => {
            done = tasks;
        }, 6000);

        const finish = () => {
            window.clearTimeout(safety);
            loader.setAttribute('aria-busy', 'false');

            if (prefersReducedMotion()) {
                loader.remove();

                return;
            }

            gsap.to(loader, {
                autoAlpha: 0,
                duration: 0.6,
                ease: 'power2.inOut',
                delay: 0.15,
                onComplete: () => loader.remove(),
            });
        };

        const tick = (now) => {
            const elapsed = now - startedAt;
            const real = done / tasks;
            const timeCap = Math.min(1, elapsed / MIN_DURATION_MS);
            const target = Math.min(real, timeCap) * 100;

            shown += (target - shown) * 0.12;

            if (target === 100 && shown > 99.5) {
                shown = 100;
            }

            const rounded = Math.floor(shown);
            bar.style.transform = `scaleX(${shown / 100})`;
            value.textContent = String(rounded);
            loader.setAttribute('aria-valuenow', String(rounded));

            if (rounded >= 100) {
                finish();

                return;
            }

            requestAnimationFrame(tick);
        };

        requestAnimationFrame(tick);
    });
}
