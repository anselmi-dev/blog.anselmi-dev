<?php

declare(strict_types=1);

namespace App\Support;

use App\Models\HomeSnapshot;
use App\Models\Post;
use App\Models\Song;

/**
 * Contenido del menú desplegable del header: grupos de enlaces y posts
 * destacados desde Filament ("Destacar en el menú").
 *
 * Para sumar un enlace, agregalo al grupo que corresponda en groups(); el
 * icono es cualquier nombre de lucide.dev.
 */
final class SiteMenu
{
    public const FEATURED_POSTS_LIMIT = 2;

    /**
     * @return list<array{title: string, links: list<array{label: string, description: string, href: string, icon: string}>}>
     */
    public static function groups(): array
    {
        $flags = SiteNavAvailability::flags();

        $groups = [
            [
                'title' => __('Servicios'),
                'links' => [
                    self::link(__('Mis fuentes'), __('Tipografías para tus proyectos'), 'services.fonts', 'type'),
                    self::link(__('Mis colores'), __('Paletas listas para Tailwind'), 'services.colors', 'palette'),
                    self::link(__('Mis herramientas'), __('El stack que uso a diario'), 'services.tools', 'wrench'),
                ],
            ],
            [
                'title' => __('Explorar'),
                'links' => array_values(array_filter([
                    self::link(__('Proyectos'), __('Trabajos y casos de estudio'), 'projects', 'briefcase'),
                    self::link(__('Código curioso'), __('Proyectos ajenos que me parecieron curiosos'), 'curious-code', 'sparkles'),
                    $flags['hasPublishedPosts'] ? self::link(__('Blog'), __('Notas sobre cómo trabajo'), 'blog', 'notebook-pen') : null,
                    $flags['hasPublishedGalleryPhotos'] ? self::link(__('Galería'), __('Lo que veo cuando no estoy codando'), 'gallery', 'camera') : null,
                    self::hasPlaylist() ? self::link(__('Mi playlist'), __('Lo que suena mientras trabajo'), 'playlist', 'music') : null,
                ])),
            ],
            [
                'title' => __('Sobre mí'),
                'links' => array_values(array_filter([
                    self::link(__('Sobre mí'), __('Quién soy y cómo trabajo'), 'about', 'user-round'),
                    $flags['hasPublishedFaqs'] ? self::link(__('FAQ'), __('Preguntas frecuentes'), 'faq', 'message-circle-question') : null,
                ])),
            ],
        ];

        return array_values(array_filter($groups, fn (array $group): bool => $group['links'] !== []));
    }

    /**
     * @return list<array{kind: string, title: string, excerpt: string|null, image_url: string|null, alt: string, date: string|null, date_iso: string|null, href: string}>
     */
    public static function featuredPosts(): array
    {
        return Post::query()
            ->published()
            ->featuredInMenu()
            ->limit(self::FEATURED_POSTS_LIMIT)
            ->get()
            ->map(function (Post $post): array {
                $entry = $post->toEntryArray();

                return [
                    'kind' => $post->kind === 'image' ? 'image' : 'note',
                    'title' => (string) $entry['title'],
                    'excerpt' => $entry['excerpt'],
                    'image_url' => $post->kind === 'image' ? $entry['image_url'] : null,
                    'alt' => (string) ($entry['alt'] ?: $entry['title']),
                    'date' => $post->published_at?->translatedFormat('j M Y'),
                    'date_iso' => $post->published_at?->toDateString(),
                    'href' => localized_route('blog.show', $post->slug),
                ];
            })
            ->all();
    }

    private static function hasPlaylist(): bool
    {
        return filled(HomeSnapshot::current()->playlist_url) || Song::query()->published()->exists();
    }

    /**
     * @return array{label: string, description: string, href: string, icon: string}
     */
    private static function link(string $label, string $description, string $routeName, string $icon): array
    {
        return [
            'label' => $label,
            'description' => $description,
            'href' => localized_route($routeName),
            'icon' => $icon,
        ];
    }
}
