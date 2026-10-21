<?php

use App\Actions\Translations\TranslateFields;
use App\Filament\Resources\Tags\Pages\CreateTag;
use App\Filament\Resources\Tags\Pages\ListTags;
use App\Models\Media;
use App\Models\Tag;
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

test('admin creates a tag with its english name and the slug is filled from the name', function () {
    Livewire::test(CreateTag::class)
        ->fillForm(['name' => 'Luz natural'])
        ->assertSchemaStateSet(['slug' => 'luz-natural'])
        ->fillForm(['name_en' => 'Natural light'])
        ->call('create')
        ->assertHasNoFormErrors();

    expect(Tag::query()->sole())
        ->name->toBe('Luz natural')
        ->name_en->toBe('Natural light')
        ->slug->toBe('luz-natural');
});

test('tag slugs are unique', function () {
    Tag::factory()->create(['name' => 'Costa', 'slug' => 'costa']);

    Livewire::test(CreateTag::class)
        ->fillForm(['name' => 'Costa'])
        ->call('create')
        ->assertHasFormErrors(['slug' => 'unique']);
});

test('the tag list shows how many photos use each tag', function () {
    $tag = Tag::factory()->create(['name' => 'Costa']);
    Media::create(['title' => 'Una'])->tags()->attach($tag);
    Media::create(['title' => 'Otra'])->tags()->attach($tag);

    Livewire::test(ListTags::class)
        ->assertCanSeeTableRecords([$tag])
        ->assertTableColumnStateSet('media_count', 2, $tag);
});

test('a tag name can be translated with the button', function () {
    $translator = Mockery::mock(TranslateFields::class);
    $translator->shouldReceive('handle')->once()->with(['name' => 'Atardecer'], ['en'])->andReturn(['en' => ['name' => 'Sunset']]);
    app()->instance(TranslateFields::class, $translator);

    Livewire::test(CreateTag::class)
        ->fillForm(['name' => 'Atardecer'])
        ->callAction(TestAction::make('translateFromSpanish')->schemaComponent())
        ->assertSchemaStateSet(['name_en' => 'Sunset']);
});
