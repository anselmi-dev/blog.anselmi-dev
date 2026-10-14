@props([
    /** Opciones del orden: valor => texto. */
    'options' => [],
    /** Valor elegido ahora. */
    'value' => null,
    /** Propiedad Livewire que se cambia al elegir una opción. */
    'model' => 'sort',
    'label' => __('Ordenar'),
])

<div
    {{ $attributes->class(['relative inline-block text-sm']) }}
    x-data="{ open: false }"
    x-on:click.outside="open = false"
    x-on:keydown.escape.window="if (open) { open = false; $refs.trigger.focus() }"
>
    <button
        type="button"
        x-ref="trigger"
        x-on:click="open = ! open"
        x-bind:aria-expanded="open"
        aria-haspopup="listbox"
        class="inline-flex cursor-pointer items-center gap-2 rounded-full border border-zinc-200 bg-white py-1.5 pl-4 pr-2 transition hover:border-zinc-900/25 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-zinc-900"
    >
        <span class="text-zinc-400">{{ $label }}</span>
        <span class="font-medium text-zinc-900">{{ $options[$value] ?? '' }}</span>
        <span class="inline-flex size-6 items-center justify-center rounded-full border border-zinc-900" aria-hidden="true">
            {{-- SVG en línea: los <x-icon> de lucide se pierden cuando Livewire re-renderiza. --}}
            <svg
                class="size-3.5 transition-transform duration-300 ease-out"
                x-bind:class="{ 'rotate-180': open }"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="1.75"
                stroke-linecap="round"
                stroke-linejoin="round"
            ><path d="m6 9 6 6 6-6" /></svg>
        </span>
    </button>

    <ul
        x-show="open"
        x-cloak
        x-transition:enter="transition duration-150 ease-out"
        x-transition:enter-start="-translate-y-1 opacity-0"
        x-transition:leave="transition duration-100 ease-in"
        x-transition:leave-end="-translate-y-1 opacity-0"
        role="listbox"
        aria-label="{{ $label }}"
        class="absolute right-0 z-20 mt-2 min-w-44 rounded-2xl bg-white p-1.5 shadow-xl ring-1 ring-zinc-900/5"
    >
        @foreach ($options as $optionValue => $optionLabel)
            <li role="option" aria-selected="{{ $optionValue === $value ? 'true' : 'false' }}">
                <button
                    type="button"
                    wire:click="$set('{{ $model }}', '{{ $optionValue }}')"
                    x-on:click="open = false; $refs.trigger.focus()"
                    @class([
                        'flex w-full cursor-pointer items-center justify-between gap-4 rounded-xl px-3 py-2 text-left transition focus-visible:outline focus-visible:outline-2 focus-visible:outline-zinc-900',
                        'bg-brand-lime-50 font-semibold text-zinc-900' => $optionValue === $value,
                        'text-zinc-600 hover:bg-zinc-100 hover:text-zinc-900' => $optionValue !== $value,
                    ])
                >
                    <span>{{ $optionLabel }}</span>
                    @if ($optionValue === $value)
                        <svg class="size-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 6 9 17l-5-5" /></svg>
                    @endif
                </button>
            </li>
        @endforeach
    </ul>
</div>
