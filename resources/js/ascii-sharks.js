const SHARK = [
    '   /\\',
    " =('>",
    '   \\/',
];

const COLS = 46;
const ROWS = 8;
const WATER_CHARS = '~‾~_';
const FRAME_INTERVAL = 90;

const controllers = new Set();

function prefersReducedMotion() {
    return window.matchMedia('(prefers-reduced-motion: reduce)').matches;
}

function makeGrid() {
    return Array.from({ length: ROWS }, () => Array(COLS).fill(' '));
}

function drawWaterline(grid, offset) {
    for (let col = 0; col < COLS; col++) {
        grid[0][col] = WATER_CHARS[(col + offset) % WATER_CHARS.length];
    }
}

function blitSprite(grid, sprite, row, col) {
    sprite.forEach((line, dy) => {
        const targetRow = row + dy;
        if (targetRow < 1 || targetRow >= grid.length) {
            return;
        }

        for (let dx = 0; dx < line.length; dx++) {
            const ch = line[dx];
            if (ch === ' ') {
                continue;
            }

            const targetCol = col + dx;
            if (targetCol >= 0 && targetCol < COLS) {
                grid[targetRow][targetCol] = ch;
            }
        }
    });
}

function renderFrame(screen, offset, sharkX, bob, bubbles) {
    const grid = makeGrid();
    drawWaterline(grid, offset);

    bubbles.forEach(({ row, col }) => {
        const r = Math.round(row);
        if (r >= 1 && r < ROWS && col >= 0 && col < COLS && grid[r][col] === ' ') {
            grid[r][col] = '°';
        }
    });

    blitSprite(grid, SHARK, 3 + bob, Math.round(sharkX));
    screen.textContent = grid.map((row) => row.join('')).join('\n');
}

/**
 * Animación ASCII propia (no es el código del proyecto original) inspirada
 * en la idea de ascii-sharks: un tiburón nadando entre burbujas en una
 * grilla de texto. Se liga a [data-ascii-sharks] + [data-ascii-sharks-screen].
 */
export function initAsciiSharks(root = document) {
    root.querySelectorAll('[data-ascii-sharks]').forEach((container) => {
        if (container.dataset.sharksBound === 'true') {
            return;
        }

        container.dataset.sharksBound = 'true';

        const screen = container.querySelector('[data-ascii-sharks-screen]');
        if (! screen) {
            return;
        }

        const spriteWidth = Math.max(...SHARK.map((line) => line.length));

        if (prefersReducedMotion()) {
            renderFrame(screen, 0, Math.round((COLS - spriteWidth) / 2), 0, []);

            return;
        }

        const state = {
            offset: 0,
            sharkX: -spriteWidth,
            bubbles: [],
            visible: true,
            raf: null,
            lastTick: 0,
        };

        const tick = (now) => {
            state.raf = null;
            if (! state.visible) {
                return;
            }

            if (now - state.lastTick < FRAME_INTERVAL) {
                state.raf = requestAnimationFrame(tick);

                return;
            }

            state.lastTick = now;
            state.offset += 1;
            state.sharkX += 1;

            if (state.sharkX > COLS) {
                state.sharkX = -spriteWidth;
            }

            if (state.offset % 18 === 0) {
                state.bubbles.push({ row: 3, col: Math.round(state.sharkX) - 1 });
            }

            state.bubbles.forEach((bubble) => {
                bubble.row -= 0.4;
            });
            state.bubbles = state.bubbles.filter((bubble) => bubble.row > 0.5);

            const bob = Math.sin(state.offset / 9) > 0 ? 1 : 0;
            renderFrame(screen, state.offset, state.sharkX, bob, state.bubbles);
            state.raf = requestAnimationFrame(tick);
        };

        const start = () => {
            if (state.raf === null && state.visible) {
                state.lastTick = 0;
                state.raf = requestAnimationFrame(tick);
            }
        };

        const observer = new IntersectionObserver(([entry]) => {
            state.visible = entry.isIntersecting;
            if (state.visible) {
                start();
            }
        });
        observer.observe(container);

        const controller = new AbortController();
        controllers.add(controller);
        controller.signal.addEventListener('abort', () => {
            if (state.raf !== null) {
                cancelAnimationFrame(state.raf);
            }

            observer.disconnect();
            controllers.delete(controller);
        });

        renderFrame(screen, 0, state.sharkX, 0, []);
        start();
    });
}

// Al navegar con wire:navigate la pantalla desaparece: soltamos el loop y el observer.
document.addEventListener('livewire:navigating', () => {
    controllers.forEach((controller) => controller.abort());
});
