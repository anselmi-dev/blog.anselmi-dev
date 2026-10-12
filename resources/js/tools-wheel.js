const controllers = new Set();

function prefersReducedMotion() {
    return window.matchMedia('(prefers-reduced-motion: reduce)').matches;
}

function clamp(value, min, max) {
    return Math.min(max, Math.max(min, value));
}

/**
 * Rueda de frascos de /servicios/herramientas.
 *
 * [data-tools-wheel] es una sección alta con un escenario sticky
 * ([data-tools-wheel-stage]). El scroll dentro de la sección se traduce en un
 * índice (0 → n-1) y la rueda ([data-tools-wheel-ring]) gira para dejar ese
 * frasco arriba. Arrastrar con mouse o dedo, las flechas y los botones
 * prev/next mueven el scroll, así el scroll es la única fuente de verdad.
 */
export function initToolsWheels(root = document) {
    root.querySelectorAll('[data-tools-wheel]').forEach((section) => {
        if (section.dataset.wheelBound === 'true') {
            return;
        }

        section.dataset.wheelBound = 'true';

        const stage = section.querySelector('[data-tools-wheel-stage]');
        const ring = section.querySelector('[data-tools-wheel-ring]');
        const items = [...section.querySelectorAll('[data-tools-wheel-item]')];
        const details = [...section.querySelectorAll('[data-tools-wheel-detail]')];
        const counter = section.querySelector('[data-tools-wheel-counter]');
        const count = items.length;

        if (! stage || ! ring || count === 0) {
            return;
        }

        const abort = new AbortController();
        const { signal } = abort;
        const reduced = prefersReducedMotion();

        let radius = 0;
        let step = 0;
        let current = 0;
        let target = 0;
        let active = -1;
        let parallax = 0;
        let frame = null;
        let snapTimer = null;

        const layout = () => {
            const width = stage.clientWidth;
            const height = stage.clientHeight;
            const jarWidth = clamp(width * 0.32, 130, 190);

            radius = clamp(width * 0.95, 620, 1300);
            step = (Math.asin(clamp((jarWidth * 1.55) / radius, 0.05, 0.9)) * 180) / Math.PI;

            stage.style.setProperty('--jar-w', `${jarWidth}px`);
            stage.style.setProperty('--jar-h', `${jarWidth * 1.45}px`);
            ring.style.top = `${height * 0.42 + radius}px`;

            items.forEach((item, index) => {
                item.style.transform = `rotate(${index * step}deg) translateY(${-radius}px)`;
            });
        };

        const scrollRange = () => Math.max(1, section.offsetHeight - window.innerHeight);

        const indexFromScroll = () => {
            const top = section.getBoundingClientRect().top;
            const progress = clamp(-top / scrollRange(), 0, 1);

            return progress * (count - 1);
        };

        const scrollToIndex = (index, behavior = 'smooth') => {
            const clamped = clamp(index, 0, count - 1);
            const sectionTop = window.scrollY + section.getBoundingClientRect().top;
            const y = sectionTop + (count > 1 ? (clamped / (count - 1)) * scrollRange() : 0);

            window.scrollTo({ top: y, behavior: reduced ? 'auto' : behavior });
        };

        const setActive = (index) => {
            if (index === active) {
                return;
            }

            active = index;

            details.forEach((detail, i) => {
                const isActive = i === index;
                detail.hidden = ! isActive;
                detail.setAttribute('aria-hidden', isActive ? 'false' : 'true');
            });

            items.forEach((item, i) => item.toggleAttribute('data-active', i === index));

            if (counter) {
                counter.textContent = `${String(index + 1).padStart(2, '0')} / ${String(count).padStart(2, '0')}`;
            }
        };

        const render = () => {
            frame = null;
            current += (target - current) * (reduced ? 1 : 0.12);

            if (Math.abs(target - current) < 0.001) {
                current = target;
            }

            ring.style.transform = `translateX(${parallax}px) rotate(${-current * step}deg)`;

            items.forEach((item, i) => {
                const distance = Math.abs(i - current);
                const scale = 1 - Math.min(distance, 3) * 0.1;
                const opacity = clamp(1.6 - distance * 0.45, 0, 1);
                const jar = item.firstElementChild;

                jar.style.transform = `scale(${scale})`;
                item.style.opacity = String(opacity);
                item.style.visibility = opacity <= 0 ? 'hidden' : 'visible';
            });

            setActive(Math.round(current));

            if (current !== target) {
                frame = requestAnimationFrame(render);
            }
        };

        const requestRender = () => {
            if (frame === null) {
                frame = requestAnimationFrame(render);
            }
        };

        const onScroll = () => {
            target = indexFromScroll();
            requestRender();

            window.clearTimeout(snapTimer);
            snapTimer = window.setTimeout(() => {
                const rect = section.getBoundingClientRect();
                const inside = rect.top <= 0 && rect.bottom >= window.innerHeight;
                const nearest = Math.round(target);

                if (inside && Math.abs(nearest - target) > 0.02) {
                    scrollToIndex(nearest);
                }
            }, 160);
        };

        // Arrastre horizontal (mouse o dedo) → scroll vertical de la sección.
        let dragging = false;
        let lastX = 0;

        stage.addEventListener('pointerdown', (event) => {
            if (event.button !== 0 || event.target.closest('a, button')) {
                return;
            }

            dragging = true;
            lastX = event.clientX;
            stage.setPointerCapture(event.pointerId);
            stage.classList.add('cursor-grabbing');
        }, { signal });

        stage.addEventListener('pointermove', (event) => {
            const rect = stage.getBoundingClientRect();
            parallax = ((event.clientX - rect.left) / rect.width - 0.5) * -24;
            requestRender();

            if (! dragging) {
                return;
            }

            const dx = event.clientX - lastX;
            lastX = event.clientX;
            const pixelsPerItem = (radius * Math.sin((step * Math.PI) / 180)) || 1;
            const delta = (-dx / pixelsPerItem) * (scrollRange() / Math.max(1, count - 1));

            // behavior "instant": el <html> tiene scroll-smooth y cada movimiento cortaría al anterior.
            window.scrollTo({ top: window.scrollY + delta, behavior: 'instant' });
        }, { signal });

        const endDrag = () => {
            dragging = false;
            stage.classList.remove('cursor-grabbing');
        };

        stage.addEventListener('pointerup', endDrag, { signal });
        stage.addEventListener('pointercancel', endDrag, { signal });
        stage.addEventListener('pointerleave', () => {
            parallax = 0;
            requestRender();
        }, { signal });

        stage.addEventListener('keydown', (event) => {
            if (event.key === 'ArrowRight' || event.key === 'ArrowDown') {
                event.preventDefault();
                scrollToIndex(Math.round(target) + 1);
            } else if (event.key === 'ArrowLeft' || event.key === 'ArrowUp') {
                event.preventDefault();
                scrollToIndex(Math.round(target) - 1);
            }
        }, { signal });

        section.querySelectorAll('[data-tools-wheel-prev]').forEach((button) => {
            button.addEventListener('click', () => scrollToIndex(Math.round(target) - 1), { signal });
        });

        section.querySelectorAll('[data-tools-wheel-next]').forEach((button) => {
            button.addEventListener('click', () => scrollToIndex(Math.round(target) + 1), { signal });
        });

        window.addEventListener('scroll', onScroll, { passive: true, signal });
        window.addEventListener('resize', () => {
            layout();
            onScroll();
        }, { signal });

        layout();
        target = indexFromScroll();
        current = target;
        render();
        section.dataset.wheelReady = 'true';

        controllers.add(abort);
        signal.addEventListener('abort', () => {
            window.clearTimeout(snapTimer);
            if (frame !== null) {
                cancelAnimationFrame(frame);
            }
            controllers.delete(abort);
        });
    });
}

// Al navegar con wire:navigate la sección desaparece: soltamos los listeners de window.
document.addEventListener('livewire:navigating', () => {
    controllers.forEach((controller) => controller.abort());
});
