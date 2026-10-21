/**
 * Avisos flotantes del sitio (ver <x-notification.stack>).
 *
 *   const aviso = window.notify({ variant: 'pending', title: 'Enviando…' });
 *   aviso.update({ variant: 'success', title: 'Enviado' });
 *   aviso.dismiss();
 *
 * También se disparan con el evento "notify" en window, que es lo que hace
 * Livewire con $this->dispatch('notify', variant: 'warning', title: '...').
 *
 * Opciones: variant (pending | warning | success | error | neutral), title,
 * message, badge (texto, o false para ocultarla), actions ([{ label, href,
 * event, primary, dismiss }]), duration (ms; null = hasta cerrarla),
 * dismissible, key (si ya hay una con la misma key, la reemplaza) y once
 * (clave para sessionStorage: una vez cerrada no vuelve a salir en la visita).
 */

const VARIANTS = ['pending', 'warning', 'success', 'error', 'neutral'];
const DEFAULT_DURATION = 5000;
const LEAVE_MS = 200;
const MAX_VISIBLE = 4;

let nextId = 1;
let store = null;
const queued = [];

function defaultDuration(variant, actions) {
    if (variant === 'pending') {
        return null;
    }

    return actions.length ? DEFAULT_DURATION * 2 : DEFAULT_DURATION;
}

function normalize(options, previous = {}) {
    const merged = { ...previous, ...options };
    const variant = VARIANTS.includes(merged.variant) ? merged.variant : 'neutral';
    const actions = Array.isArray(merged.actions) ? merged.actions.filter((action) => action && action.label) : [];

    const variantChanged = variant !== previous.variant;
    const duration = 'duration' in options
        ? options.duration
        : (variantChanged ? defaultDuration(variant, actions) : previous.duration);

    return {
        ...merged,
        variant,
        title: String(merged.title ?? ''),
        message: merged.message ? String(merged.message) : '',
        actions,
        duration,
        dismissible: merged.dismissible ?? true,
        badgeOption: 'badge' in options ? options.badge : previous.badgeOption,
    };
}

function createStore() {
    return {
        items: [],
        badges: {},
        timers: {},

        badgeFor(item) {
            if (item.badgeOption === false) {
                return null;
            }

            return item.badgeOption || this.badges[item.variant] || null;
        },

        push(id, options) {
            const existing = options.key ? this.items.find((item) => item.key === options.key && ! item.leaving) : null;

            if (existing) {
                this.update(existing.id, options);

                return existing.id;
            }

            const item = normalize(options);
            item.id = id;
            item.leaving = false;
            item.badge = this.badgeFor(item);

            this.items.push(item);
            this.schedule(item);

            const visible = this.items.filter((entry) => ! entry.leaving);
            if (visible.length > MAX_VISIBLE) {
                this.dismiss(visible[0].id);
            }

            return id;
        },

        update(id, changes) {
            const item = this.items.find((entry) => entry.id === id);

            if (! item) {
                return;
            }

            Object.assign(item, normalize(changes, item));
            item.badge = this.badgeFor(item);
            this.schedule(item);
        },

        schedule(item, duration = item.duration) {
            clearTimeout(this.timers[item.id]);

            if (duration == null) {
                return;
            }

            item.endsAt = Date.now() + duration;
            this.timers[item.id] = setTimeout(() => this.dismiss(item.id), duration);
        },

        hold(id) {
            const item = this.items.find((entry) => entry.id === id);

            if (item && item.duration != null) {
                clearTimeout(this.timers[id]);
                item.remaining = Math.max(1500, item.endsAt - Date.now());
            }
        },

        release(id) {
            const item = this.items.find((entry) => entry.id === id);

            if (item && item.duration != null && item.remaining) {
                this.schedule(item, item.remaining);
            }
        },

        dismiss(id) {
            const item = this.items.find((entry) => entry.id === id);

            if (! item || item.leaving) {
                return;
            }

            if (item.once) {
                rememberDismissed(item.once);
            }

            clearTimeout(this.timers[id]);
            item.leaving = true;

            setTimeout(() => {
                this.items = this.items.filter((entry) => entry.id !== id);
                delete this.timers[id];
            }, LEAVE_MS);
        },

        run(id, action, event) {
            if (action.event) {
                event.preventDefault();
                window.dispatchEvent(new CustomEvent(action.event, { detail: action.detail ?? {} }));
            } else if (! action.href) {
                event.preventDefault();
            }

            if (action.dismiss !== false) {
                this.dismiss(id);
            }
        },
    };
}

function wasDismissed(once) {
    try {
        return sessionStorage.getItem(`notification:${once}`) === '1';
    } catch (_) {
        return false;
    }
}

function rememberDismissed(once) {
    try {
        sessionStorage.setItem(`notification:${once}`, '1');
    } catch (_) {
        // Sin sessionStorage (modo privado estricto) vuelve a salir: no es grave.
    }
}

export function notify(options = {}) {
    const id = nextId++;

    if (options.once && wasDismissed(options.once)) {
        return { id, update: () => {}, dismiss: () => {} };
    }

    if (store) {
        store.push(id, options);
    } else {
        queued.push([id, options]);
    }

    return {
        id,
        update: (changes) => store?.update(id, changes),
        dismiss: () => store?.dismiss(id),
    };
}

export function initNotifications() {
    window.notify = notify;

    window.addEventListener('notify', (event) => {
        const detail = Array.isArray(event.detail) ? event.detail[0] : event.detail;
        notify(detail ?? {});
    });

    window.addEventListener('notify-dismiss', (event) => {
        const key = event.detail?.key;
        store?.items.filter((item) => item.key === key).forEach((item) => store.dismiss(item.id));
    });

    document.addEventListener('alpine:init', () => {
        window.Alpine.store('notifications', createStore());
        store = window.Alpine.store('notifications');

        queued.splice(0).forEach(([id, options]) => store.push(id, options));
    });
}
