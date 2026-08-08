<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeDomainCommand extends Command
{
    protected $signature = 'domain:make
                            {name : Nombre del dominio (StudlyCase o kebab)}
                            {--hosts= : Hosts separados por coma}
                            {--database= : Nombre de conexión DB propia (opcional)}
                            {--force : Sobrescribe archivos existentes}';

    protected $description = 'Scaffold un sitio en domains/{Name} y deja el registro listo en config/domains.php';

    public function handle(): int
    {
        $studly = Str::studly((string) $this->argument('name'));
        $key = Str::kebab($studly);
        $root = base_path("domains/{$studly}");

        if (File::isDirectory($root) && ! $this->option('force')) {
            $this->error("El dominio {$studly} ya existe. Usá --force para sobrescribir.");

            return self::FAILURE;
        }

        $hosts = (string) ($this->option('hosts') ?: "{$key}.anselmidev.test");
        $connection = $this->option('database') ?: null;
        $hostsEnv = 'DOMAIN_'.Str::upper(Str::snake($studly)).'_HOSTS';
        $connectionEnv = 'DOMAIN_'.Str::upper(Str::snake($studly)).'_DB_CONNECTION';

        $replacements = [
            '{{Studly}}' => $studly,
            '{{key}}' => $key,
            '{{name}}' => Str::headline($studly),
            '{{hosts}}' => $hosts,
            '{{hostsEnv}}' => $hostsEnv,
            '{{connectionEnv}}' => $connectionEnv,
            '{{connection}}' => $connection ? "'{$connection}'" : 'null',
            '{{viteCss}}' => "domains/{$studly}/resources/css/app.css",
            '{{viteJs}}' => "domains/{$studly}/resources/js/app.js",
        ];

        $stubs = [
            'ServiceProvider.php.stub' => "{$studly}ServiceProvider.php",
            'config/domain.php.stub' => 'config/domain.php',
            'routes/web.php.stub' => 'routes/web.php',
            'Livewire/Home.php.stub' => 'Livewire/Home.php',
            'resources/views/layouts/domain.blade.php.stub' => 'resources/views/layouts/domain.blade.php',
            'resources/views/livewire/home.blade.php.stub' => 'resources/views/livewire/home.blade.php',
            'resources/css/app.css.stub' => 'resources/css/app.css',
            'resources/js/app.js.stub' => 'resources/js/app.js',
            'database/migrations/.gitkeep.stub' => 'database/migrations/.gitkeep',
            'Http/Controllers/.gitkeep.stub' => 'Http/Controllers/.gitkeep',
            'Models/.gitkeep.stub' => 'Models/.gitkeep',
        ];

        foreach ($stubs as $stub => $target) {
            $this->publishStub($stub, "{$root}/{$target}", $replacements);
        }

        $this->registerInDomainsConfig($studly, $key, $hostsEnv, $connectionEnv, $hosts, $connection);
        $this->appendEnvExample($hostsEnv, $connectionEnv, $hosts, $connection);

        $this->info("Dominio creado: domains/{$studly}");
        $this->line('1. composer dump-autoload');
        $this->line("2. Setear {$hostsEnv} en .env");
        $this->line('3. npm run build (o npm run dev)');
        $this->line("4. Apuntar el vhost a: {$hosts}");

        return self::SUCCESS;
    }

    /**
     * @param  array<string, string>  $replacements
     */
    private function publishStub(string $stub, string $target, array $replacements): void
    {
        $source = base_path("stubs/domain/{$stub}");

        if (! File::exists($source)) {
            $this->warn("Stub faltante: {$stub}");

            return;
        }

        File::ensureDirectoryExists(dirname($target));
        File::put($target, str_replace(array_keys($replacements), array_values($replacements), File::get($source)));
        $this->line("  + {$target}");
    }

    private function registerInDomainsConfig(
        string $studly,
        string $key,
        string $hostsEnv,
        string $connectionEnv,
        string $hosts,
        ?string $connection,
    ): void {
        $path = config_path('domains.php');
        $contents = File::get($path);

        if (str_contains($contents, "Domains\\{$studly}\\{$studly}ServiceProvider")) {
            $this->line('  · ya estaba en config/domains.php');

            return;
        }

        if (! str_contains($contents, "use Domains\\{$studly}\\{$studly}ServiceProvider;")) {
            $contents = preg_replace(
                '/^(use Domains\\\\.+;)$/m',
                "$1\nuse Domains\\{$studly}\\{$studly}ServiceProvider;",
                $contents,
                1,
            ) ?? $contents;

            if (! str_contains($contents, "use Domains\\{$studly}\\{$studly}ServiceProvider;")) {
                $contents = preg_replace(
                    '/^(<\?php\n\ndeclare\(strict_types=1\);\n)/',
                    "$1\nuse Domains\\{$studly}\\{$studly}ServiceProvider;\n",
                    $contents,
                    1,
                ) ?? $contents;
            }
        }

        $connectionLine = $connection
            ? "env('{$connectionEnv}', '{$connection}')"
            : "env('{$connectionEnv}')";

        $entry = <<<PHP

        '{$key}' => [
            'provider' => {$studly}ServiceProvider::class,
            'hosts' => array_values(array_filter(array_map(
                static fn (string \$h): string => strtolower(trim(\$h)),
                explode(',', (string) env('{$hostsEnv}', '{$hosts}')),
            ))),
            'connection' => {$connectionLine},
        ],

PHP;

        $contents = preg_replace(
            '/(\'sites\'\s*=>\s*\[\n)/',
            "$1{$entry}",
            $contents,
            1,
        ) ?? $contents;

        File::put($path, $contents);
        $this->line('  + config/domains.php');
    }

    private function appendEnvExample(string $hostsEnv, string $connectionEnv, string $hosts, ?string $connection): void
    {
        $example = base_path('.env.example');

        if (! File::exists($example) || str_contains(File::get($example), $hostsEnv)) {
            return;
        }

        $block = "\n{$hostsEnv}={$hosts}\n";
        if ($connection) {
            $block .= "{$connectionEnv}={$connection}\n";
        }

        File::append($example, $block);
        $this->line('  + .env.example');
    }
}
