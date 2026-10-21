import { createIcons, icons } from 'lucide';
import { initBookCarousels } from './book-carousel';
import { initCustomCursor } from './custom-cursor';
import { initImageMetadataUploads } from './image-metadata-upload';
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
    initImageMetadataUploads(root);
}

let interfaceRefreshQueued = false;

function queueInterfaceRefresh() {
    if (interfaceRefreshQueued) {
        return;
    }

    interfaceRefreshQueued = true;

    queueMicrotask(() => {
        initLucideIcons();
        initImageMetadataUploads();
        interfaceRefreshQueued = false;
    });
}

document.addEventListener('DOMContentLoaded', () => boot());

document.addEventListener('livewire:initialized', () => {
    queueMicrotask(() => boot());
    window.Livewire?.hook('morph.updated', queueInterfaceRefresh);
});

document.addEventListener('livewire:navigated', () => {
    queueMicrotask(() => boot());
});

// Si el navegador restaura la página desde el back/forward cache (típico al
// volver con el botón "atrás"), no hay un load ni un evento de Livewire: la
// página simplemente se "despausa" tal como quedó. Nada de lo anterior se
// vuelve a ejecutar, así que hay que forzar el re-init acá explícitamente.
window.addEventListener('pageshow', (event) => {
    if (event.persisted) {
        boot();
    }
});
