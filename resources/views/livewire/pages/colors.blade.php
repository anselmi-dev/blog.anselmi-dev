@section('header-content')
    <x-hero-content>
        <p class="mb-2 font-folio-mono text-xs font-medium uppercase tracking-widest text-zinc-600">
            Servicios
        </p>
        <h1 class="text-3xl font-bold tracking-tight text-zinc-900 sm:text-4xl">
            Mis colores
        </h1>
        <p class="mt-4 max-w-2xl text-base leading-relaxed text-zinc-600 sm:text-lg">
            Paleta del layout (brand lime) y una guía de color de referencia. Tocá un color para exportarlo a Tailwind v2 o v3.
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
    x-data="colorExportModal(@js($columns))"
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
            class="relative z-10 max-h-[min(92vh,48rem)] w-full max-w-4xl overflow-y-auto rounded-3xl bg-white p-6 shadow-[0_25px_80px_-20px_rgba(15,23,42,0.35)] sm:p-8"
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
                class="mt-6 flex min-h-[8rem] items-end rounded-2xl p-5"
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

            <div class="mt-6" x-show="active">
                <p class="text-xs font-semibold uppercase tracking-wider text-zinc-400">Escala generada</p>
                <div class="mt-2 flex overflow-hidden rounded-xl ring-1 ring-zinc-200">
                    <template x-for="shade in shadeEntries" :key="shade.step">
                        <div
                            class="h-10 min-w-0 flex-1"
                            x-bind:style="`background-color: ${shade.value}`"
                            x-bind:title="`${shade.step}: ${shade.value}`"
                        ></div>
                    </template>
                </div>
            </div>

            <div
                class="mt-6 grid grid-cols-1 gap-4 border-t border-zinc-100 pt-6 sm:grid-cols-[9rem_9rem_minmax(0,1fr)] sm:gap-0 sm:divide-x sm:divide-zinc-100"
                x-show="active"
            >
                <div class="sm:pr-4">
                    <p class="mb-3 text-[0.65rem] font-semibold uppercase tracking-wider text-zinc-400">Framework</p>
                    <ul class="space-y-1">
                        <li>
                            <button
                                type="button"
                                class="flex w-full items-center justify-between rounded-lg px-2.5 py-2 text-left text-sm transition"
                                x-bind:class="twVersion === 'v2' ? 'bg-zinc-100 font-medium text-zinc-900' : 'text-zinc-400 hover:text-zinc-700'"
                                x-on:click="twVersion = 'v2'"
                            >
                                <span>Tailwind 2</span>
                            </button>
                        </li>
                        <li>
                            <button
                                type="button"
                                class="flex w-full items-center justify-between rounded-lg px-2.5 py-2 text-left text-sm transition"
                                x-bind:class="twVersion === 'v3' ? 'bg-zinc-100 font-medium text-zinc-900' : 'text-zinc-400 hover:text-zinc-700'"
                                x-on:click="twVersion = 'v3'"
                            >
                                <span>Tailwind 3</span>
                            </button>
                        </li>
                    </ul>
                </div>

                <div class="sm:px-4">
                    <p class="mb-3 text-[0.65rem] font-semibold uppercase tracking-wider text-zinc-400">Formato</p>
                    <ul class="space-y-1">
                        <li>
                            <button
                                type="button"
                                class="flex w-full items-center justify-between rounded-lg px-2.5 py-2 text-left text-sm transition"
                                x-bind:class="exportFormat === 'hex' ? 'bg-zinc-100 font-medium text-zinc-900' : 'text-zinc-400 hover:text-zinc-700'"
                                x-on:click="exportFormat = 'hex'"
                            >
                                <span>Hex code</span>
                            </button>
                        </li>
                        <li>
                            <button
                                type="button"
                                class="flex w-full items-center justify-between rounded-lg px-2.5 py-2 text-left text-sm transition"
                                x-bind:class="exportFormat === 'rgb' ? 'bg-zinc-100 font-medium text-zinc-900' : 'text-zinc-400 hover:text-zinc-700'"
                                x-on:click="exportFormat = 'rgb'"
                            >
                                <span>RGB</span>
                            </button>
                        </li>
                    </ul>
                </div>

                <div class="relative min-h-[14rem] overflow-hidden rounded-2xl bg-zinc-950 sm:ml-4">
                    <button
                        type="button"
                        class="absolute right-3 top-3 z-10 inline-flex items-center gap-1.5 rounded-full bg-zinc-800 px-3 py-1.5 text-xs font-medium text-zinc-200 transition hover:bg-zinc-700"
                        x-on:click="copyExport()"
                    >
                        <x-icon name="clipboard" class="size-3.5" />
                        <span x-text="copied ? 'Copiado' : 'Copy to clipboard'"></span>
                    </button>
                    <pre class="max-h-[18rem] overflow-auto p-4 pt-12 font-mono text-[0.72rem] leading-relaxed text-zinc-300"><code x-html="exportCodeHtml"></code></pre>
                </div>
            </div>

            <div class="mt-6 space-y-4 border-t border-zinc-100 pt-6" x-show="active">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-zinc-400">Uso rápido — sólido</p>
                    <pre class="mt-2 overflow-x-auto rounded-xl border border-zinc-200 bg-zinc-50 p-4 font-mono text-xs leading-relaxed text-zinc-800"><code x-text="active ? `<div class=&quot;${solidTw}&quot;>\n  ${active.name}\n</div>` : ''"></code></pre>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-zinc-400">Uso rápido — degradado</p>
                    <pre class="mt-2 overflow-x-auto rounded-xl border border-zinc-200 bg-zinc-50 p-4 font-mono text-xs leading-relaxed text-zinc-800"><code x-text="active ? `<div class=&quot;${gradTw}&quot;>\n  ${active.name}\n</div>` : ''"></code></pre>
                </div>
            </div>
        </div>
    </div>
