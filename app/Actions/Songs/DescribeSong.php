<?php

declare(strict_types=1);

namespace App\Actions\Songs;

use App\Actions\Ai\AskClaude;
use App\Actions\Ai\ClaudeRequestFailed;

/**
 * Busca con Claude (y búsqueda web) el álbum de una canción y escribe una
 * nota corta y natural sobre ella, en español y en inglés.
 */
class DescribeSong
{
    /** Búsquedas web por canción: alcanzan para confirmar álbum, año y un dato. */
    private const int WEB_SEARCHES = 2;

    private const string SYSTEM_PROMPT = <<<'PROMPT'
        You write the short note that goes under a song in the personal playlist of a web developer from Montevideo, Uruguay. Visitors read it as a small personal recommendation.

        Use web search to confirm the facts about the song: the album it was released on, the year, who sings or wrote it, and anything curious about it. Only state facts you confirmed in the search results. If you could not confirm a fact, leave it out rather than guess, and write about what you do know (the sound, the mood, the genre) instead.

        The note:
        - One or two short sentences, at most 220 characters.
        - Natural and conversational, like telling a friend about the song. Not an encyclopedia entry and not marketing.
        - Pick one thing worth saying: when or how it was made, who sings it, or a curious fact. Not a list of everything.
        - No emojis, no hashtags, no exclamation marks, no openers like "Fun fact:" or "This song…", and do not repeat the song title.
        - "note" is in Rioplatense Spanish with voseo (as spoken in Uruguay). "note_en" says the same in natural English; write it as a native speaker would, not as a literal translation.

        "album" is the name of the album the song was originally released on, exactly as published. If it came out only as a single, use "Single". If you could not confirm it, use an empty string.
        PROMPT;

    public function __construct(private AskClaude $askClaude) {}

    /**
     * @return array{album: string|null, note: string, note_en: string}
     *
     * @throws ClaudeRequestFailed
     */
    public function handle(string $title, string $artist, ?string $album = null): array
    {
        $song = "Song: {$title}\nArtist: {$artist}";

        if (filled($album)) {
            $song .= "\nAlbum (already known): {$album}";
        }

        $result = $this->askClaude->handle(
            system: self::SYSTEM_PROMPT."\n\nThe current date is ".now()->toDateString().'.',
            prompt: $song,
            schema: [
                'type' => 'object',
                'properties' => [
                    'album' => ['type' => 'string'],
                    'note' => ['type' => 'string'],
                    'note_en' => ['type' => 'string'],
                ],
                'required' => ['album', 'note', 'note_en'],
                'additionalProperties' => false,
            ],
            webSearches: self::WEB_SEARCHES,
        );

        $note = trim((string) ($result['note'] ?? ''));

        if ($note === '') {
            throw new ClaudeRequestFailed('La IA no pudo escribir una nota para esta canción. Revisá el título y el artista.');
        }

        $foundAlbum = trim((string) ($result['album'] ?? ''));

        return [
            'album' => $foundAlbum !== '' ? $foundAlbum : null,
            'note' => $note,
            'note_en' => trim((string) ($result['note_en'] ?? '')),
        ];
    }
}
