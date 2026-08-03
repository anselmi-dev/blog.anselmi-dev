@section('header-content')
    <x-hero-content>
        <p class="mb-2 font-folio-mono text-xs font-medium uppercase tracking-widest text-zinc-600">
            Servicios
        </p>
        <h1 class="text-3xl font-bold tracking-tight text-zinc-900 sm:text-4xl">
            Mis colores
        </h1>
        <p class="mt-4 max-w-2xl text-base leading-relaxed text-zinc-600 sm:text-lg">
            Paleta del layout (brand lime) y una guía de color de referencia. Tocá un color para copiar clases Tailwind.
        </p>
    </x-hero-content>
@endsection

@php
    $spanClass = [
        'sm' => 'min-h-[7.5rem] flex-[1_1_0]',
        'md' => 'min-h-[10rem] flex-[1.35_1_0]',
        'lg' => 'min-h-[14rem] flex-[2_1_0]',
        'xl' => 'min-h-[18rem] flex-[2.8_1_0]',
    ];
@endphp

<div
    class="pb-16 pt-4 sm:pb-20 sm:pt-6 lg:pb-24"
    x-data="{
        open: false,
        leaving: false,
        active: null,
        gradientTo: '#171717',
        columns: @js($columns),
        show(column, index) {
            const swatch = this.columns?.[column]?.[index] ?? null;
            if (! swatch) return;
            const next = this.columns[column][index + 1] ?? this.columns[column][index - 1] ?? null;
            this.active = swatch;
            this.gradientTo = next?.hex ?? '#171717';
            this.leaving = false;
            this.open = true;
            document.body.classList.add('overflow-hidden');
        },
        close() {
            if (! this.open || this.leaving) return;
            this.leaving = true;
            setTimeout(() => {
                this.open = false;
                this.leaving = false;
                this.active = null;
                document.body.classList.remove('overflow-hidden');
            }, 380);
        },
        get solidTw() {
            if (! this.active) return '';
            const text = this.active.ink === 'light' ? 'text-white' : 'text-zinc-900';
            return `bg-[${this.active.hex}] ${text} rounded-2xl p-6`;
        },
        get gradTw() {
            if (! this.active) return '';
            const text = this.active.ink === 'light' ? 'text-white' : 'text-zinc-900';
            return `bg-gradient-to-br from-[${this.active.hex}] to-[${this.gradientTo}] ${text} rounded-2xl p-6`;
        }
    }"
    x-on:keydown.escape.window="open && close()"
