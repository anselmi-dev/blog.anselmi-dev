<?php

namespace App\Livewire\Pages;

use App\Models\Faq as FaqModel;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.site')]
#[Title('Preguntas frecuentes')]
class Faq extends Component
{
    /**
     * @var list<array{question: string, answer: string}>
     */
    public array $items = [];

    public function mount(): void
    {
        $this->items = FaqModel::query()
            ->published()
            ->get()
            ->map(fn (FaqModel $faq): array => [
                'question' => $faq->question,
                'answer' => $faq->answer,
            ])
            ->all();
    }

    public function render()
    {
        return view('livewire.pages.faq');
    }
}
