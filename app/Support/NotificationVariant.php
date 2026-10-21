<?php

declare(strict_types=1);

namespace App\Support;

/**
 * Variantes de las notificaciones del sitio (ver <x-notification> y
 * resources/js/notifications.js). Cada una tiene su color y su píldora.
 */
enum NotificationVariant: string
{
    case Pending = 'pending';
    case Warning = 'warning';
    case Success = 'success';
    case Error = 'error';
    case Neutral = 'neutral';

    /**
     * Texto de la píldora superior; la neutra no lleva.
     */
    public function badge(): ?string
    {
        return match ($this) {
            self::Pending => __('Pendiente'),
            self::Warning => __('Atención'),
            self::Success => __('Listo'),
            self::Error => __('Error'),
            self::Neutral => null,
        };
    }

    /**
     * Las de error y atención se anuncian enseguida a los lectores de pantalla.
     */
    public function role(): string
    {
        return in_array($this, [self::Warning, self::Error], true) ? 'alert' : 'status';
    }

    /**
     * Textos de las píldoras por variante, para la pila de avisos en el navegador.
     *
     * @return array<string, string|null>
     */
    public static function badges(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (self $variant): array => [$variant->value => $variant->badge()])
            ->all();
    }
}
