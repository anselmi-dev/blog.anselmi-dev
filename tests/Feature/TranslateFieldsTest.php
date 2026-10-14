<?php

use Anthropic\Core\Exceptions\APIStatusException;
use App\Actions\Ai\AskClaude;
use App\Actions\Translations\TranslateFields;
use App\Actions\Translations\TranslationFailed;
use App\Filament\Pages\ManageSettings;
use App\Models\Setting;
use App\Models\User;
use Filament\Actions\Testing\TestAction;
use Filament\Facades\Filament;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Livewire\Livewire;
use Mockery\MockInterface;

uses(RefreshDatabase::class);

/**
 * AskClaude sin llamar a la API: send() devuelve el JSON que daría Claude.
 */
function translatorReturning(string $json, ?Closure $inspect = null): TranslateFields
{
    $askClaude = Mockery::mock(AskClaude::class)->makePartial()->shouldAllowMockingProtectedMethods();
    $askClaude->shouldReceive('send')->andReturnUsing(function (string $apiKey, string $system, string $request, array $schema, int $webSearches) use ($json, $inspect): string {
        $inspect?->__invoke($apiKey, $request, $schema, $webSearches);

        return $json;
    });
    app()->instance(AskClaude::class, $askClaude);

    return app(TranslateFields::class);
}

/**
 * AskClaude con request() simulado (el pedido HTTP a la API).
 */
function askClaudeWithRequest(): MockInterface
{
    $askClaude = Mockery::mock(AskClaude::class)->makePartial()->shouldAllowMockingProtectedMethods();
    app()->instance(AskClaude::class, $askClaude);

    return $askClaude;
}

test('it sends only the fields with text and returns their translations', function () {
    Setting::storeAnthropicApiKey('sk-ant-guardada');

    $translator = translatorReturning(
        json_encode(['en' => ['title' => 'Low tide', 'category' => 'Coast']]),
        function (string $apiKey, string $request, array $schema, int $webSearches): void {
            expect($apiKey)->toBe('sk-ant-guardada')
                ->and($webSearches)->toBe(0)
                ->and($request)->toContain('"title": "Marea baja"')->not->toContain('location')
                ->and($schema['properties']['en']['required'])->toBe(['title', 'category'])
                ->and($schema['properties']['en']['additionalProperties'])->toBeFalse();
        },
    );

    expect($translator->handle(['title' => 'Marea baja', 'category' => 'Costa', 'location' => '  ']))
        ->toBe(['en' => ['title' => 'Low tide', 'category' => 'Coast']]);
});

test('it does not call the api when there is nothing to translate', function () {
    askClaudeWithRequest()->shouldNotReceive('send');

    expect(app(TranslateFields::class)->handle(['title' => null, 'category' => '']))->toBe([]);
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

/**
 * Error 400 como lo devuelve la API de Anthropic.
 */
function badRequest(string $message): APIStatusException
{
    return APIStatusException::from(
        new Request('POST', 'https://api.anthropic.com/v1/messages'),
        new Response(400, ['Content-Type' => 'application/json', 'request-id' => 'req_test'], json_encode([
            'type' => 'error',
            'error' => ['type' => 'invalid_request_error', 'message' => $message],
        ])),
    );
}

test('a 400 error shows the reason given by the api', function () {
    Setting::storeAnthropicApiKey('sk-ant-guardada');
    Log::spy();

    askClaudeWithRequest()->shouldReceive('request')->once()->andThrow(badRequest('Your credit balance is too low to access the Anthropic API.'));

    expect(fn () => app(TranslateFields::class)->handle(['title' => 'Hola']))
        ->toThrow(TranslationFailed::class, 'La API de Anthropic devolvió un error (400): Your credit balance is too low to access the Anthropic API.');

    Log::shouldHaveReceived('warning')->once()->withArgs(fn (string $message, array $context): bool => $context['request_id'] === 'req_test');
});

test('when the account cannot use the fallback beta it asks again without it', function () {
    config(['services.anthropic.model' => 'claude-opus-5-5']);
    Setting::storeAnthropicApiKey('sk-ant-guardada');

    $askClaude = askClaudeWithRequest();
    $askClaude->shouldReceive('request')
        ->once()
        ->withArgs(fn (string $key, string $system, array $messages, array $schema, int $webSearches, bool $withFallbacks): bool => $withFallbacks)
        ->andThrow(badRequest('Unexpected value(s) `server-side-fallback-2026-07-01` for the `anthropic-beta` header.'));
    $askClaude->shouldReceive('request')
        ->once()
        ->withArgs(fn (string $key, string $system, array $messages, array $schema, int $webSearches, bool $withFallbacks): bool => ! $withFallbacks)
        ->andThrow(badRequest('Otro problema.'));

    expect(fn () => app(TranslateFields::class)->handle(['title' => 'Hola']))
        ->toThrow(TranslationFailed::class, 'Otro problema.');
});

test('admin sets the workspace for an api key that does not belong to one', function () {
    $user = User::factory()->create();
    config(['app.admin_emails' => [strtolower($user->email)]]);
    $this->actingAs($user);
    Filament::setCurrentPanel('admin');

    Livewire::test(ManageSettings::class)
        ->fillForm(['ai.anthropic_workspace_id' => 'no-es-un-workspace'])
        ->call('save')
        ->assertHasFormErrors(['ai.anthropic_workspace_id' => 'regex']);

    Livewire::test(ManageSettings::class)
        ->fillForm(['ai.anthropic_workspace_id' => 'wrkspc_011CZkZaBF1tNoB5wlCeusgy'])
        ->call('save')
        ->assertHasNoFormErrors();

    expect(Setting::anthropicWorkspaceId())->toBe('wrkspc_011CZkZaBF1tNoB5wlCeusgy');

    Livewire::test(ManageSettings::class)
        ->assertSchemaStateSet(['ai.anthropic_workspace_id' => 'wrkspc_011CZkZaBF1tNoB5wlCeusgy'])
        ->fillForm(['ai.anthropic_workspace_id' => ''])
        ->call('save');

    expect(Setting::anthropicWorkspaceId())->toBeNull();
});

test('the workspace falls back to the env file', function () {
    config(['services.anthropic.workspace_id' => 'wrkspc_DelEnv']);

    expect(Setting::anthropicWorkspaceId())->toBe('wrkspc_DelEnv');
});

test('by default it uses the cheapest model, which has no server-side fallback', function () {
    Setting::storeAnthropicApiKey('sk-ant-guardada');

    expect(config('services.anthropic.model'))->toBe('claude-haiku-5-5');

    askClaudeWithRequest()->shouldReceive('request')
        ->once()
        ->withArgs(fn (string $key, string $system, array $messages, array $schema, int $webSearches, bool $withFallbacks): bool => ! $withFallbacks)
        ->andThrow(badRequest('Unexpected value(s) for the `anthropic-beta` header.'));

    expect(fn () => app(TranslateFields::class)->handle(['title' => 'Hola']))->toThrow(TranslationFailed::class);
});
