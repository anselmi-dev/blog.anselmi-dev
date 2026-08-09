<?php

declare(strict_types=1);

namespace Domains\Calendar\Livewire;

use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('calendar::layouts.domain')]
#[Title('Me Calendar')]
class Home extends Component
{
    public function render()
    {
        return view('calendar::livewire.home', [
            'heatmap' => $this->buildHeatmap(),
        ]);
    }

    /**
     * Mock heat map: columnas = semanas, filas = Lun→Dom.
     *
     * @return array{weeks: list<array<string, mixed>>, months: list<array{label: string, span: int}>, days: list<string>}
     */
    private function buildHeatmap(): array
    {
        $start = Carbon::parse('2026-05-04')->startOfWeek(Carbon::MONDAY);
        $end = Carbon::parse('2026-07-05')->endOfWeek(Carbon::SUNDAY);

        $intensityByDay = [
            1 => 3, 2 => 0, 3 => 1, 4 => 0, 5 => 2, 6 => 0, 7 => 1,
            8 => 2, 9 => 0, 10 => 1, 11 => 0, 12 => 3, 13 => 0, 14 => 0,
            15 => 1, 16 => 0, 17 => 2, 18 => 0, 19 => 1, 20 => 0, 21 => 0,
            22 => 0, 23 => 1, 24 => 0, 25 => 2, 26 => 0, 27 => 1, 28 => 0,
            29 => 0, 30 => 3, 31 => 1,
        ];

        $notesByDate = [
            '2026-05-01' => ['Kickoff Me Calendar', 'Boceto UI heat map'],
            '2026-05-08' => ['Notas de la semana'],
            '2026-05-12' => ['Idea: sync con mobile'],
            '2026-05-30' => ['Demo landing', 'Ajustar contraste'],
            '2026-06-05' => ['Evento: review diseño'],
            '2026-06-21' => ['Ship v0 visual', 'Checklist dominio'],
        ];

        $weeks = [];
        $cursor = $start->copy();

        while ($cursor->lte($end)) {
            $weekStart = $cursor->copy();
            $days = [];

            for ($i = 0; $i < 7; $i++) {
                $day = $weekStart->copy()->addDays($i);
                $key = $day->toDateString();
                $level = $intensityByDay[$day->day] ?? ($day->dayOfWeekIso % 4 === 0 ? 2 : ($day->day % 5 === 0 ? 1 : 0));

                if (isset($notesByDate[$key])) {
                    $level = max($level, min(3, count($notesByDate[$key]) + 1));
                }

                $days[] = [
                    'date' => $key,
                    'day' => $day->day,
                    'month' => $day->month,
                    'label' => $day->translatedFormat('j M Y'),
                    'level' => $level,
                    'notes' => $notesByDate[$key] ?? [],
                ];
            }

            $weeks[] = [
                'id' => $weekStart->toDateString(),
                'month' => $weekStart->month,
                'monthLabel' => $weekStart->translatedFormat('M'),
                'days' => $days,
            ];

            $cursor->addWeek();
        }

        $months = [];
        foreach ($weeks as $week) {
            $label = $week['monthLabel'];
            $last = array_key_last($months);

            if ($last !== null && $months[$last]['label'] === $label) {
                $months[$last]['span']++;
            } else {
                $months[] = ['label' => $label, 'span' => 1];
            }
        }

        return [
            'weeks' => $weeks,
            'months' => $months,
            'days' => ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
        ];
    }
}
