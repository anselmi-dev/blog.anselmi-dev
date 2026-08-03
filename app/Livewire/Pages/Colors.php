<?php

namespace App\Livewire\Pages;

use App\Models\SiteColor;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.site')]
#[Title('Mis colores')]
class Colors extends Component
{
    public function render()
    {
        $grouped = SiteColor::query()
            ->published()
            ->get()
            ->groupBy('column_index')
            ->sortKeys()
            ->map(fn ($colors) => $colors
                ->sortBy('sort_order')
                ->values()
                ->map(fn (SiteColor $color): array => $color->toViewArray())
                ->all())
            ->values()
            ->all();

        return view('livewire.pages.colors', [
            'columns' => $grouped,
        ]);
    }
}
