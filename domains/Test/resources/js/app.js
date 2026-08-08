import { createIcons, icons } from 'lucide';

function boot(root = document) {
    createIcons({
        icons,
        attrs: {
            class: ['lucide-icon'],
            'stroke-width': 1.75,
        },
        root,
    });
}

document.addEventListener('DOMContentLoaded', () => boot());
document.addEventListener('livewire:navigated', () => queueMicrotask(() => boot()));
