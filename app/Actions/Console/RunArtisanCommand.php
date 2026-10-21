<?php

declare(strict_types=1);

namespace App\Actions\Console;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Symfony\Component\Console\Output\BufferedOutput;
use Throwable;

/**
 * Ejecuta un comando de Artisan desde el panel y devuelve su salida.
 *
 * Corre dentro del request, sin interacción: los comandos que se quedan
 * esperando (tinker, serve, workers…) están bloqueados porque colgarían la
 * página.
 */
class RunArtisanCommand
{
    /**
     * Comandos que no terminan solos o que piden entrada por teclado.
     *
     * @var list<string>
     */
    public const BLOCKED_COMMANDS = [
        'tinker',
        'serve',
        'pail',
        'queue:work',
        'queue:listen',
        'schedule:work',
        'reverb:start',
        'horizon',
        'octane:start',
        'boost:mcp',
    ];

    /**
     * @return array{command: string, exit_code: int, output: string, duration_ms: int}
     */
    public function handle(string $commandLine): array
    {
        $commandLine = $this->normalize($commandLine);
        $name = Str::before($commandLine, ' ');
        $startedAt = hrtime(true);

        if ($commandLine === '') {
            return $this->result($commandLine, 1, 'Escribí un comando, por ejemplo: about', $startedAt);
        }

        if (in_array($name, self::BLOCKED_COMMANDS, true)) {
            return $this->result($commandLine, 1, "El comando \"{$name}\" no se puede ejecutar desde el panel: se queda esperando y colgaría la página.", $startedAt);
        }

        if (! str_contains(" {$commandLine} ", ' --no-interaction ') && ! str_contains(" {$commandLine} ", ' -n ')) {
            $commandLine .= ' --no-interaction';
        }

        $output = new BufferedOutput;

        try {
            $exitCode = Artisan::call($commandLine, [], $output);
            $text = $output->fetch();
        } catch (Throwable $exception) {
            $exitCode = 1;
            $text = trim($output->fetch()."\n".$exception::class.': '.$exception->getMessage());
        }

        return $this->result($commandLine, $exitCode, $text, $startedAt);
    }

    /**
     * Saca el "php artisan" del principio si se pegó el comando completo.
     */
    protected function normalize(string $commandLine): string
    {
        $commandLine = trim(preg_replace('/\s+/', ' ', $commandLine) ?? '');

        return trim((string) preg_replace('/^(php\s+)?artisan\s+/', '', $commandLine));
    }

    /**
     * @return array{command: string, exit_code: int, output: string, duration_ms: int}
     */
    protected function result(string $commandLine, int $exitCode, string $output, int $startedAt): array
    {
        return [
            'command' => $commandLine,
            'exit_code' => $exitCode,
            'output' => rtrim($output) !== '' ? rtrim($output) : '(sin salida)',
            'duration_ms' => (int) round((hrtime(true) - $startedAt) / 1_000_000),
        ];
    }
}
