{{--
    Tarjeta de notificación: ícono en un "azulejo" con grilla, título,
    mensaje, píldora arriba al centro y acciones opcionales.

    <x-notification variant="warning" title="Falta completar" message="..." />
    <x-notification variant="neutral" title="..." :badge="false">
        <x-slot:actions>
            <a href="..." class="notification-action notification-action-primary">Ver cambios</a>
        </x-slot:actions>
    </x-notification>

    variant: pending | warning | success | error | neutral.
    badge: texto propio, false para ocultarla, null para la de la variante.
    Los colores están en resources/css/notifications.css.
--}}
@props([
    'variant' => 'neutral',
    'title',
    'message' => null,
    'badge' => null,
])

@php
    $variant = \App\Support\NotificationVariant::tryFrom($variant) ?? \App\Support\NotificationVariant::Neutral;
    $badgeText = $badge === false ? null : ($badge ?? $variant->badge());
@endphp

<div {{ $attributes->class(['notification', 'notification-has-badge' => filled($badgeText)]) }} data-variant="{{ $variant->value }}" role="{{ $variant->role() }}">
    @if (filled($badgeText))
        <span class="notification-badge">
            <x-notification.icon :variant="$variant->value" kind="badge" class="size-3.5" />
            {{ $badgeText }}
        </span>
    @endif

    <span class="notification-tile" aria-hidden="true">
        <span class="notification-tile-inner">
            <x-notification.icon :variant="$variant->value" class="size-7" />
        </span>
    </span>

    <div class="notification-body">
        <p class="notification-title">{{ $title }}</p>

        @if (filled($message))
            <p class="notification-message">{{ $message }}</p>
        @endif

        @isset($actions)
            <div class="notification-actions">{{ $actions }}</div>
        @endisset
    </div>

    {{ $slot }}
</div>
