<?php

use Anthropic\Beta\Messages\BetaMessage;
use App\Actions\Ai\AskClaude;
use App\Actions\Ai\ClaudeRequestFailed;
use App\Actions\Songs\DescribeSong;
use App\Filament\Resources\Songs\Pages\CreateSong;
use App\Filament\Resources\Songs\Pages\EditSong;
use App\Models\Setting;
use App\Models\Song;
use App\Models\User;
use Filament\Actions\Testing\TestAction;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

function actingAsSongAdmin(): void
{
    $user = User::factory()->create();
    config(['app.admin_emails' => [strtolower($user->email)]]);
    test()->actingAs($user);
    Filament::setCurrentPanel('admin');
}

/**
 * Respuesta de la API con búsqueda web y el JSON partido en varios bloques de texto.
 *
 * @param  list<string>  $jsonParts
 */
function messageWithSearch(array $jsonParts, string $stopReason = 'end_turn'): BetaMessage
{
    return BetaMessage::fromArray([
        'id' => 'msg_test', 'type' => 'message', 'role' => 'assistant', 'model' => 'claude-haiku-5-5',
        'content' => [
            ['type' => 'text', 'text' => 'Busco la canción.', 'citations' => null],
            ['type' => 'server_tool_use', 'id' => 'srvtoolu_1', 'name' => 'web_search', 'input' => ['query' => 'canción']],
            ['type' => 'web_search_tool_result', 'tool_use_id' => 'srvtoolu_1', 'content' => [
                ['type' => 'web_search_result', 'url' => 'https://example.org', 'title' => 'Resultado', 'encrypted_content' => 'x', 'page_age' => null],
            ]],
            ...array_map(fn (string $text): array => ['type' => 'text', 'text' => $text, 'citations' => null], $jsonParts),
        ],
        'stop_reason' => $stopReason, 'stop_sequence' => null, 'stop_details' => null, 'container' => null, 'context_management' => null,
        'usage' => ['input_tokens' => 1, 'output_tokens' => 1, 'cache_creation_input_tokens' => 0, 'cache_read_input_tokens' => 0, 'cache_creation' => null, 'server_tool_use' => null, 'service_tier' => 'standard', 'inference_geo' => null, 'iterations' => null, 'speed' => null],
    ]);
}

test('the button fills the album and the note in both languages', function () {
    actingAsSongAdmin();

    $describer = Mockery::mock(DescribeSong::class);
    $describer->shouldReceive('handle')->once()->with('Todo se transforma', 'Jorge Drexler', null)->andReturn([
        'album' => 'Eco',
        'note' => 'Salió en 2004 en Eco.',
        'note_en' => 'Out in 2004 on Eco.',
    ]);
    app()->instance(DescribeSong::class, $describer);

    Livewire::test(CreateSong::class)
        ->fillForm(['title' => 'Todo se transforma', 'artist' => 'Jorge Drexler'])
        ->callAction(TestAction::make('describeWithAi')->schemaComponent())
        ->assertNotified('Listo')
        ->assertSchemaStateSet([
            'album' => 'Eco',
            'note' => 'Salió en 2004 en Eco.',
            'note_en' => 'Out in 2004 on Eco.',
        ]);
});

test('the button keeps an album that is already filled', function () {
    actingAsSongAdmin();
    $song = Song::create(['title' => 'Todo se transforma', 'artist' => 'Jorge Drexler', 'album' => 'Mi álbum']);

    $describer = Mockery::mock(DescribeSong::class);
    $describer->shouldReceive('handle')->once()->with('Todo se transforma', 'Jorge Drexler', 'Mi álbum')
        ->andReturn(['album' => 'Eco', 'note' => 'Nota.', 'note_en' => 'Note.']);
    app()->instance(DescribeSong::class, $describer);

    Livewire::test(EditSong::class, ['record' => $song->getRouteKey()])
        ->callAction(TestAction::make('describeWithAi')->schemaComponent())
        ->assertSchemaStateSet(['album' => 'Mi álbum', 'note' => 'Nota.']);
});

test('the button asks for the title and the artist first', function () {
    actingAsSongAdmin();
    app()->instance(DescribeSong::class, Mockery::mock(DescribeSong::class)->shouldNotReceive('handle')->getMock());

    Livewire::test(CreateSong::class)
        ->fillForm(['title' => 'Solo el título'])
        ->callAction(TestAction::make('describeWithAi')->schemaComponent())
        ->assertNotified('Completá el título y el artista primero.');
});

test('the button shows why the request failed', function () {
    actingAsSongAdmin();

    $describer = Mockery::mock(DescribeSong::class);
    $describer->shouldReceive('handle')->andThrow(new ClaudeRequestFailed('Falta la API key de Anthropic: cargala en Ajustes → IA.'));
    app()->instance(DescribeSong::class, $describer);

    Livewire::test(CreateSong::class)
        ->fillForm(['title' => 'Una', 'artist' => 'Alguien'])
        ->callAction(TestAction::make('describeWithAi')->schemaComponent())
        ->assertNotified('No se pudo completar')
        ->assertSchemaStateSet(['note' => null]);
});

test('describing a song searches the web and cleans the answer', function () {
    $askClaude = Mockery::mock(AskClaude::class);
    $askClaude->shouldReceive('handle')
        ->once()
        ->withArgs(function (string $system, string $prompt, array $schema, int $webSearches): bool {
            return $webSearches === 2
                && str_contains($system, 'The current date is '.now()->toDateString())
                && $prompt === "Song: Todo se transforma\nArtist: Jorge Drexler"
                && $schema['required'] === ['album', 'note', 'note_en'];
        })
        ->andReturn(['album' => '', 'note' => '  Una nota.  ', 'note_en' => 'A note.']);
    app()->instance(AskClaude::class, $askClaude);

    expect(app(DescribeSong::class)->handle('Todo se transforma', 'Jorge Drexler'))
        ->toBe(['album' => null, 'note' => 'Una nota.', 'note_en' => 'A note.']);
});

test('describing a song fails clearly when no note comes back', function () {
    $askClaude = Mockery::mock(AskClaude::class);
    $askClaude->shouldReceive('handle')->andReturn(['album' => 'Eco', 'note' => '', 'note_en' => '']);
    app()->instance(AskClaude::class, $askClaude);

    app(DescribeSong::class)->handle('Una', 'Alguien');
})->throws(ClaudeRequestFailed::class, 'no pudo escribir una nota');

test('a search paused by the api is resumed and the json split in several blocks is joined', function () {
    Setting::storeAnthropicApiKey('sk-ant-guardada');

    $askClaude = Mockery::mock(AskClaude::class)->makePartial()->shouldAllowMockingProtectedMethods();
    $askClaude->shouldReceive('request')
        ->once()
        ->withArgs(fn (string $key, string $system, array $messages, array $schema, int $webSearches): bool => count($messages) === 1 && $webSearches === 2)
        ->andReturn(messageWithSearch([], 'pause_turn'));
    $askClaude->shouldReceive('request')
        ->once()
        ->withArgs(fn (string $key, string $system, array $messages): bool => count($messages) === 2
            && $messages[1]['role'] === 'assistant'
            && $messages[1]['content'][1]['type'] === 'server_tool_use')
        ->andReturn(messageWithSearch(['{"album": "Eco", ', '"note": "Una nota."}']));

    expect($askClaude->handle('Sistema', 'Pedido', ['type' => 'object'], webSearches: 2))
        ->toBe(['album' => 'Eco', 'note' => 'Una nota.']);
});
