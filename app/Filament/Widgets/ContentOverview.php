<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Collections\CollectionResource;
use App\Filament\Resources\Faqs\FaqResource;
use App\Filament\Resources\Fonts\FontResource;
use App\Filament\Resources\Media\MediaResource;
use App\Filament\Resources\Posts\PostResource;
use App\Filament\Resources\Quotes\QuoteResource;
use App\Filament\Resources\ReadingBooks\ReadingBookResource;
use App\Filament\Resources\SiteColors\SiteColorResource;
use App\Filament\Resources\Songs\SongResource;
use App\Filament\Resources\Tags\TagResource;
use App\Filament\Resources\Tools\ToolResource;
use App\Filament\Resources\Videos\VideoResource;
use App\Models\Collection;
use App\Models\Faq;
use App\Models\Font;
use App\Models\Gallery;
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
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * Dashboard: cuánto contenido hay de cada tipo, con un enlace a su sección.
 */
class ContentOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected ?string $heading = 'Contenido';

    protected ?string $description = 'Cuánto hay de cada cosa en el sitio.';

    protected ?string $pollingInterval = null;

    protected int|array|null $columns = ['default' => 1, 'sm' => 2, 'lg' => 4];

    /**
     * @return array<Stat>
     */
    protected function getStats(): array
    {
        $notes = Post::query()->where('kind', 'note')->count();
        $images = Post::query()->where('kind', 'image')->count();
        $collections = Collection::query()->count();

        return [
            Stat::make('Posts y notas', $notes + $images)
                ->description(self::join([
                    self::plural($notes, 'nota', 'notas'),
                    self::plural($images, 'imagen', 'imágenes'),
                    self::hidden(Post::class),
                ]))
                ->icon(Heroicon::OutlinedDocumentText)
                ->url(PostResource::getUrl()),
            self::galleryStat('Fotos', Media::class, Heroicon::OutlinedPhoto, MediaResource::getUrl()),
            self::galleryStat('Videos', Video::class, Heroicon::OutlinedVideoCamera, VideoResource::getUrl()),
            self::galleryStat('Citas', Quote::class, Heroicon::OutlinedChatBubbleBottomCenterText, QuoteResource::getUrl()),
            Stat::make('Colecciones', $collections)
                ->description(self::join([
                    self::publishedSummary(Collection::class, 'publicada', 'publicadas', 'oculta', 'ocultas'),
                    self::plural(Gallery::query()->count(), 'ítem', 'ítems'),
                ]))
                ->icon(Heroicon::OutlinedRectangleStack)
                ->url(CollectionResource::getUrl()),
            self::publishableStat('Libros', ReadingBook::class, Heroicon::OutlinedBookOpen, ReadingBookResource::getUrl()),
            self::publishableStat('Playlist', Song::class, Heroicon::OutlinedMusicalNote, SongResource::getUrl()),
            self::publishableStat('Herramientas', Tool::class, Heroicon::OutlinedWrenchScrewdriver, ToolResource::getUrl()),
            self::publishableStat('Fuentes', Font::class, Heroicon::OutlinedLanguage, FontResource::getUrl()),
            self::publishableStat('Colores', SiteColor::class, Heroicon::OutlinedSwatch, SiteColorResource::getUrl()),
            self::publishableStat('FAQs', Faq::class, Heroicon::OutlinedQuestionMarkCircle, FaqResource::getUrl()),
            Stat::make('Etiquetas', Tag::query()->count())
                ->description(self::plural(DB::table('media_tag')->distinct()->count('tag_id'), 'en uso', 'en uso'))
                ->icon(Heroicon::OutlinedTag)
                ->url(TagResource::getUrl()),
        ];
    }

    /**
     * Contenido que se publica con "Publicado": total, publicados y ocultos.
     *
     * @param  class-string<Model>  $model
     */
    private static function publishableStat(string $label, string $model, Heroicon $icon, string $url): Stat
    {
        return Stat::make($label, $model::query()->count())
            ->description(self::publishedSummary($model))
            ->icon($icon)
            ->url($url);
    }

    /**
     * Fotos, videos y citas: cuántos hay y en cuántas colecciones aparecen.
     *
     * @param  class-string<Model>  $model
     */
    private static function galleryStat(string $label, string $model, Heroicon $icon, string $url): Stat
    {
        $total = $model::query()->count();
        $inCollections = Gallery::query()->where('galleryable_type', $model)->distinct()->count('galleryable_id');
        $outside = $total - $inCollections;

        return Stat::make($label, $total)
            ->description($total === 0 ? 'Todavía no hay' : self::join([
                "{$inCollections} en colecciones",
                $outside > 0 ? "{$outside} sin usar" : null,
            ]))
            ->icon($icon)
            ->url($url);
    }

    /**
     * @param  class-string<Model>  $model
     */
    private static function publishedSummary(string $model, string $published = 'publicado', string $publishedPlural = 'publicados', string $hidden = 'oculto', string $hiddenPlural = 'ocultos'): string
    {
        $total = $model::query()->count();
        $visible = $model::query()->where('is_published', true)->count();

        if ($total === 0) {
            return 'Todavía no hay';
        }

        if ($visible === $total) {
            return $total === 1 ? "1 {$published}" : "Todos {$publishedPlural}";
        }

        return self::join([self::plural($visible, $published, $publishedPlural), self::plural($total - $visible, $hidden, $hiddenPlural)]);
    }

    /**
     * @param  class-string<Model>  $model
     */
    private static function hidden(string $model): ?string
    {
        $hidden = $model::query()->where('is_published', false)->count();

        return $hidden > 0 ? self::plural($hidden, 'oculto', 'ocultos') : null;
    }

    private static function plural(int $count, string $singular, string $plural): string
    {
        return $count === 1 ? "1 {$singular}" : "{$count} {$plural}";
    }

    /**
     * @param  array<int, string|null>  $parts
     */
    private static function join(array $parts): string
    {
        return implode(' · ', array_filter($parts));
    }
}
