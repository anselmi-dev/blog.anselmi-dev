<?php

namespace Database\Seeders;

use App\Models\Faq;
use App\Models\Font;
use App\Models\GalleryItem;
use App\Models\HomeSnapshot;
use App\Models\Post;
use App\Models\ReadingBook;
use App\Models\SiteColor;
use App\Models\Tool;
use Illuminate\Database\Seeder;

class ContentSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedPosts();
        $this->seedGallery();
        $this->seedFaqs();
        $this->seedFonts();
        $this->seedColors();
        $this->seedTools();
        $this->seedSnapshot();
        $this->seedReadingBooks();
    }

    private function seedPosts(): void
    {
        if (Post::query()->exists()) {
            return;
        }

        $entries = config('blog.entries', []);
        $bento = config('blog.bento_cells', []);
        $bentoBySlug = [];
        $bentoSort = 0;

        foreach ($bento as $cell) {
            $type = $cell['type'] ?? '';
            if (! in_array($type, ['card', 'image'], true)) {
                continue;
            }
            $slug = $cell['slug'] ?? null;
            if (! $slug) {
                continue;
            }
            $bentoBySlug[$slug] = [
                'show_in_bento' => true,
                'bento_type' => $type,
                'bento_grid_class' => $cell['gridClass'] ?? null,
                'bento_sort' => $bentoSort++,
            ];
        }

        $sort = 0;
        foreach ($entries as $slug => $entry) {
            $meta = $bentoBySlug[$slug] ?? [
                'show_in_bento' => false,
                'bento_type' => null,
                'bento_grid_class' => null,
                'bento_sort' => 0,
            ];

            Post::query()->create([
                'slug' => $slug,
                'kind' => $entry['kind'] ?? 'note',
                'kicker' => $entry['kicker'] ?? null,
                'title' => $entry['title'] ?? $slug,
                'excerpt' => $entry['excerpt'] ?? null,
                'body' => $entry['body'] ?? [],
                'caption' => $entry['caption'] ?? null,
                'alt' => $entry['alt'] ?? null,
                'show_in_bento' => $meta['show_in_bento'],
                'bento_type' => $meta['bento_type'],
                'bento_grid_class' => $meta['bento_grid_class'],
                'bento_sort' => $meta['bento_sort'],
                'sort_order' => $sort++,
                'is_published' => true,
                'published_at' => now()->subMonths(max(0, 12 - $sort)),
            ]);
        }
    }

    private function seedGallery(): void
    {
        if (GalleryItem::query()->exists()) {
            return;
        }

        $items = [
            [
                'kind' => 'photo', 'span' => 'wide', 'width' => 1200, 'height' => 675,
                'category' => 'Viajes', 'title' => 'Línea del horizonte, otra vez', 'featured' => true, 'play' => false,
                'released_at' => '12 mar 2025', 'location' => 'Ruta 40, Santa Cruz',
                'description' => 'Una tarde sin viento y el lago quieto. La idea era esperar el último rayo; al final quedó la calma entera en el encuadre.',
                'iso' => '100', 'aperture' => 'f/8', 'shutter' => '1/125s', 'focal_length' => '24mm', 'camera' => 'Sony A7 IV',
                'tags' => ['paisaje', 'patagonia', 'luz natural'],
            ],
            [
                'kind' => 'photo', 'span' => 'tall', 'width' => 800, 'height' => 1200,
                'category' => 'Calles', 'title' => 'Domingo de lluvia', 'featured' => false, 'play' => true,
                'released_at' => '03 feb 2025', 'location' => 'Montevideo, UY',
                'description' => 'Reflejos en vereda y sombrillas cerradas. Audio ambiente: colectivos y charcos.',
                'iso' => '800', 'aperture' => 'f/2.8', 'shutter' => '1/160s', 'focal_length' => '35mm', 'camera' => 'Fujifilm X-T5',
                'tags' => ['urbano', 'lluvia', 'color'],
            ],
            [
                'kind' => 'photo', 'span' => 'tall', 'width' => 800, 'height' => 1200,
                'category' => 'Naturaleza', 'title' => 'Bosque y silencio', 'featured' => false, 'play' => false,
                'released_at' => '18 ene 2025', 'location' => 'Delta del Paraná, AR',
                'description' => 'Sendero angosto, musgo húmedo. Poca luz: trípode y paciencia.',
                'iso' => '400', 'aperture' => 'f/4', 'shutter' => '0,5s', 'focal_length' => '28mm', 'camera' => 'Nikon Z6 II',
                'tags' => ['bosque', 'verde', 'larga exposición'],
            ],
            [
                'kind' => 'quote', 'span' => 'tall',
                'quote' => 'La luz correcta llega cuando dejás de perseguirla.',
                'attribution' => '— nota de campo',
            ],
            [
                'kind' => 'photo', 'span' => 'tall', 'width' => 800, 'height' => 1200,
                'category' => 'Arquitectura', 'title' => 'Escaleras que suben a ningún lado', 'featured' => false, 'play' => false,
                'released_at' => '07 nov 2024', 'location' => 'Buenos Aires, AR',
                'description' => 'Edificio público, mediodía duro. Busqué sombras limpias y una geometría que no aburra.',
                'iso' => '200', 'aperture' => 'f/5.6', 'shutter' => '1/320s', 'focal_length' => '50mm', 'camera' => 'Canon R6',
                'tags' => ['arquitectura', 'sombras', 'bn propuesto'],
            ],
            [
                'kind' => 'photo', 'span' => 'tall', 'width' => 800, 'height' => 1200,
                'category' => 'Costa', 'title' => 'Marea baja', 'featured' => false, 'play' => false,
                'released_at' => '22 oct 2024', 'location' => 'Mar del Plata, AR',
                'description' => 'Algas, arena húmeda y un cielo que no prometía nada y terminó en rosa.',
                'iso' => '100', 'aperture' => 'f/11', 'shutter' => '1/60s', 'focal_length' => '18mm', 'camera' => 'Sony A7 IV',
                'tags' => ['costa', 'atardecer', 'gran angular'],
            ],
            [
                'kind' => 'photo', 'span' => 'wide', 'width' => 1200, 'height' => 675,
                'category' => 'Destinos', 'title' => 'Guía exprés de montaña', 'featured' => false, 'play' => true,
                'released_at' => '01 sep 2024', 'location' => 'Bariloche, AR',
                'description' => 'Cumbre ventosa, manos frías. La toma es del descenso, cuando el cielo abrió un poco.',
                'iso' => '320', 'aperture' => 'f/5.6', 'shutter' => '1/500s', 'focal_length' => '70mm', 'camera' => 'Nikon Z6 II',
                'tags' => ['montaña', 'viaje', 'viento'],
            ],
            [
                'kind' => 'photo', 'span' => 'tall', 'width' => 800, 'height' => 1200,
                'category' => 'Interior', 'title' => 'Ventana y taza fría', 'featured' => true, 'play' => false,
                'released_at' => '14 ago 2024', 'location' => 'Estudio, CABA',
                'description' => 'Luz de mañana lateral. Nada de flash: solo cortina y café ya frío.',
                'iso' => '400', 'aperture' => 'f/2', 'shutter' => '1/200s', 'focal_length' => '50mm', 'camera' => 'Fujifilm X-T5',
                'tags' => ['interior', 'luz natural', 'still'],
            ],
            [
                'kind' => 'photo', 'span' => 'tall', 'width' => 800, 'height' => 1200,
                'category' => 'Ciudad', 'title' => 'Neón después del cine', 'featured' => false, 'play' => false,
                'released_at' => '30 jul 2024', 'location' => 'CABA',
                'description' => 'Salida de función, mano alzada, ISO alto y aceptar el grano como parte del cuento.',
                'iso' => '3200', 'aperture' => 'f/1.8', 'shutter' => '1/80s', 'focal_length' => '35mm', 'camera' => 'Sony A7 IV',
                'tags' => ['nocturno', 'neón', 'ciudad'],
            ],
            [
                'kind' => 'photo', 'span' => 'tall', 'width' => 800, 'height' => 1200,
                'category' => 'Serie', 'title' => 'Último carrete del verano', 'featured' => false, 'play' => false,
                'released_at' => '19 jul 2024', 'location' => 'Colonia, UY',
                'description' => 'Película 400, escaneo casero. El borde del mar y gente que no mira a cámara.',
                'iso' => '400', 'aperture' => 'f/5.6', 'shutter' => '1/250s', 'focal_length' => '40mm', 'camera' => 'Leica M6 + Portra',
                'tags' => ['analógico', 'playa', 'serie'],
            ],
            [
                'kind' => 'quote', 'span' => 'wide',
                'quote' => 'No hace falta el mejor equipo: hace falta volver al mismo lugar con ojos distintos.',
                'attribution' => '— sobre fotografía',
            ],
        ];

        foreach ($items as $i => $item) {
            GalleryItem::query()->create([
                ...$item,
                'sort_order' => $i,
                'is_published' => true,
            ]);
        }
    }

    private function seedFaqs(): void
    {
        if (Faq::query()->exists()) {
            return;
        }

        $items = [
            ['question' => '¿Con qué stack trabajás?', 'answer' => 'Principalmente Laravel, Livewire y Tailwind en el front, con herramientas de build actuales (Vite). Para APIs o piezas puntuales elijo lo que mejor encaje con el proyecto.'],
            ['question' => '¿Hacés solo desarrollo o también diseño?', 'answer' => 'Puedo llevarte desde una base visual clara hasta el código listo para producción. Si ya tenés diseño en Figma u otra herramienta, encajo el maquetado y la integración sin fricción.'],
            ['question' => '¿Cómo es el proceso de un proyecto típico?', 'answer' => 'Arrancamos definiendo alcance y prioridades, luego iteramos con entregas visibles (staging o previews). Al final dejamos despliegue documentado y espacio para ajustes razonables post-lanzamiento.'],
            ['question' => '¿Trabajás por hora o por proyecto?', 'answer' => 'Depende del tipo de trabajo. Los alcances cerrados suelen ir por presupuesto fijo; el soporte continuo o la consultoría pueden ser por hora. En el primer contacto lo dejamos explícito por escrito.'],
            ['question' => '¿Podés mantener o mejorar un sitio que ya existe?', 'answer' => 'Sí: auditoría de código, actualización de dependencias, performance, accesibilidad y nuevas funcionalidades sobre bases Laravel u otras, siempre evaluando coste/beneficio.'],
            ['question' => '¿Qué necesitás para cotizar?', 'answer' => 'Una descripción breve del objetivo, plazos aproximados y, si hay, referencias visuales o técnicas. Con eso te propongo siguiente paso: llamada corta o propuesta por escrito.'],
            ['question' => '¿Ofrecés hosting o dominios?', 'answer' => 'No vendo hosting como producto; te recomiendo proveedores según el caso y puedo ayudarte a desplegar y dejar el entorno documentado para que tengas control.'],
            ['question' => '¿Cómo te contacto?', 'answer' => 'Desde cualquier página podés abrir el modal de contacto (botón “Contacto” o el atajo del pie). Ahí llega tu mensaje y respondemos en el menor tiempo posible.'],
        ];

        foreach ($items as $i => $item) {
            Faq::query()->create([
                ...$item,
                'sort_order' => $i,
                'is_published' => true,
            ]);
        }
    }

    private function seedFonts(): void
    {
        if (Font::query()->exists()) {
            return;
        }

        $sort = 0;
        foreach (config('services-kit.fonts', []) as $slug => $font) {
            Font::query()->create([
                'slug' => $slug,
                'name' => $font['name'],
                'family' => $font['family'],
                'tailwind' => $font['tailwind'] ?? null,
                'category' => $font['category'] ?? null,
                'weights' => $font['weights'] ?? null,
                'sample' => $font['sample'] ?? null,
                'google_url' => $font['google'] ?? null,
                'bunny_url' => $font['bunny'] ?? null,
                'css' => $font['css'] ?? null,
                'note' => $font['note'] ?? null,
                'sort_order' => $sort++,
                'is_published' => true,
            ]);
        }
    }

    private function seedColors(): void
    {
        if (SiteColor::query()->exists()) {
            return;
        }

        foreach (config('services-kit.color_columns', []) as $columnIndex => $column) {
            foreach ($column as $sort => $swatch) {
                SiteColor::query()->create([
                    'name' => $swatch['name'],
                    'hex' => $swatch['hex'],
                    'rgb' => $swatch['rgb'] ?? null,
                    'cmyk' => $swatch['cmyk'] ?? null,
                    'span' => $swatch['span'] ?? 'md',
                    'ink' => $swatch['ink'] ?? 'dark',
                    'column_index' => $columnIndex,
                    'sort_order' => $sort,
                    'is_published' => true,
                ]);
            }
        }
    }

    private function seedTools(): void
    {
        if (Tool::query()->exists()) {
            return;
        }

        foreach (config('services-kit.tools', []) as $i => $tool) {
            Tool::query()->create([
                'name' => $tool['name'],
                'tagline' => $tool['tagline'] ?? null,
                'url' => $tool['url'] ?? null,
                'category' => $tool['category'] ?? null,
                'icon' => $tool['icon'] ?? null,
                'sort_order' => $i,
                'is_published' => true,
            ]);
        }
    }

    private function seedSnapshot(): void
    {
        HomeSnapshot::current()->update([
            'map_image_path' => 'https://anselmidev.com/images/mapa.svg',
            'maps_url' => 'https://maps.app.goo.gl/6jtvkALmD64tEbkY9',
            'map_label' => 'MONTEVIDEO · URUGUAY',
            'spotify_embed_url' => 'https://open.spotify.com/embed/track/6MjfEIHOMW6MaDO3LpFcmW?utm_source=generator',
            'carousel_interval' => 4500,
        ]);
    }

    private function seedReadingBooks(): void
    {
        if (ReadingBook::query()->exists()) {
            return;
        }

        $cover = 'https://anselmidev.com/images/books/manual-del-estoicismo.jpg';

        $books = [
            ['title' => 'Manual de Estoicismo', 'author' => 'EPICTETO'],
            ['title' => 'Meditaciones', 'author' => 'MARCO AURELIO'],
            ['title' => 'El arte de la guerra', 'author' => 'SUN TZU'],
        ];

        foreach ($books as $i => $book) {
            ReadingBook::query()->create([
                'title' => $book['title'],
                'author' => $book['author'],
                'image_path' => $cover,
                'sort_order' => $i,
                'is_published' => true,
            ]);
        }
    }
}
