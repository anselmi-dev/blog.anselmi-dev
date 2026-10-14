<?php

use App\Actions\Translations\TranslateFields;
use App\Actions\Translations\TranslationFailed;
use App\Filament\Resources\Media\Pages\CreateMedia;
use App\Filament\Resources\Media\Pages\EditMedia;
use App\Filament\Resources\Media\Pages\ListMedia;
use App\Livewire\Pages\Gallery as GalleryPage;
use App\Models\Collection;
use App\Models\Gallery;
use App\Models\Media;
use App\Models\Tag;
use App\Models\User;
use Filament\Actions\Testing\TestAction;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    Storage::fake('public');
});

function actingAsAdmin(): void
{
    $user = User::factory()->create();
    config(['app.admin_emails' => [strtolower($user->email)]]);
    test()->actingAs($user);
    Filament::setCurrentPanel('admin');
}

function storeImage(string $name, int $width, int $height): string
{
    return UploadedFile::fake()->image($name, $width, $height)->store('media', 'public');
}

test('saving a photo generates its thumbnail and fills its size', function () {
    $media = Media::create(['image_path' => storeImage('grande.jpg', 1600, 900)]);

    expect($media->thumb_path)->toBe('media/'.pathinfo($media->image_path, PATHINFO_FILENAME).'-thumb.jpg')
        ->and($media->width)->toBe(1600)
        ->and($media->height)->toBe(900);
    Storage::disk('public')->assertExists($media->thumb_path);

    [$thumbWidth, $thumbHeight] = getimagesize(Storage::disk('public')->path($media->thumb_path));
    expect([$thumbWidth, $thumbHeight])->toBe([480, 270]);
});

test('small photos also get a thumbnail, without being upscaled', function () {
    $media = Media::create(['image_path' => storeImage('chica.png', 300, 200)]);

    Storage::disk('public')->assertExists($media->thumb_path);
    expect(getimagesize(Storage::disk('public')->path($media->thumb_path))[0])->toBe(300);
});

test('a missing thumbnail is generated again on the next save', function () {
    $media = Media::create(['image_path' => storeImage('foto.jpg', 1200, 800)]);
    Storage::disk('public')->delete($media->thumb_path);

    $media->update(['title' => 'Otro título']);

    Storage::disk('public')->assertExists($media->thumb_path);
});

test('thumbnails can be regenerated in bulk from the table', function () {
    actingAsAdmin();
    $media = Media::create(['image_path' => storeImage('foto.jpg', 1200, 800)]);
    Storage::disk('public')->delete($media->thumb_path);

    Livewire::test(ListMedia::class)
        ->selectTableRecords([$media])
        ->callAction(TestAction::make('regenerateThumbnails')->table()->bulk())
        ->assertNotified('Miniaturas regeneradas');

    Storage::disk('public')->assertExists($media->refresh()->thumb_path);
});

test('admin creates a photo with a date, tags and its english texts', function () {
    actingAsAdmin();
    $landscape = Tag::factory()->create(['name' => 'paisaje']);
    $light = Tag::factory()->create(['name' => 'luz natural']);

    Livewire::test(CreateMedia::class)
        ->fillForm([
            'image_path' => UploadedFile::fake()->image('ruta.jpg', 1200, 675),
            'released_on' => '2025-03-12',
            'tags' => [$landscape->id, $light->id],
            'title' => 'Línea del horizonte',
            'title_en' => 'Horizon line',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $media = Media::query()->sole();

    expect($media->released_on->toDateString())->toBe('2025-03-12')
        ->and($media->title_en)->toBe('Horizon line')
        ->and($media->tags->pluck('name')->sort()->values()->all())->toBe(['luz natural', 'paisaje'])
        ->and($media->thumb_path)->not->toBeNull();
});

test('the translate button fills the english fields from the spanish ones', function () {
    actingAsAdmin();
    $media = Media::create(['title' => 'Marea baja', 'category' => 'Costa', 'description' => 'Algas y arena húmeda.']);

    $translator = Mockery::mock(TranslateFields::class);
    $translator->shouldReceive('handle')
        ->once()
        ->with(['title' => 'Marea baja', 'category' => 'Costa', 'description' => 'Algas y arena húmeda.', 'location' => null], ['en'])
        ->andReturn(['en' => ['title' => 'Low tide', 'category' => 'Coast', 'description' => 'Seaweed and wet sand.']]);
    app()->instance(TranslateFields::class, $translator);

    Livewire::test(EditMedia::class, ['record' => $media->getRouteKey()])
        ->callAction(TestAction::make('translateFromSpanish')->schemaComponent())
        ->assertNotified('Se tradujeron 3 campos')
        ->assertSchemaStateSet([
            'title_en' => 'Low tide',
            'category_en' => 'Coast',
            'description_en' => 'Seaweed and wet sand.',
        ]);
});

test('the translate button shows why a translation failed', function () {
    actingAsAdmin();
    $media = Media::create(['title' => 'Marea baja']);

    $translator = Mockery::mock(TranslateFields::class);
    $translator->shouldReceive('handle')->andThrow(new TranslationFailed('Falta la API key de Anthropic: cargala en Ajustes → IA.'));
    app()->instance(TranslateFields::class, $translator);

    Livewire::test(EditMedia::class, ['record' => $media->getRouteKey()])
        ->callAction(TestAction::make('translateFromSpanish')->schemaComponent())
        ->assertNotified('No se pudo traducir')
        ->assertSchemaStateSet(['title_en' => null]);
});

test('the gallery shows the date and the tags in the visitor language', function () {
    $collection = Collection::create(['name' => 'Principal', 'slug' => 'principal']);
    $media = Media::create(['title' => 'Marea baja', 'released_on' => '2025-03-12']);
    $media->tags()->attach(Tag::factory()->translated('coast')->create(['name' => 'costa']), ['sort_order' => 0]);
    Gallery::create([
        'collection_id' => $collection->id,
        'galleryable_type' => Media::class,
        'galleryable_id' => $media->id,
        'is_published' => true,
    ]);

    app()->setLocale('es');
    Livewire::test(GalleryPage::class, ['slug' => 'principal'])
        ->call('openLightbox', 0)
        ->assertSee('12 mar 2025')
        ->assertSee('costa');

    $this->get(localized_route('gallery.show', ['slug' => 'principal'], 'en'))->assertOk();
    app()->setLocale('en');
    expect(Gallery::query()->withContent()->sole()->toViewArray())
        ->released_at->toBe('12 Mar 2025')
        ->tags->toBe(['coast']);
});
