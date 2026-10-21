<?php

namespace App\Filament\Widgets;

use App\Filament\Pages\ManageHomeSnapshot;
use App\Filament\Resources\ContactMessages\ContactMessageResource;
use App\Models\Collection;
use App\Models\ContactMessage;
use App\Models\Faq;
use App\Models\Font;
use App\Models\HomeSnapshot;
use App\Models\Media;
use App\Models\Post;
use App\Models\Quote;
use App\Models\ReadingBook;
use App\Models\SiteColor;
use App\Models\Song;
use App\Models\Tag;
use App\Models\Tool;
use App\Models\Video;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Dashboard: lo que espera algo de mí (mensajes, traducciones, la Vitrina).
 */
class PendingOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 2;

    protected ?string $heading = 'Pendientes';

    protected ?string $pollingInterval = null;

    protected int|array|null $columns = ['default' => 1, 'sm' => 3];

    /**
     * Texto principal de cada tipo: si tiene español y no inglés, falta traducirlo.
     *
     * @var array<string, array{0: class-string<Model>, 1: string}>
     */
    public const array TRANSLATABLE = [
        'Posts' => [Post::class, 'title'],
        'Fotos' => [Media::class, 'title'],
        'Videos' => [Video::class, 'title'],
        'Citas' => [Quote::class, 'quote'],
        'Colecciones' => [Collection::class, 'name'],
        'Libros' => [ReadingBook::class, 'title'],
        'Playlist' => [Song::class, 'note'],
        'Herramientas' => [Tool::class, 'tagline'],
        'Fuentes' => [Font::class, 'note'],
        'Colores' => [SiteColor::class, 'name'],
        'FAQs' => [Faq::class, 'question'],
        'Etiquetas' => [Tag::class, 'name'],
    ];

    /**
     * @return array<Stat>
     */
    protected function getStats(): array
    {
        return [
            $this->messagesStat(),
            $this->translationsStat(),
            $this->showcaseStat(),
        ];
    }

    private function messagesStat(): Stat
    {
        $unread = ContactMessage::query()->whereNull('read_at')->count();

        return Stat::make('Mensajes sin leer', $unread)
            ->description($unread > 0 ? 'Esperan respuesta' : 'Nada nuevo')
            ->descriptionIcon($unread > 0 ? Heroicon::OutlinedBellAlert : Heroicon::OutlinedCheckCircle)
            ->color($unread > 0 ? 'warning' : 'gray')
            ->icon(Heroicon::OutlinedEnvelope)
            ->url(ContactMessageResource::getUrl());
    }

    private function translationsStat(): Stat
    {
        $missing = collect(self::untranslated())->filter()->sortDesc();
        $total = $missing->sum();

        return Stat::make('Sin traducir al inglés', $total)
            ->description($total > 0
                ? $missing->take(3)->map(fn (int $count, string $label): string => "{$label} {$count}")->implode(' · ')
                : 'Todo traducido')
            ->descriptionIcon($total > 0 ? Heroicon::OutlinedExclamationCircle : Heroicon::OutlinedCheckCircle)
            ->color($total > 0 ? 'warning' : 'gray')
            ->icon(Heroicon::OutlinedGlobeAlt);
    }

    private function showcaseStat(): Stat
    {
        $missing = self::showcaseMissing();

        return Stat::make('Vitrina de la home', $missing === [] ? 'Completa' : 'Incompleta')
            ->description($missing === [] ? 'Mapa y música listos' : 'Falta: '.implode(', ', $missing))
            ->descriptionIcon($missing === [] ? Heroicon::OutlinedCheckCircle : Heroicon::OutlinedExclamationTriangle)
            ->color($missing === [] ? 'gray' : 'warning')
            ->icon(Heroicon::OutlinedSquares2x2)
            ->url(ManageHomeSnapshot::getUrl());
    }

    /**
     * Cuántos registros de cada tipo tienen el texto en español y no en inglés.
     *
     * @return array<string, int>
     */
    public static function untranslated(): array
    {
        return collect(self::TRANSLATABLE)
            ->map(fn (array $definition): int => $definition[0]::query()
                ->where(fn (Builder $query) => $query->whereNotNull($definition[1])->where($definition[1], '!=', ''))
                ->where(fn (Builder $query) => $query->whereNull("{$definition[1]}_en")->orWhere("{$definition[1]}_en", ''))
                ->count())
            ->all();
    }

    /**
     * Partes de la Vitrina que faltan cargar.
     *
     * @return list<string>
     */
    public static function showcaseMissing(): array
    {
        $snapshot = HomeSnapshot::query()->first();

        return array_keys(array_filter([
            'imagen del mapa' => blank($snapshot?->map_image_path),
            'etiqueta del mapa' => blank($snapshot?->map_label),
            'música' => blank($snapshot?->spotify_embed_url),
        ]));
    }
}