</div>

@script
<script>
Alpine.data('colorExportModal', (columns) => ({
    open: false,
    leaving: false,
    active: null,
    gradientTo: '#171717',
    columns,
    twVersion: 'v3',
    exportFormat: 'hex',
    copied: false,
    _copyTimer: null,

    show(column, index) {
        const swatch = this.columns?.[column]?.[index] ?? null;
        if (! swatch) return;
        const next = this.columns[column][index + 1] ?? this.columns[column][index - 1] ?? null;
        this.active = swatch;
        this.gradientTo = next?.hex ?? '#171717';
        this.copied = false;
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
    },

    slugify(name) {
        return String(name || 'color')
            .normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '')
            .toLowerCase()
            .replace(/[^a-z0-9]+/g, '-')
            .replace(/^-+|-+$/g, '') || 'color';
    },

    parseHex(hex) {
        const raw = String(hex || '').replace('#', '');
        if (raw.length !== 6) return [0, 0, 0];
        return [
            parseInt(raw.slice(0, 2), 16),
            parseInt(raw.slice(2, 4), 16),
            parseInt(raw.slice(4, 6), 16),
        ];
    },

    toHex(r, g, b) {
        const h = (n) => Math.max(0, Math.min(255, Math.round(n))).toString(16).padStart(2, '0');
        return `#${h(r)}${h(g)}${h(b)}`;
    },

    mix(hex, towardRgb, amount) {
        const [r, g, b] = this.parseHex(hex);
        const [tr, tg, tb] = towardRgb;
        return this.toHex(
            r + (tr - r) * amount,
            g + (tg - g) * amount,
            b + (tb - b) * amount,
        );
    },

    shadeMap(hex) {
        const light = { 50: 0.95, 100: 0.9, 200: 0.75, 300: 0.55, 400: 0.3 };
        const dark = { 600: 0.12, 700: 0.3, 800: 0.48, 900: 0.64, 950: 0.8 };
        const map = { 500: this.toHex(...this.parseHex(hex)) };

        for (const [step, amount] of Object.entries(light)) {
            map[step] = this.mix(hex, [255, 255, 255], amount);
        }
        for (const [step, amount] of Object.entries(dark)) {
            map[step] = this.mix(hex, [0, 0, 0], amount);
        }

        return map;
    },

    get shadeSteps() {
        return this.twVersion === 'v2'
            ? ['50', '100', '200', '300', '400', '500', '600', '700', '800', '900']
            : ['50', '100', '200', '300', '400', '500', '600', '700', '800', '900', '950'];
    },

    get shadeEntries() {
        if (! this.active) return [];
        const map = this.shadeMap(this.active.hex);
        return this.shadeSteps.map((step) => ({ step, value: map[step] }));
    },

    formatValue(hex) {
        if (this.exportFormat === 'rgb') {
            const [r, g, b] = this.parseHex(hex);
            return `rgb(${r} ${g} ${b})`;
        }
        return hex.toLowerCase();
    },

    get exportCode() {
        if (! this.active) return '';
        const key = this.slugify(this.active.name);
        const lines = this.shadeEntries.map(
            ({ step, value }) => `    '${step}': '${this.formatValue(value)}',`,
        );
        return `'${key}': {\n${lines.join('\n')}\n  },`;
    },

    get exportCodeHtml() {
        if (! this.active) return '';
        const key = this.slugify(this.active.name);
        const rows = this.shadeEntries.map(({ step, value }) => {
            const formatted = this.formatValue(value);
            return `  <span class="text-zinc-100">'${step}'</span>: <span class="text-sky-300">'${formatted}'</span>,`;
        });
        return `<span class="text-zinc-100">'${key}'</span>: {\n${rows.join('\n')}\n},`;
    },

    async copyExport() {
        if (! this.exportCode) return;
        try {
            await navigator.clipboard.writeText(this.exportCode);
            this.copied = true;
            clearTimeout(this._copyTimer);
            this._copyTimer = setTimeout(() => { this.copied = false; }, 1600);
        } catch (_) {
            // ignore clipboard failures (permissions / insecure context)
        }
    },
}));
</script>
@endscript
