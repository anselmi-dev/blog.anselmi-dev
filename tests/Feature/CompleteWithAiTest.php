<?php

use App\Actions\Ai\AskClaude;
use App\Actions\Ai\ClaudeRequestFailed;
use App\Actions\Ai\CompleteFields;
use App\Filament\Pages\ManageSettings;
use App\Filament\Resources\Collections\Pages\CreateCollection;
use App\Filament\Resources\Faqs\Pages\CreateFaq;
use App\Filament\Resources\Fonts\Pages\CreateFont;
use App\Filament\Resources\Media\Pages\CreateMedia;
use App\Filament\Resources\Media\Pages\EditMedia;
use App\Filament\Resources\Posts\Pages\CreatePost;
use App\Filament\Resources\Posts\Pages\EditPost;
use App\Filament\Resources\ReadingBooks\Pages\CreateReadingBook;
use App\Filament\Resources\SiteColors\Pages\CreateSiteColor;
use App\Filament\Resources\Tools\Pages\CreateTool;
use App\Filament\Resources\Tools\Pages\EditTool;
use App\Filament\Resources\Videos\Pages\CreateVideo;
use App\Models\Media;
use App\Models\Post;
use App\Models\Tool;
use App\Models\User;
use Filament\Actions\Testing\TestAction;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    $user = User::factory()->create();
    config(['app.admin_emails' => [strtolower($user->email)]]);
    $this->actingAs($user);
    Filament::setCurrentPanel('admin');
});

/**
 * CompleteFields simulado: escribe "{campo} (IA)" y "{campo} (EN)", y guarda lo que recibió.
 */
function fakeCompleter(?array &$received = null): void
{
    $completer = Mockery::mock(CompleteFields::class);
    $completer->shouldReceive('handle')->andReturnUsing(function (...$arguments) use (&$received): array {
        $names = ['about', 'guidance', 'context', 'write', 'translate', 'webSearch', 'image'];
        $received = array_combine($names, array_pad(array_values($arguments), count($names), null));
        $write = $received['write'];
        $translate = $received['translate'] ?? [];

        return [
            'es' => collect($write)->mapWithKeys(fn (string $field): array => [$field => "{$field} (IA)"])->all(),
            'en' => collect([...$write, ...array_keys($translate)])->mapWithKeys(fn (string $field): array => [$field => "{$field} (EN)"])->all(),
        ];
    });
    app()->instance(CompleteFields::class, $completer);
}

test('every form with written content has the complete button', function (string $page) {
    Livewire::test($page)->assertSee('Completar con IA');
})->with([
    'posts' => CreatePost::class,
    'colecciones' => CreateCollection::class,
    'libros' => CreateReadingBook::class,
    'colores' => CreateSiteColor::class,
    'fuentes' => CreateFont::class,
    'herramientas' => CreateTool::class,
    'faqs' => CreateFaq::class,
    'videos' => CreateVideo::class,
    'media' => CreateMedia::class,
    'ajustes' => ManageSettings::class,
]);

test('it writes the empty spanish fields, translates the rest and uses the web for tools', function () {
    $tool = Tool::create(['name' => 'Laravel', 'url' => 'https://laravel.com', 'category' => 'Backend', 'sort_order' => 0, 'is_published' => true]);
    fakeCompleter($received);

    Livewire::test(EditTool::class, ['record' => $tool->getRouteKey()])
        ->callAction(TestAction::make('completeWithAi')->schemaComponent())
        ->assertNotified('Se escribieron 2 campos')
        ->assertSchemaStateSet([
            'tagline' => 'tagline (IA)',
            'description' => 'description (IA)',
            'category' => 'Backend',
            'tagline_en' => 'tagline (EN)',
            'category_en' => 'category (EN)',
        ]);

    expect($received['write'])->toBe(['tagline', 'description'])
        ->and($received['translate'])->toBe(['category' => 'Backend'])
        ->and($received['context'])->toMatchArray(['Nombre' => 'Laravel', 'Sitio web' => 'https://laravel.com'])
        ->and($received['webSearch'])->toBeTrue();
});

test('when every field has text it asks before rewriting all of them', function () {
    $tool = Tool::create([
        'name' => 'Laravel', 'url' => 'https://laravel.com', 'sort_order' => 0, 'is_published' => true,
        'tagline' => 'Algo', 'category' => 'Backend', 'description' => 'Una descripción.',
    ]);
    fakeCompleter($received);

    Livewire::test(EditTool::class, ['record' => $tool->getRouteKey()])
        ->mountAction(TestAction::make('completeWithAi')->schemaComponent())
        ->assertActionMounted(TestAction::make('completeWithAi')->schemaComponent())
        ->callMountedAction()
        ->assertSchemaStateSet(['tagline' => 'tagline (IA)', 'category' => 'category (IA)']);

    expect($received['write'])->toBe(['tagline', 'category', 'description']);
});

