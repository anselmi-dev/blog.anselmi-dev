@push('meta')
    <link rel="preconnect" href="https://fonts.bunny.net">
    @foreach ($fonts as $font)
        <link href="{{ $font['bunny'] }}" rel="stylesheet" />
    @endforeach
@endpush

@section('header-content')
    <x-hero-content>
        <p class="mb-2 font-folio-mono text-xs font-medium uppercase tracking-widest text-zinc-600">
            Servicios
        </p>
        <h1 class="text-3xl font-bold tracking-tight text-zinc-900 sm:text-4xl">
            Mis fuentes
        </h1>
        <p class="mt-4 max-w-2xl text-base leading-relaxed text-zinc-600 sm:text-lg">
            Tipografías que uso o pruebo en proyectos. Tocá una para ver cómo cargarla con CSS o Tailwind.
        </p>
    </x-hero-content>
@endsection

<div
    class="pb-16 pt-4 sm:pb-20 sm:pt-6 lg:pb-24"
    x-data="{
        open: false,
        leaving: false,
        active: null,
        fonts: @js($fonts),
        show(slug) {
            this.active = this.fonts[slug] ?? null;
            if (! this.active) return;
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
        }
    }"
    x-on:keydown.escape.window="open && close()"
>
    <div
        class="mx-auto max-w-8xl space-y-3"
        data-reveal="fade-up"
        data-reveal-scroll
        data-reveal-stagger="0.07"
        data-reveal-once
    >
        @foreach ($fonts as $slug => $font)
            <button
                type="button"
                data-reveal-item
                x-on:click="show(@js($slug))"
                class="group flex w-full cursor-pointer flex-col gap-3 rounded-2xl border border-zinc-200 bg-brand-lime-50 px-5 py-6 text-left transition hover:border-zinc-900/25 hover:bg-brand-lime-100 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-zinc-900 sm:flex-row sm:items-end sm:justify-between sm:px-8 sm:py-8"
            >
                <div class="min-w-0">
                    <p class="font-folio-mono text-[0.7rem] uppercase tracking-widest text-zinc-500">
                        {{ $font['category'] }} · {{ $font['weights'] }}
                    </p>
                    <p class="mt-2 text-sm font-semibold text-zinc-900">{{ $font['name'] }}</p>
                </div>
                <p
                    class="max-w-full truncate text-3xl font-medium tracking-tight text-zinc-900 transition group-hover:translate-x-0.5 sm:text-4xl lg:text-5xl"
                    style="font-family: {{ $font['family'] }}"
                >
                    {{ $font['sample'] }}
                </p>
            </button>
        @endforeach
    </div>

    <div
        x-show="open"
        x-cloak
        class="fixed inset-0 z-[200] flex items-center justify-center p-3 sm:p-6"
        role="dialog"
        aria-modal="true"
        aria-labelledby="font-modal-title"
        data-reveal-pause
    >
        <div
            class="absolute inset-0 cursor-pointer bg-zinc-900/50 backdrop-blur-md"
            x-bind:class="leaving ? 'motion-safe:animate-contact-backdrop-out' : 'motion-safe:animate-contact-backdrop-in'"
            x-on:click="close()"
            aria-hidden="true"
        ></div>

        <div
            class="relative z-10 max-h-[min(92vh,50rem)] w-full max-w-2xl overflow-y-auto rounded-3xl bg-white p-6 shadow-[0_25px_80px_-20px_rgba(15,23,42,0.35)] sm:p-8"
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

            <p class="pr-12 font-folio-mono text-[0.7rem] uppercase tracking-widest text-zinc-500" x-text="active?.category"></p>
            <h2 id="font-modal-title" class="mt-2 pr-12 text-2xl font-bold tracking-tight text-zinc-900 sm:text-3xl" x-text="active?.name"></h2>
            <p class="mt-2 text-sm text-zinc-600" x-show="active?.note" x-text="active?.note"></p>

            <p
                class="mt-8 rounded-2xl bg-brand-lime px-5 py-8 text-center text-4xl font-medium tracking-tight text-zinc-900 sm:text-5xl"
                x-bind:style="active ? `font-family: ${active.family}` : ''"
                x-text="active?.sample"
            ></p>

            <div class="mt-8 space-y-5" x-show="active">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-zinc-400">Google Fonts</p>
                    <a
                        x-bind:href="active?.google"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="mt-1 inline-flex items-center gap-1.5 text-sm font-medium text-zinc-900 underline decoration-zinc-300 underline-offset-4 transition hover:decoration-zinc-900"
                    >
                        Abrir ficha
                        <x-icon name="arrow-up-right" class="size-3.5" />
                    </a>
                </div>

                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-zinc-400">CSS</p>
                    <pre class="mt-2 overflow-x-auto rounded-xl border border-zinc-200 bg-zinc-50 p-4 font-mono text-xs leading-relaxed text-zinc-800"><code x-text="active ? `<link href=&quot;${active.bunny}&quot; rel=&quot;stylesheet&quot; />\n\n.element {\n  ${active.css}\n}` : ''"></code></pre>
                </div>

                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-zinc-400">Tailwind</p>
                    <pre class="mt-2 overflow-x-auto rounded-xl border border-zinc-200 bg-zinc-50 p-4 font-mono text-xs leading-relaxed text-zinc-800"><code x-text="active ? `<p class=&quot;${active.tailwind} text-4xl&quot;>\n  ${active.sample}\n</p>` : ''"></code></pre>
                </div>
            </div>
        </div>
    </div>
</div>
