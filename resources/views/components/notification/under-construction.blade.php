{{--
    Aviso de "sitio en construcción" (se activa en Filament: Ajustes → Sitio).
    Si el visitante lo cierra, no vuelve a salir en esa pestaña salvo que
    cambie el texto.
--}}
@php
    $site = \App\Models\Setting::localizedSite();
    $title = filled($site['under_construction_title'] ?? null) ? $site['under_construction_title'] : __('Sitio en construcción');
    $message = $site['under_construction_message'] ?? null;
    $notification = [
        'variant' => \App\Support\NotificationVariant::Warning->value,
        'badge' => __('En construcción'),
        'title' => $title,
        'message' => $message,
        'duration' => null,
        'key' => 'under-construction',
        'once' => 'under-construction:'.substr(md5($title.'|'.$message), 0, 8),
    ];
@endphp

@if ($site['under_construction'] ?? false)
    <div x-data x-init="window.notify(@js($notification))" data-under-construction hidden></div>
@endif
