<?php

use App\Actions\Translations\TranslateFields;
use App\Filament\Pages\ManageHomeSnapshot;
use App\Filament\Pages\ManageSettings;
use App\Filament\Resources\Collections\Pages\CreateCollection;
use App\Filament\Resources\Faqs\Pages\CreateFaq;
use App\Filament\Resources\Fonts\Pages\CreateFont;
use App\Filament\Resources\Media\Pages\CreateMedia;
use App\Filament\Resources\Posts\Pages\CreatePost;
use App\Filament\Resources\Posts\Pages\EditPost;
use App\Filament\Resources\Quotes\Pages\CreateQuote;
use App\Filament\Resources\ReadingBooks\Pages\CreateReadingBook;
use App\Filament\Resources\SiteColors\Pages\CreateSiteColor;
use App\Filament\Resources\Songs\Pages\CreateSong;
use App\Filament\Resources\Tags\Pages\CreateTag;
use App\Filament\Resources\Tools\Pages\CreateTool;
use App\Filament\Resources\Videos\Pages\CreateVideo;
use App\Models\Post;
use App\Models\User;
use Filament\Actions\Testing\TestAction;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    $user = User::factory()->create();
    config(['app.admin_emails' => [strtolower($user->email)]]);
    $this->actingAs($user);
    Filament::setCurrentPanel('admin');
});

/**
 * Reemplaza la traducción con IA por una que agrega " (EN)" a cada texto.
 */
function fakeTranslator(?Closure $inspect = null): void
{
    $translator = Mockery::mock(TranslateFields::class);
    $translator->shouldReceive('handle')->andReturnUsing(function (array $fields, array $locales) use ($inspect): array {
        $inspect?->__invoke($fields);

        return ['en' => collect($fields)->filter()->map(fn (string $text): string => "{$text} (EN)")->all()];
    });
    app()->instance(TranslateFields::class, $translator);
}

test('every translatable form has language tabs with the translate button', function (string $page) {
    Livewire::test($page)
        ->assertSee('English')
        ->assertSee('Traducir desde español')
        ->assertDontSee('Textos para la versión en inglés del sitio');
})->with([
    'posts' => CreatePost::class,
    'colecciones' => CreateCollection::class,
    'playlist' => CreateSong::class,
    'libros' => CreateReadingBook::class,
    'colores' => CreateSiteColor::class,
    'fuentes' => CreateFont::class,
    'herramientas' => CreateTool::class,
    'faqs' => CreateFaq::class,
    'citas' => CreateQuote::class,
    'videos' => CreateVideo::class,
    'media' => CreateMedia::class,
    'etiquetas' => CreateTag::class,
    'ajustes' => ManageSettings::class,
    'vitrina' => ManageHomeSnapshot::class,
]);

test('a post is translated with its paragraphs, one by one', function () {
    $post = Post::create([
        'kind' => 'note',
        'title' => 'Notas de proceso',
        'slug' => 'notas-de-proceso',
        'excerpt' => 'Un resumen.',
        'body' => ['Primer párrafo.', 'Segundo párrafo.'],
        'is_published' => true,
    ]);

    fakeTranslator(function (array $fields): void {
        expect($fields)->toMatchArray([
            'title' => 'Notas de proceso',
            'body.0' => 'Primer párrafo.',
            'body.1' => 'Segundo párrafo.',
        ]);
    });

    $component = Livewire::test(EditPost::class, ['record' => $post->getRouteKey()])
        ->callAction(TestAction::make('translateFromSpanish')->schemaComponent())
        ->assertSchemaStateSet([
            'title_en' => 'Notas de proceso (EN)',
            'excerpt_en' => 'Un resumen. (EN)',
        ]);

    $component->call('save')->assertHasNoFormErrors();

    expect($post->refresh())
        ->title_en->toBe('Notas de proceso (EN)')
        ->body_en->toBe(['Primer párrafo. (EN)', 'Segundo párrafo. (EN)']);
});

test('each group of language tabs in the settings translates only its own fields', function () {
    $seen = [];
    fakeTranslator(function (array $fields) use (&$seen): void {
        $seen[] = array_keys($fields);
    });

    Livewire::test(ManageSettings::class)
        ->fillForm([
            'site.meta_description' => 'Desarrollador web en Montevideo.',
            'contact.form_title' => 'Escribime',
        ])
        ->callAction(TestAction::make('translateFromSpanish')->schemaComponent('seo-idiomas.en-traducir'))
        ->assertSchemaStateSet(['site.meta_description_en' => 'Desarrollador web en Montevideo. (EN)'])
        ->callAction(TestAction::make('translateFromSpanish')->schemaComponent('contacto-idiomas.en-traducir'))
        ->assertSchemaStateSet(['contact.form_title_en' => 'Escribime (EN)']);

    expect($seen[0])->toBe(['site.meta_description'])
        ->and($seen[1])->toContain('contact.form_title', 'contact.email_label')->not->toContain('site.meta_description');
});
