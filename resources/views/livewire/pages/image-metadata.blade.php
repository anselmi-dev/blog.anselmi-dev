@section('header-content')
    <x-hero-content>
        <p class="mb-2 font-folio-mono text-xs font-medium uppercase tracking-widest text-zinc-600">
            Herramienta
        </p>
        <h1 class="text-3xl font-bold tracking-tight text-zinc-900 sm:text-4xl">
            Lector de metadatos EXIF
        </h1>
        <p class="mt-4 max-w-2xl text-base leading-relaxed text-zinc-600 sm:text-lg">
            Descubrí los ajustes de cámara, lente y captura guardados en tus fotografías.
        </p>
    </x-hero-content>
@endsection

@php
    $sections = [
        'camera' => ['title' => 'Cámara y lente', 'icon' => 'camera'],
        'capture' => ['title' => 'Ajustes de captura', 'icon' => 'aperture'],
        'image' => ['title' => 'Imagen', 'icon' => 'image'],
        'author' => ['title' => 'Autoría', 'icon' => 'copyright'],
        'location' => ['title' => 'Ubicación GPS', 'icon' => 'map-pin'],
    ];

    $labels = [
        'make' => 'Fabricante',
        'model' => 'Modelo',
        'lens' => 'Lente',
        'camera_serial' => 'Serie de cámara',
        'lens_serial' => 'Serie del lente',
        'captured_at' => 'Fecha de captura',
        'shutter_speed' => 'Velocidad',
        'aperture' => 'Apertura',
        'iso' => 'ISO',
        'focal_length' => 'Distancia focal',
        'focal_length_35mm' => 'Equivalente 35 mm',
        'exposure_program' => 'Programa',
        'exposure_bias' => 'Compensación',
        'metering_mode' => 'Medición',
        'white_balance' => 'Balance de blancos',
        'flash' => 'Flash',
        'width' => 'Ancho',
        'height' => 'Alto',
        'orientation' => 'Orientación',
        'color_space' => 'Espacio de color',
        'software' => 'Software',
        'resolution' => 'Resolución',
        'artist' => 'Autor',
        'copyright' => 'Copyright',
        'latitude' => 'Latitud',
        'longitude' => 'Longitud',
        'altitude' => 'Altitud',
    ];
@endphp

