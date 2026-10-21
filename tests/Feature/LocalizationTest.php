<?php

use App\Filament\Resources\Faqs\Pages\EditFaq;
use App\Http\Middleware\SetLocale;
use App\Models\Faq;
use App\Models\Setting;
use App\Models\User;
use App\Support\Localization;
use Database\Seeders\EnglishContentSeeder;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

test('spanish pages keep their urls and copy', function () {
    $this->get('/')
        ->assertOk()
        ->assertSee('<html id="top" lang="es"', false)
        ->assertSee('Diseño y desarrollo que')
        ->assertSee('href="'.url('/en').'"', false);
});

test('english pages live under /en with translated copy', function () {
    $this->get('/en')
        ->assertOk()
        ->assertSee('<html id="top" lang="en"', false)
        ->assertSee('Design and development that')
        ->assertSee('Here are some of my projects')
        ->assertDontSee('Diseño y desarrollo que');
});

test('english routes use translated slugs', function (string $name, string $path) {
    expect(localized_route($name, [], 'en'))->toBe(url($path));

    $this->get($path)->assertOk();
})->with([
    'about' => ['about', '/en/about'],
    'projects' => ['projects', '/en/projects'],
    'gallery' => ['gallery', '/en/gallery'],
    'faq' => ['faq', '/en/faq'],
    'playlist' => ['playlist', '/en/playlist'],
    'fonts' => ['services.fonts', '/en/services/fonts'],
    'colors' => ['services.colors', '/en/services/colors'],
    'tools' => ['services.tools', '/en/services/tools'],
]);

test('pages link to their other language version', function () {
    $this->get('/sobre-mi')
        ->assertOk()
        ->assertSee('hreflang="en" href="'.url('/en/about').'"', false)
        ->assertSee('hreflang="x-default" href="'.url('/sobre-mi').'"', false);

    $this->get('/en/projects/peronda')
        ->assertOk()
        ->assertSee('hreflang="es" href="'.url('/proyectos/peronda').'"', false);
});

test('internal links stay in the current language', function () {
    $this->get('/en')
        ->assertOk()
        ->assertSee('href="'.url('/en/about').'"', false)
        ->assertSee('href="'.url('/en/projects/brickstarter').'"', false)
        ->assertDontSee('href="'.url('/sobre-mi').'"', false);
});

test('project pages use the english translation', function () {
    $this->get('/en/projects/brickstarter')
        ->assertOk()
        ->assertSee('About the project')
        ->assertSee('Full-stack · migration and product')
        ->assertDontSee('Sobre el proyecto');

    $this->get('/proyectos/brickstarter')
        ->assertOk()
        ->assertSee('Sobre el proyecto');
});

test('database content uses the english fields and falls back to spanish', function () {
    Faq::create([
        'question' => '¿Pregunta traducida?',
        'question_en' => 'Translated question?',
        'answer' => 'Respuesta traducida.',
        'answer_en' => 'Translated answer.',
        'is_published' => true,
    ]);

    Faq::create([
        'question' => '¿Pregunta sin traducir?',
        'answer' => 'Respuesta sin traducir.',
        'is_published' => true,
    ]);

    $this->get('/en/faq')
        ->assertOk()
        ->assertSee('Translated question?')
        ->assertSee('Translated answer.')
        ->assertSee('¿Pregunta sin traducir?')
        ->assertDontSee('¿Pregunta traducida?');

    $this->get('/faq')
        ->assertOk()
        ->assertSee('¿Pregunta traducida?')
        ->assertDontSee('Translated question?');
});

test('contact settings use their english version on english pages', function () {
    Setting::putMany('contact', [
        'form_title' => 'Título propio en español',
        'form_title_en' => 'My own English title',
    ]);
    Setting::forgetGroupCache('contact');

    app()->setLocale('en');

    expect(Setting::localizedContact())
        ->form_title->toBe('My own English title')
        ->success_title->toBe('Got it!');

    app()->setLocale('es');

    expect(Setting::localizedContact())
        ->form_title->toBe('Título propio en español')
        ->success_title->toBe('¡Recibido!');
});

test('livewire updates keep the language of the page', function () {
    expect(Livewire::getPersistentMiddleware())->toContain(SetLocale::class);
});

test('not found pages under /en are in english', function () {
    $this->get('/en/no-existe')
        ->assertNotFound()
        ->assertSee('Page not found');

    $this->get('/no-existe')
        ->assertNotFound()
        ->assertSee('Página no encontrada');
});

test('switch url falls back to the home page of the other language', function () {
    expect(Localization::switchUrl('en'))->toBe(url('/en'));
});

test('english content seeder fills only empty translations', function () {
    Faq::create([
        'question' => '¿Con qué stack trabajás?',
        'answer' => 'Principalmente Laravel.',
        'answer_en' => 'My own answer.',
        'is_published' => true,
    ]);

    $this->seed(EnglishContentSeeder::class);

    $faq = Faq::query()->firstOrFail();

    expect($faq->question_en)->toBe('What stack do you work with?')
        ->and($faq->answer_en)->toBe('My own answer.');
});

test('admin can edit the english fields of a faq', function () {
    $user = User::factory()->create();
    config(['app.admin_emails' => [strtolower($user->email)]]);
    $this->actingAs($user);
    Filament::setCurrentPanel('admin');

    $faq = Faq::create([
        'question' => '¿Pregunta?',
        'answer' => 'Respuesta.',
        'is_published' => true,
    ]);

    Livewire::test(EditFaq::class, ['record' => $faq->getRouteKey()])
        ->fillForm([
            'question_en' => 'Question?',
            'answer_en' => 'Answer.',
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($faq->refresh())
        ->question_en->toBe('Question?')
        ->answer_en->toBe('Answer.');
});
