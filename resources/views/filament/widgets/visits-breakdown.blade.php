{{--
    Desglose de visitas: cada lista muestra el nombre, la cantidad y su parte
    del total, con una barra fina debajo (un solo color: la barra es cantidad,
    medida contra el primero de la lista).
--}}
@php
    $sections = $this->getSections();
    $total = $this->getTotal();
@endphp

<x-filament-widgets::widget>
    <x-filament::section heading="De dónde y con qué" :description="$total.' '.($total === 1 ? 'visita' : 'visitas').' en el período'">
        <x-slot name="afterHeader">
            <x-filament::input.wrapper>
                <x-filament::input.select wire:model.live="days" aria-label="Período">
                    @foreach (\App\Filament\Widgets\VisitsBreakdown::RANGES as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </x-filament::input.select>
            </x-filament::input.wrapper>
        </x-slot>

        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
            @foreach ($sections as $key => $section)
                @php
                    $max = max(1, collect($section['rows'])->max('total') ?? 1);
                @endphp

                <div wire:key="visits-{{ $key }}" class="rounded-2xl bg-(--panel-row) p-5">
                    <h3 class="mb-4 text-sm font-medium text-(--panel-ink)">{{ $section['title'] }}</h3>

                    @if ($section['rows'] === [])
                        <p class="text-sm text-(--panel-muted)">
                            {{ $key === 'cities' ? 'Activá los headers de ubicación en Cloudflare para ver ciudades.' : 'Sin visitas todavía.' }}
                        </p>
                    @else
                        <ul class="grid gap-3.5">
                            @foreach ($section['rows'] as $row)
                                <li title="{{ $row['label'] }}: {{ $row['total'] }}">
                                    <div class="flex items-baseline justify-between gap-3 text-sm">
                                        <span class="truncate text-(--panel-ink)/80">{{ $row['label'] }}</span>
                                        <span class="shrink-0 tabular-nums text-(--panel-ink)">
                                            {{ number_format($row['total'], 0, ',', '.') }}
                                            <span class="ms-1 text-xs text-(--panel-muted)">{{ $total > 0 ? round($row['total'] / $total * 100) : 0 }}%</span>
                                        </span>
                                    </div>
                                    <div class="mt-1.5 h-1.5 rounded-full bg-(--panel-line)">
                                        <div class="h-full rounded-full bg-primary-400" style="width: {{ round($row['total'] / $max * 100, 1) }}%"></div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            @endforeach
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
