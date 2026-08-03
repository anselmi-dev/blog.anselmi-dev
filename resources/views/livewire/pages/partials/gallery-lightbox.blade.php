@if ($lightboxShow && $lightboxIndex !== null && ($items[$lightboxIndex]['kind'] ?? '') === 'photo')
    @php
        $lb = $items[$lightboxIndex];
        $lbUrl = $this->lightboxImageUrl($lb);
        $photoCount = count($this->photoOnlyIndices());
    @endphp

    <div
        class="fixed inset-0 z-[185] flex items-center justify-center p-2 sm:p-4"
        role="dialog"
        aria-modal="true"
        @if ($lightboxImageOnly)
            aria-label="Foto: {{ $lb['title'] }}"
        @else
            aria-labelledby="gallery-lightbox-title"
        @endif
        wire:keydown.escape.window="closeLightbox"
        wire:keydown.arrow-right.window="lightboxNext"
        wire:keydown.arrow-left.window="lightboxPrev"
    >
        <div
            @class([
                'absolute inset-0 cursor-pointer bg-zinc-900/55 backdrop-blur-md dark:bg-black/65',
                'motion-safe:animate-gallery-lightbox-backdrop-in' => ! $lightboxLeaving,
                'motion-safe:animate-gallery-lightbox-backdrop-out' => $lightboxLeaving,
            ])
            wire:click="closeLightbox"
            aria-hidden="true"
        ></div>

        <div
            @class([
                'relative z-10 flex w-full flex-col overflow-hidden rounded-2xl border border-zinc-200/90 bg-white shadow-[0_25px_80px_-20px_rgba(15,23,42,0.35)] ring-1 ring-zinc-950/[0.04] dark:border-zinc-700 dark:ring-white/[0.08]',
                'motion-safe:animate-gallery-lightbox-panel-in' => ! $lightboxLeaving,
                'motion-safe:animate-gallery-lightbox-panel-out' => $lightboxLeaving,
                'max-h-[min(92vh,52rem)] max-w-2xl sm:max-w-3xl' => ! $lightboxImageOnly,
                'max-h-[min(94vh,100dvh)] max-w-[min(96vw,90rem)]' => $lightboxImageOnly,
            ])
            wire:click.stop
        >
            <div class="absolute right-3 top-3 z-30 flex max-w-[calc(100%-1.5rem)] flex-wrap items-center justify-end gap-1.5 sm:gap-2">
                @if ($photoCount > 1)
                    <button
                        type="button"
                        class="inline-flex size-10 cursor-pointer items-center justify-center rounded-full border border-zinc-200 bg-white/95 text-zinc-600 shadow-sm transition hover:border-zinc-300 hover:bg-white hover:text-zinc-900 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-zinc-900 dark:border-zinc-700 dark:bg-zinc-900/95 dark:text-zinc-300 dark:hover:border-zinc-600 dark:hover:bg-zinc-900"
                        wire:click="lightboxPrev"
                        aria-label="Foto anterior"
                    >
                        <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path d="M15 6l-6 6 6 6" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </button>
                    <button
                        type="button"
                        class="inline-flex size-10 cursor-pointer items-center justify-center rounded-full border border-zinc-200 bg-white/95 text-zinc-600 shadow-sm transition hover:border-zinc-300 hover:bg-white hover:text-zinc-900 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-zinc-900 dark:border-zinc-700 dark:bg-zinc-900/95 dark:text-zinc-300 dark:hover:border-zinc-600 dark:hover:bg-zinc-900"
                        wire:click="lightboxNext"
                        aria-label="Foto siguiente"
                    >
                        <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path d="M9 6l6 6-6 6" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </button>
                @endif
                <button
                    type="button"
                    class="inline-flex size-10 cursor-pointer items-center justify-center rounded-full border border-zinc-200 bg-white/95 text-zinc-600 shadow-sm transition hover:border-zinc-300 hover:bg-white hover:text-zinc-900 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-zinc-900 dark:border-zinc-700 dark:bg-zinc-900/95 dark:text-zinc-300 dark:hover:border-zinc-600 dark:hover:bg-zinc-900"
                    wire:click="toggleLightboxImageOnly"
                    aria-pressed="{{ $lightboxImageOnly ? 'true' : 'false' }}"
                    aria-label="{{ $lightboxImageOnly ? 'Mostrar detalles de la foto' : 'Maximizar solo imagen' }}"
                >
                    @if ($lightboxImageOnly)
                        {{-- Restaurar / ver detalles --}}
                        <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path d="M8 3H5a2 2 0 00-2 2v3m18 0V5a2 2 0 00-2-2h-3m0 18h3a2 2 0 002-2v-3M3 16v3a2 2 0 002 2h3" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    @else
                        {{-- Maximizar --}}
                        <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path d="M15 3h6v6M9 21H3v-6M21 3l-7 7M3 21l7-7" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    @endif
                </button>
                <button
                    type="button"
                    class="inline-flex size-10 cursor-pointer items-center justify-center rounded-full border border-zinc-200 bg-white/95 text-zinc-600 shadow-sm transition hover:border-zinc-300 hover:bg-white hover:text-zinc-900 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-zinc-900 dark:border-zinc-700 dark:bg-zinc-900/95 dark:text-zinc-300 dark:hover:border-zinc-600 dark:hover:bg-zinc-900"
                    wire:click="closeLightbox"
                    aria-label="Cerrar"
                >
                    <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path d="M6 6l12 12M18 6L6 18" stroke-linecap="round" />
                    </svg>
                </button>
            </div>

            {{-- Imagen --}}
            <div
                @class([
                    'relative bg-zinc-900',
                    'min-h-0 shrink-0' => ! $lightboxImageOnly,
                    'flex min-h-0 flex-1 flex-col' => $lightboxImageOnly,
                ])
            >
                <div
                    @class([
                        'relative w-full',
                        'max-h-[min(48vh,22rem)] sm:max-h-[min(52vh,26rem)]' => ! $lightboxImageOnly,
                        'flex max-h-[min(88vh,85dvh)] min-h-0 flex-1 items-center justify-center px-2 pb-2 pt-14 sm:px-4 sm:pb-4' => $lightboxImageOnly,
                    ])
                >
                    <img
                        wire:key="lightbox-img-{{ $lightboxIndex }}"
                        src="{{ $lbUrl }}"
                        alt="{{ $lb['title'] }} — {{ $lb['category'] }}"
                        @class([
                            'mx-auto block h-full w-full object-contain',
                            'max-h-[min(48vh,22rem)] sm:max-h-[min(52vh,26rem)]' => ! $lightboxImageOnly,
                            'max-h-[min(82vh,80dvh)] max-w-full' => $lightboxImageOnly,
                        ])
                    />
                </div>
            </div>

            @if (! $lightboxImageOnly)
                {{-- Footer blanco --}}
                <footer class="flex flex-col gap-4 overflow-y-auto border-t border-zinc-200/90 bg-white p-5 sm:p-6">
                    <div class="min-w-0">
                        <p class="text-xs font-medium text-zinc-500">
                            {{ $lb['released_at'] ?? '' }}
                        </p>
                        <h2
                            id="gallery-lightbox-title"
                            class="mt-1 text-lg font-bold tracking-tight text-zinc-900 sm:text-xl"
                        >
                            {{ $lb['title'] }}
                        </h2>
                        @if (! empty($lb['description']))
                            <p class="mt-3 text-sm leading-relaxed text-zinc-600">
                                {{ $lb['description'] }}
                            </p>
                        @endif
                    </div>

                    <dl class="grid grid-cols-1 gap-x-6 gap-y-2 text-sm sm:grid-cols-2">
                        @if (! empty($lb['location']))
                            <div class="flex flex-col gap-0.5">
                                <dt class="text-xs font-semibold uppercase tracking-wider text-zinc-400">Ubicación</dt>
                                <dd class="font-medium text-zinc-800">{{ $lb['location'] }}</dd>
                            </div>
                        @endif
                        @if (! empty($lb['released_at']))
                            <div class="flex flex-col gap-0.5">
                                <dt class="text-xs font-semibold uppercase tracking-wider text-zinc-400">Publicación</dt>
                                <dd class="font-medium text-zinc-800">{{ $lb['released_at'] }}</dd>
                            </div>
                        @endif
                        @if (! empty($lb['camera']))
                            <div class="flex flex-col gap-0.5">
                                <dt class="text-xs font-semibold uppercase tracking-wider text-zinc-400">Cámara</dt>
                                <dd class="font-medium text-zinc-800">{{ $lb['camera'] }}</dd>
                            </div>
                        @endif
                        @if (! empty($lb['iso']) || ! empty($lb['aperture']) || ! empty($lb['shutter']) || ! empty($lb['focal_length']))
                            <div class="flex flex-col gap-0.5 sm:col-span-2">
                                <dt class="text-xs font-semibold uppercase tracking-wider text-zinc-400">Técnica</dt>
                                <dd class="flex flex-wrap gap-x-3 gap-y-1 font-medium text-zinc-800">
                                    @if (! empty($lb['iso']))
                                        <span>ISO {{ $lb['iso'] }}</span>
                                    @endif
                                    @if (! empty($lb['aperture']))
                                        <span>{{ $lb['aperture'] }}</span>
                                    @endif
                                    @if (! empty($lb['shutter']))
                                        <span>{{ $lb['shutter'] }}</span>
                                    @endif
                                    @if (! empty($lb['focal_length']))
                                        <span>{{ $lb['focal_length'] }}</span>
                                    @endif
                                </dd>
                            </div>
                        @endif
                    </dl>

                    @if (! empty($lb['tags']) && is_array($lb['tags']))
                        <div class="flex flex-wrap gap-2">
                            @foreach ($lb['tags'] as $tag)
                                <span
                                    class="inline-flex rounded-full border border-zinc-200 bg-zinc-50 px-2.5 py-1 text-xs font-medium text-zinc-700"
                                >
                                    {{ $tag }}
                                </span>
                            @endforeach
                        </div>
                    @endif
                </footer>
            @endif
        </div>
    </div>
@endif
