/**
 * Cursor personalizado: punto + anillo con seguimiento suave (lerp).
 * Solo en puntero fino y con el estilo "circle"; respeta prefers-reduced-motion.
 */
let cursorEl = null;
let cursorBound = false;

export function initCustomCursor(root = document) {
    if (typeof window === 'undefined') {
        return;
    }

    // El estilo se elige en Filament (Ajustes → Sitio → Estilo del cursor).
    if (document.documentElement.dataset.cursor !== 'circle') {
        return;
    }

    if (window.matchMedia('(pointer: coarse)').matches) {
        return;
    }

    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        return;
    }

    // wire:navigate reemplaza el contenido de <body> y el nodo inyectado en
    // runtime no forma parte de ninguna plantilla, así que la navegación lo
    // descarta aunque el resto del estado (listeners, rAF) siga vivo.
    // Reengancharlo alcanza, no hace falta recrear nada más.
    if (cursorEl && ! document.body.contains(cursorEl)) {
        document.body.appendChild(cursorEl);
    }

    if (cursorBound) {
        return;
    }

    cursorBound = true;
    document.documentElement.classList.add('has-custom-cursor');

    const cursor = document.createElement('div');
    cursor.className = 'site-cursor';
    cursor.setAttribute('aria-hidden', 'true');
    cursor.innerHTML = `
        <div class="site-cursor__ring"></div>
        <div class="site-cursor__dot"></div>
    `;
    document.body.appendChild(cursor);
    cursorEl = cursor;

    const ring = cursor.querySelector('.site-cursor__ring');
    const dot = cursor.querySelector('.site-cursor__dot');

    const mouse = { x: window.innerWidth / 2, y: window.innerHeight / 2 };
    const ringPos = { x: mouse.x, y: mouse.y };
    let visible = false;
    let hovering = false;
    let rafId = 0;

    const lerp = (a, b, n) => a + (b - a) * n;

    const interactiveSelector = [
        'a',
        'button',
        'label',
        'summary',
        'input',
        'textarea',
        'select',
        '[role="button"]',
        '[data-cursor-hover]',
    ].join(',');

    function setVisible(next) {
        if (visible === next) {
            return;
        }
        visible = next;
        cursor.classList.toggle('is-visible', visible);
    }

    function onMove(event) {
        mouse.x = event.clientX;
        mouse.y = event.clientY;
        setVisible(true);

        const target = event.target;
        const isInteractive = Boolean(target?.closest?.(interactiveSelector));
        const isTextField = Boolean(target?.closest?.('input, textarea, [contenteditable="true"]'));

        hovering = isInteractive && ! isTextField;
        cursor.classList.toggle('is-hover', hovering);
        cursor.classList.toggle('is-hidden', isTextField);
    }

    function onLeave() {
        setVisible(false);
        hovering = false;
        cursor.classList.remove('is-hover', 'is-hidden');
    }

    function tick() {
        ringPos.x = lerp(ringPos.x, mouse.x, 0.16);
        ringPos.y = lerp(ringPos.y, mouse.y, 0.16);

        dot.style.transform = `translate3d(${mouse.x}px, ${mouse.y}px, 0) translate(-50%, -50%)${hovering ? ' scale(0.55)' : ''}`;
        ring.style.transform = `translate3d(${ringPos.x}px, ${ringPos.y}px, 0) translate(-50%, -50%)${hovering ? ' scale(1.55)' : ''}`;

        rafId = window.requestAnimationFrame(tick);
    }

    window.addEventListener('mousemove', onMove, { passive: true });
    window.addEventListener('mouseleave', onLeave);
    document.addEventListener('mouseenter', () => setVisible(true));
    document.documentElement.addEventListener('mouseleave', onLeave);

    rafId = window.requestAnimationFrame(tick);

    document.addEventListener('livewire:navigating', () => {
        // Livewire Navigate guarda un snapshot en HTML de la página que estás
        // dejando para que "atrás" sea instantáneo. Si el cursor sigue en el
        // <body> en ese momento, queda horneado dentro de ese snapshot y al
        // restaurarlo termina duplicado junto al nodo que reenganchamos acá
        // abajo. Sacarlo del DOM antes de que se tome esa foto evita eso.
        setVisible(false);
        cursor.remove();
    });

    return () => {
        window.cancelAnimationFrame(rafId);
        window.removeEventListener('mousemove', onMove);
        window.removeEventListener('mouseleave', onLeave);
        cursor.remove();
        document.documentElement.classList.remove('has-custom-cursor');
        cursorEl = null;
        cursorBound = false;
    };
}
