{{--
    Desglose de visitas: listas con barra proporcional (un solo color: la barra
    es cantidad). Estilos en línea con las variables de Filament: el panel no
    compila las clases de Tailwind de vistas propias (no tiene tema).
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

        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(16rem,1fr));gap:1.5rem;">
            @foreach ($sections as $key => $section)
                @php
                    $max = max(1, collect($section['rows'])->max('total') ?? 1);
                @endphp
                <div wire:key="visits-{{ $key }}">
                    <h3 style="margin:0 0 0.5rem;font-size:0.875rem;font-weight:600;">{{ $section['title'] }}</h3>

                    @if ($section['rows'] === [])
                        <p style="margin:0;font-size:0.875rem;opacity:0.6;">
                            {{ $key === 'cities' ? 'Activá los headers de ubicación en Cloudflare para ver ciudades.' : 'Sin visitas todavía.' }}
                        </p>
                    @else
                        <ul style="margin:0;padding:0;list-style:none;display:grid;gap:4px;">
                            @foreach ($section['rows'] as $row)
                                <li style="position:relative;display:flex;justify-content:space-between;gap:0.75rem;padding:0.25rem 0.5rem;font-size:0.875rem;border-radius:4px;" title="{{ $row['label'] }}: {{ $row['total'] }}">
                                    <span aria-hidden="true" style="position:absolute;inset:0 auto 0 0;width:{{ round($row['total'] / $max * 100, 1) }}%;border-radius:4px;background:color-mix(in oklab, var(--primary-500) 18%, transparent);"></span>
                                    <span style="position:relative;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $row['label'] }}</span>
                                    <span style="position:relative;font-variant-numeric:tabular-nums;opacity:0.75;">{{ number_format($row['total'], 0, ',', '.') }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            @endforeach
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