<div class="pb-16 pt-4 sm:pb-20 sm:pt-6 lg:pb-24">
    <div class="mx-auto max-w-6xl" data-image-metadata-upload>
        <section
            class="rounded-3xl border border-zinc-200 bg-brand-lime-50 p-4 sm:p-6 lg:p-8"
            aria-labelledby="upload-title"
        >
            <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="font-folio-mono text-[0.7rem] font-medium uppercase tracking-widest text-zinc-500">
                        JPEG · TIFF · Máximo 50 MB
                    </p>
                    <h2 id="upload-title" class="mt-2 text-xl font-bold tracking-tight text-zinc-900 sm:text-2xl">
                        Subí una fotografía
                    </h2>
                </div>
                <p class="max-w-md text-sm leading-relaxed text-zinc-500">
                    El archivo se analiza de forma temporal y no se guarda en la galería.
                </p>
            </div>

            <label
                for="metadata-image"
                data-image-drop-zone
                class="relative mt-6 flex min-h-72 cursor-pointer flex-col items-center justify-center overflow-hidden rounded-2xl border-2 border-dashed border-zinc-300 bg-white px-6 py-12 text-center transition duration-200 hover:border-zinc-500 hover:bg-zinc-50 data-[active=true]:scale-[0.995] data-[active=true]:border-zinc-900 data-[active=true]:bg-brand-lime-100 focus-within:outline-2 focus-within:outline-offset-4 focus-within:outline-zinc-900"
            >
                <input
                    id="metadata-image"
                    data-image-input
                    type="file"
                    wire:model="image"
                    accept=".jpg,.jpeg,.tif,.tiff,image/jpeg,image/tiff"
                    class="sr-only"
                />

                <div wire:loading.remove wire:target="image" class="flex flex-col items-center">
                    <span
                        class="inline-flex size-16 items-center justify-center rounded-2xl border border-zinc-200 bg-brand-lime-50 text-zinc-900"
                        aria-hidden="true"
                    >
                        <x-icon name="scan-search" class="size-7" />
                    </span>
                    <span class="mt-5 text-base font-bold tracking-tight text-zinc-900 sm:text-lg">
                        Arrastrá tu imagen hasta acá
                    </span>
                    <span class="mt-2 text-sm leading-relaxed text-zinc-500">
                        o hacé clic para buscarla en tu equipo
                    </span>
                    <span class="mt-5 inline-flex items-center gap-2 rounded-full bg-zinc-900 px-4 py-2 text-sm font-semibold text-white">
                        <x-icon name="upload" class="size-4" />
                        Elegir imagen
                    </span>
                </div>

                <div
                    wire:loading.flex
                    wire:target="image"
                    class="absolute inset-0 flex-col items-center justify-center bg-white/95 px-6"
                    role="status"
                    aria-live="polite"
                >
                    <span class="relative inline-flex size-16 items-center justify-center rounded-full bg-brand-lime-100 text-zinc-900">
                        <span class="absolute inset-0 animate-ping rounded-full bg-brand-lime-200 opacity-50"></span>
                        <x-icon name="scan-line" class="relative size-7 animate-pulse" />
                    </span>
                    <span class="mt-5 text-base font-bold text-zinc-900">Leyendo la fotografía…</span>
                    <span class="mt-1 text-sm text-zinc-500" data-upload-status>Preparando la carga</span>
                    <div class="mt-5 h-1.5 w-full max-w-xs overflow-hidden rounded-full bg-zinc-100">
                        <div
                            data-upload-progress
                            class="h-full w-0 rounded-full bg-zinc-900 transition-[width] duration-200"
                        ></div>
                    </div>
                </div>
            </label>

            @error('image')
                <p class="mt-3 flex items-center gap-2 text-sm font-medium text-red-700" role="alert">
                    <x-icon name="circle-alert" class="size-4" />
                    {{ $message }}
                </p>
            @enderror
        </section>

        @if ($image && $file !== [])
            <section
                class="mt-6 overflow-hidden rounded-3xl border border-zinc-200 bg-white"
                aria-labelledby="file-title"
            >
                <div class="grid lg:grid-cols-[minmax(0,0.75fr)_minmax(0,1.25fr)]">
                    <div class="flex min-h-72 items-center justify-center overflow-hidden bg-zinc-100">
                        @if (($file['mime'] ?? null) === 'image/jpeg')
                            <img
                                src="{{ $image->temporaryUrl() }}"
                                alt="Vista previa de {{ $file['name'] }}"
                                class="h-full max-h-128 w-full object-contain"
                            />
                        @else
                            <div class="flex flex-col items-center px-6 text-center text-zinc-500">
                                <x-icon name="image" class="size-12" />
                                <p class="mt-3 text-sm">La vista previa TIFF no está disponible en el navegador.</p>
                            </div>
                        @endif
                    </div>

                    <div class="flex flex-col justify-between p-5 sm:p-7 lg:p-8">
                        <div>
                            <div class="flex items-start justify-between gap-4">
                                <div class="min-w-0">
                                    <p class="font-folio-mono text-[0.7rem] font-medium uppercase tracking-widest text-zinc-500">
                                        Archivo analizado
                                    </p>
                                    <h2 id="file-title" class="mt-2 truncate text-xl font-bold tracking-tight text-zinc-900">
                                        {{ $file['name'] }}
                                    </h2>
                                </div>
                                <button
                                    type="button"
                                    wire:click="removeImage"
                                    class="inline-flex size-10 shrink-0 cursor-pointer items-center justify-center rounded-full border border-zinc-200 text-zinc-500 transition hover:border-zinc-400 hover:text-zinc-900 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-zinc-900"
                                    aria-label="Quitar imagen"
                                >
                                    <x-icon name="x" class="size-4" />
                                </button>
                            </div>

                            <dl class="mt-6 grid grid-cols-2 gap-3">
                                <div class="rounded-xl bg-zinc-50 p-3">
                                    <dt class="text-xs font-medium text-zinc-500">Tamaño</dt>
                                    <dd class="mt-1 text-sm font-semibold text-zinc-900">{{ $file['size'] }}</dd>
                                </div>
                                <div class="rounded-xl bg-zinc-50 p-3">
                                    <dt class="text-xs font-medium text-zinc-500">Formato</dt>
                                    <dd class="mt-1 truncate text-sm font-semibold text-zinc-900">{{ $file['mime'] }}</dd>
                                </div>
                            </dl>
                        </div>

                        @if ($analysisMessage)
                            <p
                                class="mt-6 flex items-start gap-2 rounded-xl border border-zinc-200 bg-brand-lime-50 p-3 text-sm leading-relaxed text-zinc-700"
                                role="status"
                            >
                                <x-icon
                                    name="{{ $metadata === [] ? 'info' : 'circle-check' }}"
                                    class="mt-0.5 size-4 shrink-0"
                                />
                                {{ $analysisMessage }}
                            </p>
                        @endif
                    </div>
                </div>
            </section>
        @endif

        @if ($metadata !== [])
            <section class="mt-10" aria-labelledby="metadata-title">
                <div class="flex items-end justify-between gap-4">
                    <div>
                        <p class="font-folio-mono text-[0.7rem] font-medium uppercase tracking-widest text-zinc-500">
                            Resultado
                        </p>
                        <h2 id="metadata-title" class="mt-2 text-2xl font-bold tracking-tight text-zinc-900 sm:text-3xl">
                            Datos encontrados
                        </h2>
                    </div>
                    <span class="hidden text-sm text-zinc-500 sm:block">
                        {{ collect($metadata)->flatten()->count() }} campos
                    </span>
                </div>

                <div class="mt-6 grid grid-cols-1 gap-4 md:grid-cols-2">
                    @foreach ($metadata as $sectionKey => $values)
                        <article
                            wire:key="metadata-section-{{ $sectionKey }}"
                            @class([
                                'rounded-2xl border border-zinc-200 bg-white p-5 sm:p-6',
                                'md:col-span-2' => $sectionKey === 'capture',
                            ])
                        >
                            <div class="flex items-center gap-3 border-b border-zinc-100 pb-4">
                                <span class="inline-flex size-10 items-center justify-center rounded-xl bg-brand-lime-100 text-zinc-900">
                                    <x-icon :name="$sections[$sectionKey]['icon'] ?? 'file-scan'" class="size-5" />
                                </span>
                                <div>
                                    <h3 class="font-bold tracking-tight text-zinc-900">
                                        {{ $sections[$sectionKey]['title'] ?? ucfirst($sectionKey) }}
                                    </h3>
                                    @if ($sectionKey === 'location')
                                        <p class="mt-0.5 text-xs text-amber-700">Estos datos pueden revelar dónde se tomó la foto.</p>
                                    @endif
                                </div>
                            </div>

                            <dl @class([
                                'mt-1 divide-y divide-zinc-100',
                                'sm:grid sm:grid-cols-2 sm:gap-x-8 sm:divide-y-0' => $sectionKey === 'capture',
                            ])>
                                @foreach ($values as $key => $value)
                                    <div
                                        wire:key="metadata-{{ $sectionKey }}-{{ $key }}"
                                        class="flex items-start justify-between gap-4 py-3 sm:border-b sm:border-zinc-100"
                                    >
                                        <dt class="text-sm text-zinc-500">{{ $labels[$key] ?? ucfirst($key) }}</dt>
                                        <dd class="max-w-[60%] wrap-break-word text-right font-folio-mono text-sm font-semibold text-zinc-900">
                                            {{ $value }}
                                        </dd>
                                    </div>
                                @endforeach
                            </dl>
                        </article>
                    @endforeach
                </div>
            </section>
        @endif

        <aside class="mt-8 flex items-start gap-3 rounded-2xl bg-zinc-50 p-4 text-sm leading-relaxed text-zinc-600 sm:p-5">
            <x-icon name="shield-check" class="mt-0.5 size-5 shrink-0 text-zinc-900" />
            <p>
                <strong class="font-semibold text-zinc-900">Privacidad:</strong>
                el análisis ocurre en un archivo temporal. Evitá compartir públicamente coordenadas GPS o números de serie.
            </p>
        </aside>
    </div>
</div>