>
    <div
        class="mx-auto grid max-w-8xl grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-4 lg:gap-4"
        data-reveal="fade-up"
        data-reveal-scroll
        data-reveal-stagger="0.08"
        data-reveal-once
    >
        @foreach ($columns as $columnIndex => $column)
            <div class="flex min-h-[28rem] flex-col gap-0 overflow-hidden rounded-2xl" data-reveal-item>
                @foreach ($column as $swatchIndex => $swatch)
                    @php
                        $ink = ($swatch['ink'] ?? 'dark') === 'light' ? 'text-white' : 'text-zinc-900';
                        $meta = ($swatch['ink'] ?? 'dark') === 'light' ? 'text-white/55' : 'text-zinc-900/40';
                        $rgb = $swatch['rgb'] ?? [0, 0, 0];
                        $cmyk = $swatch['cmyk'] ?? [0, 0, 0, 0];
                    @endphp
                    <button
                        type="button"
                        x-on:click="show({{ $columnIndex }}, {{ $swatchIndex }})"
                        class="relative flex {{ $spanClass[$swatch['span'] ?? 'md'] }} cursor-pointer flex-col justify-between p-4 text-left transition hover:brightness-[0.97] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-[-2px] focus-visible:outline-zinc-900 sm:p-5"
                        style="background-color: {{ $swatch['hex'] }}"
                        aria-label="Ver {{ $swatch['name'] }}"
                    >
                        <p class="text-sm font-semibold tracking-tight {{ $ink }}">
                            {{ $swatch['name'] }}
                        </p>

                        <div
                            class="pointer-events-none absolute bottom-4 right-3 origin-bottom-right -rotate-90 whitespace-nowrap font-folio-mono text-[0.58rem] uppercase tracking-[0.14em] {{ $meta }} sm:bottom-5 sm:right-4 sm:text-[0.62rem]"
                        >
                            CMYK {{ implode(', ', $cmyk) }}
                            &nbsp;·&nbsp;
                            RGB {{ implode(', ', $rgb) }}
                            &nbsp;·&nbsp;
                            {{ strtoupper($swatch['hex']) }}
                        </div>
                    </button>
                @endforeach
            </div>
        @endforeach
    </div>

    <div
        x-show="open"
        x-cloak
        class="fixed inset-0 z-[200] flex items-center justify-center p-3 sm:p-6"
        role="dialog"
        aria-modal="true"
        aria-labelledby="color-modal-title"
        data-reveal-pause
    >
        <div
            class="absolute inset-0 cursor-pointer bg-zinc-900/50 backdrop-blur-md"
            x-bind:class="leaving ? 'motion-safe:animate-contact-backdrop-out' : 'motion-safe:animate-contact-backdrop-in'"
            x-on:click="close()"
            aria-hidden="true"
        ></div>

        <div
            class="relative z-10 max-h-[min(92vh,42rem)] w-full max-w-2xl overflow-y-auto rounded-3xl bg-white p-6 shadow-[0_25px_80px_-20px_rgba(15,23,42,0.35)] sm:p-8"
            x-bind:class="leaving ? 'motion-safe:animate-contact-modal-out' : 'motion-safe:animate-contact-modal-in'"
            x-on:click.stop
        >
            <button
                type="button"
                class="absolute right-3 top-3 inline-flex size-10 cursor-pointer items-center justify-center rounded-full border border-zinc-200 bg-white text-zinc-600 transition hover:border-zinc-300 hover:text-zinc-900 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-zinc-900"
                x-on:click="close()"
                aria-label="Cerrar"
            >
                <x-icon name="x" class="size-5" />
            </button>

            <h2
                id="color-modal-title"
                class="pr-12 text-2xl font-bold tracking-tight text-zinc-900 sm:text-3xl"
                x-text="active?.name"
            ></h2>

            <div
                class="mt-6 flex min-h-[10rem] items-end rounded-2xl p-5"
                x-bind:class="active?.ink === 'light' ? 'text-white' : 'text-zinc-900'"
                x-bind:style="active ? `background: linear-gradient(135deg, ${active.hex} 0%, ${gradientTo} 100%)` : ''"
            >
                <p class="text-sm font-semibold tracking-tight" x-text="active ? `${active.hex.toUpperCase()} → ${gradientTo.toUpperCase()}` : ''"></p>
            </div>

            <dl class="mt-6 grid grid-cols-1 gap-3 text-sm sm:grid-cols-3">
                <div class="rounded-xl border border-zinc-200 bg-zinc-50 p-3">
                    <dt class="text-xs font-semibold uppercase tracking-wider text-zinc-400">HEX</dt>
                    <dd class="mt-1 font-mono font-medium text-zinc-900" x-text="active?.hex?.toUpperCase()"></dd>
                </div>
                <div class="rounded-xl border border-zinc-200 bg-zinc-50 p-3">
                    <dt class="text-xs font-semibold uppercase tracking-wider text-zinc-400">RGB</dt>
                    <dd class="mt-1 font-mono font-medium text-zinc-900" x-text="active?.rgb?.join(', ')"></dd>
                </div>
                <div class="rounded-xl border border-zinc-200 bg-zinc-50 p-3">
                    <dt class="text-xs font-semibold uppercase tracking-wider text-zinc-400">CMYK</dt>
                    <dd class="mt-1 font-mono font-medium text-zinc-900" x-text="active?.cmyk?.join(', ')"></dd>
                </div>
            </dl>

            <div class="mt-6 space-y-4" x-show="active">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-zinc-400">Tailwind — sólido</p>
                    <pre class="mt-2 overflow-x-auto rounded-xl border border-zinc-200 bg-zinc-50 p-4 font-mono text-xs leading-relaxed text-zinc-800"><code x-text="active ? `<div class=&quot;${solidTw}&quot;>\n  ${active.name}\n</div>` : ''"></code></pre>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-zinc-400">Tailwind — degradado</p>
                    <pre class="mt-2 overflow-x-auto rounded-xl border border-zinc-200 bg-zinc-50 p-4 font-mono text-xs leading-relaxed text-zinc-800"><code x-text="active ? `<div class=&quot;${gradTw}&quot;>\n  ${active.name}\n</div>` : ''"></code></pre>
                </div>
            </div>
        </div>
    </div>
</div>
