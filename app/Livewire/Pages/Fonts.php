<?php

namespace App\Livewire\Pages;

use App\Models\Font;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.site')]
#[Title('Mis fuentes')]
class Fonts extends Component
{
    public function render()
    {
        $fonts = Font::query()
            ->published()
            ->get()
            ->mapWithKeys(fn (Font $font): array => [
                $font->slug => $font->toViewArray(),
            ])
            ->all();

        return view('livewire.pages.fonts', [
            'fonts' => $fonts,
        ]);
    }
}
