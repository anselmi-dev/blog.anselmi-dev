<?php

namespace App\Livewire\Pages;

use App\Models\GalleryItem;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.site')]
#[Title('Galería')]
class Gallery extends Component
{
    private const int LIGHTBOX_CLOSE_MS = 400;

    public bool $lightboxShow = false;

    public bool $lightboxLeaving = false;

    public ?int $lightboxIndex = null;

    /** Solo imagen a pantalla (oculta pie con datos). */
    public bool $lightboxImageOnly = false;

    /**
     * @var list<array<string, mixed>>
     */
    public array $items = [];

    public function mount(): void
    {
        $this->items = GalleryItem::query()
            ->published()
            ->get()
            ->map(fn (GalleryItem $item): array => $item->toViewArray())
            ->all();
    }

    public function updatedLightboxShow(bool $value): void
    {
        if ($value) {
            $this->js('document.body.classList.add("overflow-hidden")');
        }
    }

    public function openLightbox(int $index): void
    {
        if (! isset($this->items[$index]) || ($this->items[$index]['kind'] ?? '') !== 'photo') {
            return;
        }

        $this->lightboxLeaving = false;
        $this->lightboxShow = true;
        $this->lightboxIndex = $index;
        $this->lightboxImageOnly = false;
    }

    public function toggleLightboxImageOnly(): void
    {
        $this->lightboxImageOnly = ! $this->lightboxImageOnly;
    }

    public function closeLightbox(): void
    {
        if (! $this->lightboxShow || $this->lightboxLeaving) {
            return;
        }

        $this->lightboxLeaving = true;
        $this->js('setTimeout(() => $wire.finishLightboxClose(), '.self::LIGHTBOX_CLOSE_MS.')');
    }

    public function finishLightboxClose(): void
    {
        if (! $this->lightboxLeaving) {
            return;
        }

        $this->lightboxLeaving = false;
        $this->lightboxShow = false;
        $this->lightboxIndex = null;
        $this->lightboxImageOnly = false;
        $this->js('document.body.classList.remove("overflow-hidden")');
    }

    /**
     * @return list<int>
     */
    public function photoOnlyIndices(): array
    {
        $out = [];
        foreach ($this->items as $i => $item) {
            if (($item['kind'] ?? '') === 'photo') {
                $out[] = $i;
            }
        }

        return $out;
    }

    public function lightboxNext(): void
    {
        if ($this->lightboxIndex === null) {
            return;
        }

        $photos = $this->photoOnlyIndices();
        $pos = array_search($this->lightboxIndex, $photos, true);
        if ($pos === false) {
            return;
        }

        $this->lightboxIndex = $photos[($pos + 1) % count($photos)];
    }

    public function lightboxPrev(): void
    {
        if ($this->lightboxIndex === null) {
            return;
        }

        $photos = $this->photoOnlyIndices();
        $pos = array_search($this->lightboxIndex, $photos, true);
        if ($pos === false) {
            return;
        }

        $n = count($photos);
        $this->lightboxIndex = $photos[($pos - 1 + $n) % $n];
    }

    /** URL de imagen más grande para el visor. */
    public function lightboxImageUrl(array $item): string
    {
        if (! empty($item['image_url'])) {
            return $item['image_url'];
        }

        $w = max(1, (int) ($item['w'] ?? 1200));
        $h = max(1, (int) ($item['h'] ?? 800));
        $max = 1920;
        if ($w >= $h) {
            $nw = $max;
            $nh = max(1, (int) round($max * $h / $w));
        } else {
            $nh = $max;
            $nw = max(1, (int) round($max * $w / $h));
        }

        return sprintf('https://picsum.photos/seed/gallery-fallback/%d/%d', $nw, $nh);
    }

    public function render()
    {
        return view('livewire.pages.gallery');
    }
}
