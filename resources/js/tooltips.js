/**
 * Tooltips del sitio, sin librerías.
 *
 * Texto simple:
 *   <button data-tooltip="Ver en inglés">EN</button>
 *
 * Contenido con chips (un <template data-tooltip-content> dentro del
 * elemento, o data-tooltip-template="#id" apuntando a un <template>):
 *   <a href="..." data-tooltip-theme="dark">
 *       Vuelo
 *       <template data-tooltip-content>
 *           Cambio de aeropuerto
 *           <span class="tooltip-chip">IST</span>
 *           <span class="tooltip-separator">›</span>
 *           <span class="tooltip-chip">SAW</span>
 *           <span class="tooltip-chip tooltip-chip-strong">1d 2h</span>
 *       </template>
 *   </a>
 *
 * Opciones: data-tooltip-theme="light|dark" (claro por defecto) y
 * data-tooltip-placement="top|bottom" (arriba por defecto; si no entra, da
 * vuelta). Usa delegación de eventos, así que sirve para contenido que
 * agrega Livewire sin volver a inicializar nada.
 */

const SELECTOR = '[data-tooltip], [data-tooltip-template], :has(> template[data-tooltip-content])';
const GUTTER = 8;
const OFFSET = 10;
const SHOW_DELAY = 80;
const HIDE_DELAY = 60;

let bubble = null;
let content = null;
let activeTrigger = null;
let showTimer = null;
let hideTimer = null;
let frame = null;

function createBubble() {
    bubble = document.createElement('div');
    bubble.id = 'site-tooltip';
    bubble.className = 'tooltip';
    bubble.setAttribute('role', 'tooltip');
    bubble.hidden = true;

    content = document.createElement('div');
    content.className = 'tooltip-content';

    const arrow = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
    arrow.setAttribute('class', 'tooltip-arrow');
    arrow.setAttribute('viewBox', '0 0 20 9');
    arrow.setAttribute('aria-hidden', 'true');
    arrow.innerHTML = '<path d="M0 0C6 0 7.2 9 10 9S14 0 20 0Z" />';

    bubble.append(content, arrow);
    document.body.append(bubble);
}

function triggerFrom(target) {
    return target instanceof Element ? target.closest(SELECTOR) : null;
}

function fillContent(trigger) {
    const text = trigger.getAttribute('data-tooltip');
    const templateSelector = trigger.getAttribute('data-tooltip-template');
    const template = templateSelector
        ? document.querySelector(templateSelector)
        : trigger.querySelector(':scope > template[data-tooltip-content]');

    content.replaceChildren();

    if (template instanceof HTMLTemplateElement) {
        content.append(template.content.cloneNode(true));
    } else if (text) {
        content.textContent = text;
    }

    return content.textContent.trim() !== '';
}

function position() {
    if (! activeTrigger || ! bubble) {
        return;
    }

    const rect = activeTrigger.getBoundingClientRect();
    const width = bubble.offsetWidth;
    const height = bubble.offsetHeight;
    const viewportWidth = document.documentElement.clientWidth;

    let placement = activeTrigger.getAttribute('data-tooltip-placement') === 'bottom' ? 'bottom' : 'top';

    if (placement === 'top' && rect.top - height - OFFSET < GUTTER) {
        placement = 'bottom';
    } else if (placement === 'bottom' && rect.bottom + height + OFFSET > window.innerHeight - GUTTER) {
        placement = 'top';
    }

    const center = rect.left + rect.width / 2;
    const left = Math.min(Math.max(center - width / 2, GUTTER), viewportWidth - width - GUTTER);
    const top = placement === 'top' ? rect.top - height - OFFSET : rect.bottom + OFFSET;
    const arrowX = Math.min(Math.max(center - left, 18), width - 18);

    bubble.dataset.placement = placement;
    bubble.style.setProperty('--tooltip-arrow-x', `${arrowX}px`);
    bubble.style.transform = `translate3d(${Math.round(left)}px, ${Math.round(top)}px, 0)`;
}

function schedulePosition() {
    cancelAnimationFrame(frame);
    frame = requestAnimationFrame(position);
}

function show(trigger) {
    clearTimeout(hideTimer);

    if (! bubble) {
        createBubble();
    }

    if (! fillContent(trigger)) {
        return;
    }

    if (activeTrigger && activeTrigger !== trigger) {
        activeTrigger.removeAttribute('aria-describedby');
    }

    activeTrigger = trigger;
    trigger.setAttribute('aria-describedby', bubble.id);

    bubble.dataset.theme = trigger.getAttribute('data-tooltip-theme') === 'dark' ? 'dark' : 'light';
    bubble.hidden = false;
    bubble.classList.remove('tooltip-visible');
    position();

    requestAnimationFrame(() => bubble?.classList.add('tooltip-visible'));

    window.addEventListener('scroll', schedulePosition, { passive: true, capture: true });
    window.addEventListener('resize', schedulePosition, { passive: true });
}

function hide() {
    clearTimeout(showTimer);

    if (! bubble || ! activeTrigger) {
        return;
    }

    activeTrigger.removeAttribute('aria-describedby');
    activeTrigger = null;
    bubble.classList.remove('tooltip-visible');
    bubble.hidden = true;

    window.removeEventListener('scroll', schedulePosition, { capture: true });
    window.removeEventListener('resize', schedulePosition);
}

function showSoon(trigger) {
    clearTimeout(hideTimer);
    clearTimeout(showTimer);

    if (trigger === activeTrigger) {
        return;
    }

    showTimer = setTimeout(() => show(trigger), activeTrigger ? 0 : SHOW_DELAY);
}

function hideSoon() {
    clearTimeout(showTimer);
    clearTimeout(hideTimer);
    hideTimer = setTimeout(hide, HIDE_DELAY);
}

export function initTooltips() {
    document.addEventListener('pointerover', (event) => {
        if (event.pointerType === 'touch') {
            return;
        }

        const trigger = triggerFrom(event.target);

        if (trigger) {
            showSoon(trigger);
        }
    });

    document.addEventListener('pointerout', (event) => {
        if (event.pointerType === 'touch') {
            return;
        }

        const trigger = triggerFrom(event.target);

        if (trigger && ! trigger.contains(event.relatedTarget)) {
            hideSoon();
        }
    });

    document.addEventListener('focusin', (event) => {
        const trigger = triggerFrom(event.target);

        if (trigger && trigger.matches(':focus-visible')) {
            show(trigger);
        }
    });

    document.addEventListener('focusout', (event) => {
        if (triggerFrom(event.target)) {
            hideSoon();
        }
    });

    // En pantallas táctiles: mantener apretado muestra el tooltip, tocar
    // en cualquier otro lado lo esconde.
    document.addEventListener('pointerdown', (event) => {
        const trigger = triggerFrom(event.target);

        if (event.pointerType === 'touch' && trigger) {
            clearTimeout(showTimer);
            showTimer = setTimeout(() => show(trigger), 450);

            return;
        }

        if (! trigger) {
            hide();
        }
    });

    document.addEventListener('pointerup', (event) => {
        if (event.pointerType === 'touch' && ! activeTrigger) {
            clearTimeout(showTimer);
        }
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            hide();
        }
    });

    document.addEventListener('livewire:navigating', hide);
}
