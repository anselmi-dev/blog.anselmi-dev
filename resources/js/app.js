import { createIcons, icons } from 'lucide';
import { initBookCarousels } from './book-carousel';
import { initCustomCursor } from './custom-cursor';
import { initMotion } from './motion';

/**
 * Reemplaza nodos `<i data-lucide="nombre">` por SVG de Lucide.
 * Misma familia visual que lucide-animated.com (animaciones de ese sitio son solo React + Motion).
 */
function initLucideIcons(root = document) {
    createIcons({
        icons,
        attrs: {
            class: ['lucide-icon'],
            'stroke-width': 1.75,
        },
        root,
    });
}

function boot(root = document) {
    initLucideIcons(root);
    initMotion(root);
    initBookCarousels(root);
    initCustomCursor(root);
}

document.addEventListener('DOMContentLoaded', () => boot());

document.addEventListener('livewire:initialized', () => {
    queueMicrotask(() => boot());
});

document.addEventListener('livewire:navigated', () => {
    queueMicrotask(() => boot());
});