test('a note post does not get an image caption and sends its paragraphs as context', function () {
    $post = Post::create([
        'kind' => 'note', 'title' => 'Proceso', 'slug' => 'proceso',
        'body' => ['Primer párrafo.', 'Segundo párrafo.'], 'is_published' => true,
    ]);
    fakeCompleter($received);

    Livewire::test(EditPost::class, ['record' => $post->getRouteKey()])
        ->callAction(TestAction::make('completeWithAi')->schemaComponent());

    expect($received['write'])->toBe(['kicker', 'excerpt'])
        ->and($received['context']['Texto del post'])->toBe("Primer párrafo.\n\nSegundo párrafo.")
        ->and($received['image'])->toBeNull()
        ->and($received['webSearch'])->toBeFalse();
});

test('a photo is sent to the ai so it can describe it', function () {
    Storage::fake('public');
    $media = Media::create(['image_path' => UploadedFile::fake()->image('foto.jpg', 2000, 1200)->store('media', 'public')]);
    fakeCompleter($received);

    Livewire::test(EditMedia::class, ['record' => $media->getRouteKey()])
        ->callAction(TestAction::make('completeWithAi')->schemaComponent())
        ->assertSchemaStateSet(['title' => 'title (IA)', 'title_en' => 'title (EN)']);

    expect($received['image']['type'])->toBe('image')
        ->and($received['image']['source']['mediaType'])->toBe('image/jpeg')
        ->and(getimagesizefromstring(base64_decode($received['image']['source']['data']))[0])->toBe(1024);
});

test('the settings fill their own texts, with nested field names', function () {
    fakeCompleter($received);

    Livewire::test(ManageSettings::class)
        ->fillForm(['site.author_name' => 'Anselmi', 'site.meta_description' => ''])
        ->callAction(TestAction::make('completeWithAi')->schemaComponent('seo-idiomas.es-completar'))
        ->assertSchemaStateSet([
            'site.meta_description' => 'site.meta_description (IA)',
            'site.meta_description_en' => 'site.meta_description (EN)',
        ]);

    expect($received['write'])->toBe(['site.meta_description'])
        ->and($received['context'])->toMatchArray(['Nombre / autor' => 'Anselmi']);
});

test('the complete button shows why the request failed', function () {
    $completer = Mockery::mock(CompleteFields::class);
    $completer->shouldReceive('handle')->andThrow(new ClaudeRequestFailed('Falta la API key de Anthropic: cargala en Ajustes → IA.'));
    app()->instance(CompleteFields::class, $completer);

    Livewire::test(CreateSiteColor::class)
        ->fillForm(['hex' => '#c96f4a'])
        ->callAction(TestAction::make('completeWithAi')->schemaComponent())
        ->assertNotified('No se pudo completar')
        ->assertSchemaStateSet(['name' => null]);
});

test('complete fields asks for the spanish and english texts it needs and cleans them', function () {
    $askClaude = Mockery::mock(AskClaude::class);
    $askClaude->shouldReceive('handle')
        ->once()
        ->withArgs(function (string $system, string|array $prompt, array $schema, int $webSearches): bool {
            return is_array($prompt)
                && $prompt[0]['type'] === 'image'
                && str_contains($prompt[1]['text'], '- Nombre: Laravel')
                && str_contains($prompt[1]['text'], '- tagline: Qué es')
                && $schema['properties']['es']['required'] === ['tagline']
                && $schema['properties']['en']['required'] === ['tagline', 'category']
                && $webSearches === 2;
        })
        ->andReturn(['es' => ['tagline' => '  El framework  '], 'en' => ['tagline' => 'The framework', 'category' => '']]);
    app()->instance(AskClaude::class, $askClaude);

    $result = app(CompleteFields::class)->handle(
        about: 'una herramienta',
        guidance: ['tagline' => 'Qué es', 'category' => 'Rubro'],
        context: ['Nombre' => 'Laravel'],
        write: ['tagline'],
        translate: ['category' => 'Backend'],
        webSearch: true,
        image: ['type' => 'image', 'source' => ['type' => 'base64', 'mediaType' => 'image/jpeg', 'data' => 'x']],
    );

    expect($result)->toBe(['es' => ['tagline' => 'El framework'], 'en' => ['tagline' => 'The framework']]);
});
