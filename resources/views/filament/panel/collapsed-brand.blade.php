{{--
    Con la barra lateral contraída se ve solo el cuadrado verde del logo;
    al tocarlo se expande el menú.
--}}
<button
    type="button"
    x-data="{}"
    x-cloak
    x-show="! $store.sidebar.isOpen"
    x-on:click="$store.sidebar.open()"
    aria-controls="fi-main-sidebar"
    aria-label="{{ __('filament-panels::layout.actions.sidebar.expand.label') }}"
    title="{{ __('filament-panels::layout.actions.sidebar.expand.label') }}"
    class="panel-brand-mark panel-brand-toggle"
>
    <x-logo.app class="panel-brand-logo" />
</button>
