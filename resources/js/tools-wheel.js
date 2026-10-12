import { wrappedOffset } from './tools-wheel-math';

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
 * El carrusel no usa el scroll de la página (la rueda del mouse solo mueve la
 * página): se gira agarrándolo con el mouse o el dedo, con los botones
 * prev/next o con las flechas del teclado. La posición no tiene límites y los
 * frascos se repiten en bucle (wrappedOffset).
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
        const title = section.querySelector('[data-tools-wheel-title]');
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

        let spacing = 0;
        let current = 0;
        let target = 0;
        let active = -1;
        let parallax = 0;
        let frame = null;
        let scene3d = null;
        let focusedIndex = -1;
        let pendingFocus = null;

        // Tamaño de los frascos y alto de su centro dentro del escenario (lo usa también la escena 3D).
        const stageLayout = { jarWidth: 150, centerY: 0 };

        /**
         * Título arriba, frascos al medio y detalle abajo, bien juntos: los frascos
         * toman el alto que queda libre y, si sobra espacio, el título y el
         * detalle se acercan a ellos en vez de quedar pegados a los bordes.
         */
        const layout = () => {
            const width = stage.clientWidth;
            const height = stage.clientHeight;
            const gap = 16;
            const titleHeight = title?.offsetHeight ?? 0;
            const footerHeight = footer?.offsetHeight ?? 0;
            const free = Math.max(0, height - titleHeight - footerHeight - gap * 2);
            // 1.6 ≈ alto del frasco más el crecimiento e inclinación de los de la izquierda.
            const jarWidth = clamp(Math.min(width * 0.34, free / 1.6), 110, 230);
            const jarHeight = jarWidth * 1.6;
            const shift = Math.max(0, (free - jarHeight) / 2);

            title?.style.setProperty('translate', `0 ${shift}px`);
            footer?.style.setProperty('translate', `0 ${-shift}px`);

            stageLayout.jarWidth = jarWidth;
            stageLayout.centerY = titleHeight + shift + gap + jarHeight / 2;
            spacing = jarWidth * 1.5;

            stage.style.setProperty('--jar-w', `${jarWidth}px`);
            stage.style.setProperty('--jar-h', `${jarWidth * 1.45}px`);
            ring.style.top = `${stageLayout.centerY}px`;

            scene3d?.resize();
        };

        const wrapIndex = (value) => ((Math.round(value) % count) + count) % count;

        const goTo = (position) => {
            target = position;

            // Mover el carrusel con el detalle abierto lo cierra.
            if (focusedIndex >= 0 && Math.abs(wrappedOffset(focusedIndex, target, count)) > 0.25) {
                closeFocus({ restoreFocus: false });
            }

            requestRender();
        };

        /** Posición más cercana (yendo para el lado más corto) en la que queda centrado ese frasco. */
        const positionFor = (index) => {
            const base = Math.round(target);
            const forward = (((index - base) % count) + count) % count;

            return base + (forward > count / 2 ? forward - count : forward);
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
            panel.querySelector('[data-panel-description]').textContent = jar.description ?? '';

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
            title?.classList.add('opacity-0');
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
            title?.classList.remove('opacity-0');

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
                ring.style.transform = `translateX(${parallax}px)`;

                // Misma idea que la escena 3D: los de la derecha más chicos (más lejos),
                // los de la izquierda más grandes (más cerca).
                items.forEach((item, i) => {
                    const offset = wrappedOffset(i, current, count);
                    const depthScale = offset > 0 ? 1 / (1 + offset * 0.13) : 1 + Math.min(-offset, 1.7) * 0.09;
                    const x = offset * spacing * (offset > 0 ? 1 / (1 + offset * 0.06) : 1);
                    const y = Math.sin(offset * 0.85) * spacing * 0.08;
                    const tilt = Math.sin(i * 2.1 + 0.6) * 9 * (0.35 + 0.65 * Math.min(1, Math.abs(offset)));
                    const visible = offset > -1.7 && offset < 3.6;

                    item.style.transform = `translate(${x}px, ${y}px) rotate(${-tilt}deg)`;
                    item.style.zIndex = String(100 - Math.round(offset * 10));
                    item.firstElementChild.style.transform = `scale(${depthScale})`;
                    item.style.opacity = visible ? '1' : '0';
                    item.style.visibility = visible ? 'visible' : 'hidden';
                });
            } else if (reduced) {
                scene3d.invalidate();
            }

            setActive(wrapIndex(current));

            if (pendingFocus !== null && Math.abs(wrappedOffset(pendingFocus, current, count)) < 0.02) {
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

        // ---- Interacción ---------------------------------------------------

        // Arrastre horizontal (mouse o dedo). Al soltar sigue un poco por inercia
        // y encaja en el frasco más cercano.
        let dragging = false;
        let velocity = 0;
        let lastMoveAt = 0;
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
            velocity = 0;
            lastMoveAt = performance.now();
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
            const now = performance.now();
            lastX = event.clientX;
            velocity = dx / Math.max(1, now - lastMoveAt);
            lastMoveAt = now;
            goTo(target - dx / (spacing || 1));
        }, { signal });

        const endDrag = (event) => {
            const wasClick = dragging && ! moved;
            const wasDrag = dragging && moved;
            dragging = false;
            stage.classList.remove('cursor-grabbing');

            if (wasDrag) {
                // Inercia: si se suelta en movimiento, avanza un poco más antes de encajar.
                const recent = performance.now() - lastMoveAt < 80;
                const fling = recent && ! reduced ? clamp((-velocity * 220) / (spacing || 1), -2, 2) : 0;
                goTo(Math.round(target + fling));
            }

            if (! wasClick || ! scene3d || event.type !== 'pointerup') {
                return;
            }

            const index = scene3d.pick(event.clientX, event.clientY);

            if (index < 0) {
                closeFocus();
            } else if (index === focusedIndex) {
                closeFocus();
            } else if (index === wrapIndex(target)) {
                openFocus(index);
            } else {
                closeFocus({ restoreFocus: false });
                pendingFocus = index;
                goTo(positionFor(index));
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
                openFocus(wrapIndex(target));
            } else if (event.key === 'ArrowRight' || event.key === 'ArrowDown') {
                event.preventDefault();
                goTo(Math.round(target) + 1);
            } else if (event.key === 'ArrowLeft' || event.key === 'ArrowUp') {
                event.preventDefault();
                goTo(Math.round(target) - 1);
            }
        }, { signal });

        section.querySelectorAll('[data-tools-wheel-prev]').forEach((button) => {
            button.addEventListener('click', () => goTo(Math.round(target) - 1), { signal });
        });

        section.querySelectorAll('[data-tools-wheel-next]').forEach((button) => {
            button.addEventListener('click', () => goTo(Math.round(target) + 1), { signal });
        });

        section.querySelectorAll('[data-tools-wheel-open]').forEach((button) => {
            button.addEventListener('click', () => openFocus(Number(button.dataset.toolsWheelOpen)), { signal });
        });

        section.querySelector('[data-tools-wheel-close]')?.addEventListener('click', () => closeFocus(), { signal });

        window.addEventListener('resize', () => {
            layout();
            requestRender();
        }, { signal });

        layout();
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
                        getLayout: () => stageLayout,
                        reducedMotion: reduced,
                    });

                    if (scene3d) {
                        section.dataset.wheel3d = 'true';
                        section.querySelectorAll('[data-tools-wheel-open]').forEach((button) => {
                            button.hidden = false;
                        });
                        // El botón "ver en detalle" cambia el alto del bloque de abajo.
                        layout();
                    }
                })
                .catch(() => {})
                .finally(announceReady);
        } else {
            announceReady();
        }

        controllers.add(abort);
        signal.addEventListener('abort', () => {
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
