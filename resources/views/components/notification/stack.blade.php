{{--
    Pila de avisos flotantes (abajo a la derecha; abajo a lo ancho en celular).
    Se alimenta desde resources/js/notifications.js: window.notify({...}),
    el evento "notify" (también desde Livewire con $this->dispatch('notify', ...))
    o $store.notifications. Usa las mismas clases que <x-notification>.
--}}
<section
    x-data
    x-init="$store.notifications.badges = @js(\App\Support\NotificationVariant::badges())"
    class="notification-stack"
    aria-label="{{ __('Notificaciones') }}"
>
    <template x-for="item in $store.notifications.items" :key="item.id">
        <div
            class="notification notification-toast"
            :class="{ 'notification-has-badge': item.badge, 'notification-leaving': item.leaving }"
            :data-variant="item.variant"
            :role="['warning', 'error'].includes(item.variant) ? 'alert' : 'status'"
            x-on:mouseenter="$store.notifications.hold(item.id)"
            x-on:mouseleave="$store.notifications.release(item.id)"
            x-on:focusin="$store.notifications.hold(item.id)"
            x-on:focusout="$store.notifications.release(item.id)"
        >
            <span class="notification-badge" x-show="item.badge">
                <x-notification.icon alpine="item.variant" kind="badge" class="size-3.5" />
                <span x-text="item.badge"></span>
            </span>

            <span class="notification-tile" aria-hidden="true">
                <span class="notification-tile-inner">
                    <x-notification.icon alpine="item.variant" class="size-7" />
                </span>
            </span>

            <div class="notification-body">
                <p class="notification-title" x-text="item.title"></p>
                <p class="notification-message" x-show="item.message" x-text="item.message"></p>

                <div class="notification-actions" x-show="item.actions.length">
                    <template x-for="(action, index) in item.actions" :key="index">
                        <a
                            :href="action.href || '#'"
                            class="notification-action"
                            :class="{ 'notification-action-primary': action.primary }"
                            x-text="action.label"
                            x-on:click="$store.notifications.run(item.id, action, $event)"
                        ></a>
                    </template>
                </div>
            </div>

            <button
                type="button"
                class="notification-close"
                x-show="item.dismissible"
                x-on:click="$store.notifications.dismiss(item.id)"
                aria-label="{{ __('Cerrar notificación') }}"
            >
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true" class="size-4"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
            </button>
        </div>
    </template>
</section>
