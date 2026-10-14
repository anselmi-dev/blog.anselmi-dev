<?php

use App\Filament\Resources\Galleries\GalleryResource;
use App\Filament\Resources\Galleries\Pages\CreateGallery;
use App\Filament\Resources\Galleries\Pages\EditGallery;
use App\Filament\Resources\Galleries\Pages\ListGalleries;
use App\Filament\Resources\Galleries\Pickers\MediaPicker;
use App\Filament\Resources\Galleries\Pickers\QuotePicker;
use App\Filament\Resources\Galleries\Pickers\VideoPicker;
use App\Models\Collection;
use App\Models\Gallery;
use App\Models\Media;
use App\Models\Quote;
use App\Models\User;
use App\Models\Video;
use Filament\Facades\Filament;
use Filament\Forms\Components\TableSelect\Livewire\TableSelectLivewireComponent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    $user = User::factory()->create();
    config(['app.admin_emails' => [strtolower($user->email)]]);
    $this->actingAs($user);
    Filament::setCurrentPanel('admin');

    $this->collection = Collection::create(['name' => 'Principal', 'slug' => 'principal']);
});

function galleryEntry(Collection $collection, mixed $galleryable): Gallery
{
    return Gallery::create([
        'collection_id' => $collection->id,
        'galleryable_type' => $galleryable::class,
        'galleryable_id' => $galleryable->id,
        'is_published' => true,
    ]);
}

test('the table shows a square preview: the photo, the video poster or an icon of the type', function () {
    Storage::fake('public');
    $photo = Media::create(['title' => 'Muelle']);
    $photo->forceFill(['image_path' => 'media/muelle.jpg', 'thumb_path' => 'media/muelle-thumb.jpg'])->saveQuietly();
    $video = Video::factory()->withPoster()->create(['title' => 'Olas']);
    $quote = Quote::create(['quote' => 'La luz correcta llega cuando dejás de perseguirla.']);

    $entries = collect([$photo, $video, $quote])->map(fn ($item) => galleryEntry($this->collection, $item));

    Livewire::test(ListGalleries::class)
        ->assertCanSeeTableRecords($entries)
        ->assertSeeHtml('src="'.Storage::disk('public')->url('media/muelle-thumb.jpg').'"')
        ->assertSeeHtml('src="'.$video->posterUrl().'"')
        ->assertSee('La luz correcta llega cuando dejás de perseguirla.')
        ->assertSeeHtml('--warning-600');
});

test('an entry whose content was deleted says so in the table', function () {
    $quote = Quote::create(['quote' => 'Se va a borrar.']);
    $entry = galleryEntry($this->collection, $quote);
    Quote::query()->whereKey($quote->id)->delete();

    expect($entry->refresh()->adminPreview())->toBe(['kind' => 'missing', 'image' => null, 'label' => 'Contenido borrado']);
});

test('admin adds a video to a collection picking it from the grid', function () {
    $video = Video::factory()->create(['title' => 'Olas en la rambla']);

    Livewire::test(CreateGallery::class)
        ->fillForm([
            'collection_id' => $this->collection->id,
            'galleryable_type' => Video::class,
        ])
        ->fillForm(['galleryable_id' => $video->id])
        ->assertSee('Olas en la rambla')
        ->call('create')
        ->assertHasNoFormErrors();

    expect(Gallery::query()->sole())
        ->galleryable_type->toBe(Video::class)
        ->galleryable_id->toBe($video->id);
});

test('changing the type clears the chosen content', function () {
    $media = Media::create(['title' => 'Una foto']);
    $entry = galleryEntry($this->collection, $media);

    Livewire::test(EditGallery::class, ['record' => $entry->getRouteKey()])
        ->assertSchemaStateSet(['galleryable_type' => Media::class, 'galleryable_id' => $media->id])
        ->fillForm(['galleryable_type' => Quote::class])
        ->assertSchemaStateSet(['galleryable_id' => null]);
});

test('the content has to exist for the chosen type', function () {
    $quote = Quote::create(['quote' => 'Una cita.']);

    Livewire::test(CreateGallery::class)
        ->fillForm(['collection_id' => $this->collection->id, 'galleryable_type' => Video::class])
        ->fillForm(['galleryable_id' => $quote->id + 100])
        ->call('create')
        ->assertHasFormErrors(['galleryable_id']);
});

test('the picker grids list the content with search and pages of twelve', function (string $picker, Closure $make, string $text) {
    $make();

    Livewire::test(TableSelectLivewireComponent::class, ['tableConfiguration' => base64_encode($picker)])
        ->assertSee($text)
        ->searchTable('no-existe-nada')
        ->assertDontSee($text);

    expect(collect(GalleryResource::TYPES)->keys())->toContain(Media::class, Video::class, Quote::class);
})->with([
    'fotos' => [MediaPicker::class, fn () => Media::create(['title' => 'Muelle al atardecer']), 'Muelle al atardecer'],
    'videos' => [VideoPicker::class, fn () => Video::factory()->create(['title' => 'Olas en la rambla']), 'Olas en la rambla'],
    'citas' => [QuotePicker::class, fn () => Quote::create(['quote' => 'Volver con ojos distintos.']), 'Volver con ojos distintos.'],
]);

test('the photo grid pages twelve photos at a time', function () {
    $photos = collect(range(1, 13))->map(fn (int $number): Media => Media::create(['title' => "Foto {$number}"]));

    Livewire::test(TableSelectLivewireComponent::class, ['tableConfiguration' => base64_encode(MediaPicker::class)])
        ->assertCountTableRecords(13)
        ->assertCanSeeTableRecords($photos->slice(1))
        ->assertCanNotSeeTableRecords($photos->take(1));
});
