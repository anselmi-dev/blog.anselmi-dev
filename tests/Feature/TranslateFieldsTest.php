<?php

use App\Actions\Translations\TranslateFields;
use App\Actions\Translations\TranslationFailed;
use App\Filament\Pages\ManageSettings;
use App\Models\Setting;
use App\Models\User;
use Filament\Actions\Testing\TestAction;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Crypt;
use Livewire\Livewire;

uses(RefreshDatabase::class);

/**
 * TranslateFields sin llamar a la API: send() devuelve el JSON que daría Claude.
 */
function translatorReturning(string $json, ?Closure $inspect = null): TranslateFields
{
    $translator = Mockery::mock(TranslateFields::class)->makePartial()->shouldAllowMockingProtectedMethods();
    $translator->shouldReceive('send')->andReturnUsing(function (string $apiKey, string $request, array $schema) use ($json, $inspect): string {
        $inspect?->__invoke($apiKey, $request, $schema);

        return $json;
    });

    return $translator;
}

test('it sends only the fields with text and returns their translations', function () {
    Setting::storeAnthropicApiKey('sk-ant-guardada');

    $translator = translatorReturning(
        json_encode(['en' => ['title' => 'Low tide', 'category' => 'Coast']]),
        function (string $apiKey, string $request, array $schema): void {
            expect($apiKey)->toBe('sk-ant-guardada')
                ->and($request)->toContain('"title": "Marea baja"')->not->toContain('location')
                ->and($schema['properties']['en']['required'])->toBe(['title', 'category'])
                ->and($schema['properties']['en']['additionalProperties'])->toBeFalse();
        },
    );

    expect($translator->handle(['title' => 'Marea baja', 'category' => 'Costa', 'location' => '  ']))
        ->toBe(['en' => ['title' => 'Low tide', 'category' => 'Coast']]);
});

test('it does not call the api when there is nothing to translate', function () {
    $translator = Mockery::mock(TranslateFields::class)->makePartial()->shouldAllowMockingProtectedMethods();
    $translator->shouldNotReceive('send');

    expect($translator->handle(['title' => null, 'category' => '']))->toBe([]);
});

test('it explains that the api key is missing', function () {
    config(['services.anthropic.key' => null]);

    app(TranslateFields::class)->handle(['title' => 'Hola']);
})->throws(TranslationFailed::class, 'Falta la API key de Anthropic');

test('it rejects a response that is not json', function () {
    Setting::storeAnthropicApiKey('sk-ant-guardada');

    translatorReturning('no es json')->handle(['title' => 'Hola']);
})->throws(TranslationFailed::class, 'no se pudo leer');

test('the api key is stored encrypted and read back', function () {
    Setting::storeAnthropicApiKey('sk-ant-secreta');

    $stored = Setting::query()->where('group', 'ai')->where('key', 'anthropic_api_key')->value('value');

    expect($stored)->not->toContain('sk-ant-secreta')
        ->and(Crypt::decryptString($stored))->toBe('sk-ant-secreta')
        ->and(Setting::anthropicApiKey())->toBe('sk-ant-secreta');
});

test('the api key falls back to the env file and survives a changed app key', function () {
    config(['services.anthropic.key' => 'sk-ant-del-env']);
    expect(Setting::anthropicApiKey())->toBe('sk-ant-del-env');

    Setting::put(Setting::ANTHROPIC_API_KEY, 'cifrada-con-otra-app-key');
    expect(Setting::anthropicApiKey())->toBeNull();
});

test('admin saves the api key from the settings without ever seeing it again', function () {
    $user = User::factory()->create();
    config(['app.admin_emails' => [strtolower($user->email)]]);
    $this->actingAs($user);
    Filament::setCurrentPanel('admin');

    Livewire::test(ManageSettings::class)
        ->fillForm(['ai.anthropic_api_key' => 'sk-ant-nueva'])
        ->call('save')
        ->assertHasNoFormErrors()
        ->assertSchemaStateSet(['ai.anthropic_api_key' => null])
        ->assertDontSeeHtml('sk-ant-nueva');

    expect(Setting::anthropicApiKey())->toBe('sk-ant-nueva');

    // Guardar otra vez con el campo vacío mantiene la clave.
    Livewire::test(ManageSettings::class)->call('save');
    expect(Setting::anthropicApiKey())->toBe('sk-ant-nueva');

    Livewire::test(ManageSettings::class)
        ->callAction(TestAction::make('forgetAnthropicApiKey')->schemaComponent())
        ->assertNotified('Clave borrada');

    expect(Setting::hasStoredAnthropicApiKey())->toBeFalse();
});
