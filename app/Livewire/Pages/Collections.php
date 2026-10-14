<?php

namespace App\Livewire\Pages;

use App\Models\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

/**
 * Índice de la galería: una tarjeta por colección publicada, con sus primeros
 * ítems en abanico. Cada una abre su galería en /galeria/{slug}.
 */
#[Layout('layouts.site')]
#[Title('Galería')]
class Collections extends Component
{
    public const array SORTS = ['order', 'recent', 'name'];

    #[Url(except: 'order')]
    public string $sort = 'order';

    public function updatedSort(string $value): void
    {
        if (! in_array($value, self::SORTS, true)) {
            $this->sort = 'order';
        }
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function entries(): array
    {
        $entries = Collection::query()
            ->published()
            ->with(['publishedItems' => fn (HasMany $items) => $items->withContent()])
            ->get()
            ->map(fn (Collection $collection): array => $collection->toEntryArray());

        $sorted = match ($this->sort) {
            'recent' => $entries->sortByDesc(fn (array $entry): int => $entry['updated_at']?->getTimestamp() ?? 0),
            'name' => $entries->sortBy('name', SORT_NATURAL | SORT_FLAG_CASE),
            default => $entries,
        };

        return $sorted->values()->all();
    }

    public function render()
    {
        if (! in_array($this->sort, self::SORTS, true)) {
            $this->sort = 'order';
        }

        return view('livewire.pages.collections', [
            'entries' => $this->entries(),
        ]);
    }
}
