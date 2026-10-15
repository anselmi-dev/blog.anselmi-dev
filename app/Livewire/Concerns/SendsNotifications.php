<?php

declare(strict_types=1);

namespace App\Livewire\Concerns;

use App\Support\NotificationVariant;

/**
 * Muestra un aviso flotante del sitio desde un componente Livewire
 * (lo recibe resources/js/notifications.js por el evento "notify").
 */
trait SendsNotifications
{
    /**
     * @param  list<array{label: string, href?: string, event?: string, primary?: bool}>  $actions
     */
    protected function notify(
        NotificationVariant $variant,
        string $title,
        ?string $message = null,
        array $actions = [],
        ?string $key = null,
    ): void {
        $this->dispatch('notify', ...array_filter([
            'variant' => $variant->value,
            'title' => $title,
            'message' => $message,
            'actions' => $actions,
            'key' => $key,
        ], fn (mixed $value): bool => $value !== null && $value !== []));
    }
}
