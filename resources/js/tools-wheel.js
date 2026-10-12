const controllers = new Set();

function prefersReducedMotion() {
    return window.matchMedia('(prefers-reduced-motion: reduce)').matches;
}

function clamp(value, min, max) {
    return Math.min(max, Math.max(min, value));
}

function parseJars(section) {
    try {
        const jars = JSON.parse(section.dataset.toolsWheelJars || '[]');

        return Array.isArray(jars) ? jars : [];
    } catch {
        return [];
    }
}

/**
 * Rueda de frascos de /servicios/herramientas.
 *
 * [data-tools-wheel] es una sección alta con un escenario sticky
 * ([data-tools-wheel-stage]). El scroll dentro de la sección se traduce en un
 * índice (0 → n-1) y la rueda gira para dejar ese frasco arriba. Arrastrar con
 * mouse o dedo, las flechas y los botones prev/next mueven el scroll, así el
 * scroll es la única fuente de verdad.
 *
 * Los frascos se dibujan en 3D con three.js (tools-jars-3d.js, carga diferida).
 * Si WebGL no está disponible se usa la versión HTML/CSS ([data-tools-wheel-ring]).
 * Al hacer clic en un frasco se abre el modo detalle ([data-tools-wheel-panel]).
 */
export function initToolsWheels(root = document) {
    root.querySelectorAll('[data-tools-wheel]').forEach((section) => {
        if (section.dataset.wheelBound === 'true') {
            return;
        }

        section.dataset.wheelBound = 'true';

        const stage = section.querySelector('[data-tools-wheel-stage]');
        const ring = section.querySelector('[data-tools-wheel-ring]');
        const canvasContainer = section.querySelector('[data-tools-wheel-canvas]');
        const items = [...section.querySelectorAll('[data-tools-wheel-item]')];
        const details = [...section.querySelectorAll('[data-tools-wheel-detail]')];
        const counter = section.querySelector('[data-tools-wheel-counter]');
        const footer = section.querySelector('[data-tools-wheel-footer]');
        const panel = section.querySelector('[data-tools-wheel-panel]');
        const jars = parseJars(section);
        const count = items.length;

        if (! stage || ! ring || count === 0) {
            announceReady();

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
        let scene3d = null;
        let focusedIndex = -1;
        let pendingFocus = null;

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

            scene3d?.resize();
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

        // ---- Modo detalle -------------------------------------------------

        const fillPanel = (index) => {
            const jar = jars[index];

            if (! panel || ! jar) {
                return;
            }

            panel.querySelector('[data-panel-index]').textContent = `${String(index + 1).padStart(2, '0')} / ${String(count).padStart(2, '0')}`;
            panel.querySelector('[data-panel-category]').textContent = jar.category ?? '';
            panel.querySelector('[data-panel-name]').textContent = jar.name ?? '';
            panel.querySelector('[data-panel-tagline]').textContent = jar.tagline ?? '';

            const link = panel.querySelector('[data-panel-link]');
            link.hidden = ! jar.url;
            link.href = jar.url || '#';
        };

        const openFocus = (index) => {
            if (! scene3d || ! panel) {
                return;
            }

            focusedIndex = index;
            fillPanel(index);
            scene3d.focus(index);
            section.dataset.focused = 'true';
            panel.hidden = false;
            footer?.classList.add('opacity-0', 'pointer-events-none');
            panel.animate?.(
                [{ opacity: 0, translate: '0 16px' }, { opacity: 1, translate: '0 0' }],
                { duration: reduced ? 1 : 450, delay: reduced ? 0 : 300, easing: 'cubic-bezier(.2,.7,.2,1)', fill: 'backwards' },
            );
            panel.querySelector('[data-tools-wheel-close]')?.focus({ preventScroll: true });
        };

        const closeFocus = ({ restoreFocus = true } = {}) => {
            if (focusedIndex < 0) {
                return;
            }

            focusedIndex = -1;
            scene3d?.blur();
            delete section.dataset.focused;

            if (panel) {
                panel.hidden = true;
            }

            footer?.classList.remove('opacity-0', 'pointer-events-none');

            if (restoreFocus) {
                stage.focus({ preventScroll: true });
            }
        };

        // ---- Render de la versión HTML/CSS y estado ------------------------

        const render = () => {
            frame = null;
            current += (target - current) * (reduced ? 1 : 0.12);

            if (Math.abs(target - current) < 0.001) {
                current = target;
            }

            if (! scene3d) {
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
            } else if (reduced) {
                scene3d.invalidate();
            }

            setActive(Math.round(current));

            if (pendingFocus !== null && Math.abs(current - pendingFocus) < 0.02) {
                const index = pendingFocus;
                pendingFocus = null;
                openFocus(index);
            }

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

            // Scrollear con el detalle abierto lo cierra.
            if (focusedIndex >= 0 && Math.abs(target - focusedIndex) > 0.25) {
                closeFocus({ restoreFocus: false });
            }

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

        // ---- Interacción ---------------------------------------------------

        // Arrastre horizontal (mouse o dedo) → scroll vertical de la sección.
        let dragging = false;
        let lastX = 0;
        let downX = 0;
        let downY = 0;
        let moved = false;

        stage.addEventListener('pointerdown', (event) => {
            if (event.button !== 0 || event.target.closest('a, button, [data-tools-wheel-panel]')) {
                return;
            }

            dragging = true;
            moved = false;
            lastX = downX = event.clientX;
            downY = event.clientY;
            stage.setPointerCapture(event.pointerId);
        }, { signal });

        stage.addEventListener('pointermove', (event) => {
            const rect = stage.getBoundingClientRect();
            parallax = ((event.clientX - rect.left) / rect.width - 0.5) * -24;
            requestRender();

            if (scene3d && ! dragging && focusedIndex < 0) {
                stage.style.cursor = scene3d.pick(event.clientX, event.clientY) >= 0 ? 'pointer' : '';
            }

            if (! dragging || focusedIndex >= 0) {
                return;
            }

            if (Math.abs(event.clientX - downX) > 5 || Math.abs(event.clientY - downY) > 5) {
                moved = true;
                stage.classList.add('cursor-grabbing');
            }

            const dx = event.clientX - lastX;
            lastX = event.clientX;
            const pixelsPerItem = (radius * Math.sin((step * Math.PI) / 180)) || 1;
            const delta = (-dx / pixelsPerItem) * (scrollRange() / Math.max(1, count - 1));

            // behavior "instant": el <html> tiene scroll-smooth y cada movimiento cortaría al anterior.
            window.scrollTo({ top: window.scrollY + delta, behavior: 'instant' });
        }, { signal });

        const endDrag = (event) => {
            const wasClick = dragging && ! moved;
            dragging = false;
            stage.classList.remove('cursor-grabbing');

            if (! wasClick || ! scene3d || event.type !== 'pointerup') {
                return;
            }

            const index = scene3d.pick(event.clientX, event.clientY);

            if (index < 0) {
                closeFocus();
            } else if (index === focusedIndex) {
                closeFocus();
            } else if (index === Math.round(target)) {
                openFocus(index);
            } else {
                closeFocus({ restoreFocus: false });
                pendingFocus = index;
                scrollToIndex(index);
            }
        };

        stage.addEventListener('pointerup', endDrag, { signal });
        stage.addEventListener('pointercancel', endDrag, { signal });
        stage.addEventListener('pointerleave', () => {
            parallax = 0;
            requestRender();
        }, { signal });

        stage.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && focusedIndex >= 0) {
                event.preventDefault();
                closeFocus();
            } else if (event.key === 'Enter' && event.target === stage && scene3d) {
                event.preventDefault();
                openFocus(Math.round(target));
            } else if (event.key === 'ArrowRight' || event.key === 'ArrowDown') {
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

        section.querySelectorAll('[data-tools-wheel-open]').forEach((button) => {
            button.addEventListener('click', () => openFocus(Number(button.dataset.toolsWheelOpen)), { signal });
        });

        section.querySelector('[data-tools-wheel-close]')?.addEventListener('click', () => closeFocus(), { signal });

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

        // ---- Escena 3D (carga diferida) -----------------------------------

        if (canvasContainer && jars.length === count) {
            import('./tools-jars-3d')
                .then(({ createJarScene }) => {
                    if (signal.aborted) {
                        return;
                    }

                    scene3d = createJarScene(canvasContainer, jars, {
                        getState: () => ({ current, parallax }),
                        reducedMotion: reduced,
                    });

                    if (scene3d) {
                        section.dataset.wheel3d = 'true';
                        section.querySelectorAll('[data-tools-wheel-open]').forEach((button) => {
                            button.hidden = false;
                        });
                    }
                })
                .catch(() => {})
                .finally(announceReady);
        } else {
            announceReady();
        }

        controllers.add(abort);
        signal.addEventListener('abort', () => {
            window.clearTimeout(snapTimer);

            if (frame !== null) {
                cancelAnimationFrame(frame);
            }

            scene3d?.destroy();
            scene3d = null;
            controllers.delete(abort);
        });
    });
}

function announceReady() {
    document.dispatchEvent(new CustomEvent('tools-wheel:ready'));
}

// Al navegar con wire:navigate la sección desaparece: soltamos listeners y la escena 3D.
document.addEventListener('livewire:navigating', () => {
    controllers.forEach((controller) => controller.abort());
});
