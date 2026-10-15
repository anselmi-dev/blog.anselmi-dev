<?php

namespace App\Filament\Widgets;

use App\Models\PageView;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

/**
 * Dashboard: cuántas visitas tuvo el sitio público y cuántas personas distintas.
 */
class VisitsOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 3;

    protected ?string $heading = 'Visitas';

    protected ?string $description = 'Páginas vistas del sitio público (sin contarme a mí ni a los bots).';

    protected ?string $pollingInterval = null;

    protected int|array|null $columns = ['default' => 1, 'sm' => 2, 'lg' => 4];

    /**
     * @return array<Stat>
     */
    protected function getStats(): array
    {
        $today = PageView::query()->since(today())->count();
        $yesterday = PageView::query()->since(today()->subDay())->where('created_at', '<', today())->count();
        $week = PageView::query()->since(today()->subDays(6))->count();
        $month = PageView::query()->since(today()->subDays(29))->count();
        $visitors = PageView::query()->since(today()->subDays(29))->distinct()->count('visitor_hash');

        return [
            Stat::make('Hoy', $today)
                ->description("Ayer {$yesterday}")
                ->icon(Heroicon::OutlinedEye),
            Stat::make('Últimos 7 días', $week)
                ->description('Páginas vistas')
                ->icon(Heroicon::OutlinedCalendarDays)
                ->chart(self::dailyCounts(7))
                ->color('primary'),
            Stat::make('Últimos 30 días', $month)
                ->description('Páginas vistas')
                ->icon(Heroicon::OutlinedChartBar)
                ->chart(self::dailyCounts(30))
                ->color('primary'),
            Stat::make('Visitantes en 30 días', $visitors)
                ->description($visitors > 0 ? number_format($month / $visitors, 1, ',', '.').' páginas por visitante' : 'Todavía sin visitas')
                ->icon(Heroicon::OutlinedUsers),
        ];
    }

    /**
     * Visitas por día de los últimos $days días (incluido hoy), del más viejo al más nuevo.
     *
     * @return list<int>
     */
    public static function dailyCounts(int $days): array
    {
        $start = today()->subDays($days - 1);

        $counts = PageView::query()
            ->since($start)
            ->selectRaw('DATE(created_at) as day, COUNT(*) as total')
            ->groupBy('day')
            ->pluck('total', 'day');

        return collect(range(0, $days - 1))
            ->map(fn (int $offset): int => (int) ($counts[$start->copy()->addDays($offset)->toDateString()] ?? 0))
            ->all();
    }
}
