<?php

namespace App\Filament\Widgets;

use App\Models\PageView;
use App\Support\Country;
use Filament\Widgets\Widget;
use Illuminate\Database\Eloquent\Builder;

/**
 * Dashboard: de dónde vienen las visitas, qué miran y con qué dispositivo.
 */
class VisitsBreakdown extends Widget
{
    protected static ?int $sort = 4;

    protected string $view = 'filament.widgets.visits-breakdown';

    protected int|string|array $columnSpan = 'full';

    /** Días hacia atrás que se muestran. */
    public int $days = 30;

    /** @var array<int, string> */
    public const array RANGES = [7 => '7 días', 30 => '30 días', 90 => '90 días', 365 => '12 meses'];

    private const array DEVICES = ['desktop' => 'Escritorio', 'mobile' => 'Celular', 'tablet' => 'Tablet'];

    private const int LIMIT = 8;

    public function updatedDays(): void
    {
        if (! array_key_exists($this->days, self::RANGES)) {
            $this->days = 30;
        }
    }

    /**
     * @return array<string, array{title: string, rows: list<array{label: string, total: int}>}>
     */
    public function getSections(): array
    {
        return [
            'pages' => ['title' => 'Páginas', 'rows' => $this->top('path')],
            'countries' => ['title' => 'Países', 'rows' => $this->top('country', fn (?string $code): string => trim(Country::flag($code).' '.Country::name($code)))],
            'cities' => ['title' => 'Ciudades', 'rows' => $this->topCities()],
            'devices' => ['title' => 'Dispositivos', 'rows' => $this->top('device', fn (?string $device): string => self::DEVICES[$device] ?? 'Otro')],
            'browsers' => ['title' => 'Navegadores', 'rows' => $this->top('browser')],
            'systems' => ['title' => 'Sistemas', 'rows' => $this->top('os')],
            'referrers' => ['title' => 'De dónde llegan', 'rows' => $this->top('referrer_host', fn (?string $host): string => $host ?? 'Directo o sin dato')],
        ];
    }

    public function getTotal(): int
    {
        return $this->query()->count();
    }

    /**
     * @param  (callable(?string): string)|null  $label
     * @return list<array{label: string, total: int}>
     */
    private function top(string $column, ?callable $label = null): array
    {
        return $this->query()
            ->selectRaw("{$column} as value, COUNT(*) as total")
            ->groupBy($column)
            ->orderByDesc('total')
            ->limit(self::LIMIT)
            ->get()
            ->map(fn (PageView $row): array => [
                'label' => $label ? $label($row->value) : ($row->value ?? 'Sin dato'),
                'total' => (int) $row->total,
            ])
            ->all();
    }

    /**
     * Las ciudades solo llegan si Cloudflare manda los headers de ubicación.
     *
     * @return list<array{label: string, total: int}>
     */
    private function topCities(): array
    {
        return $this->query()
            ->whereNotNull('city')
            ->selectRaw('city, region, country, COUNT(*) as total')
            ->groupBy('city', 'region', 'country')
            ->orderByDesc('total')
            ->limit(self::LIMIT)
            ->get()
            ->map(fn (PageView $row): array => [
                'label' => trim(Country::flag($row->country).' '.collect([$row->city, $row->region])->filter()->unique()->implode(', ')),
                'total' => (int) $row->total,
            ])
            ->all();
    }

    private function query(): Builder
    {
        $days = array_key_exists($this->days, self::RANGES) ? $this->days : 30;

        return PageView::query()->since(today()->subDays($days - 1));
    }
}
