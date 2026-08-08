<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Support\Domain\DomainManager;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

class MigrateDomainCommand extends Command
{
    protected $signature = 'domain:migrate
                            {domain : Key del dominio (ej. test)}
                            {--fresh : migrate:fresh}
                            {--seed : Ejecutar seeders}';

    protected $description = 'Migraciones de un dominio (path domains/{Name}/database/migrations)';

    public function handle(DomainManager $domains): int
    {
        $key = Str::kebab((string) $this->argument('domain'));
        $definition = $domains->get($key);

        if ($definition === null) {
            $this->error("Dominio [{$key}] no está en config/domains.php");

            return self::FAILURE;
        }

        $studly = Str::studly($key);
        $relative = "domains/{$studly}/database/migrations";

        if (! is_dir(base_path($relative))) {
            $this->error("No existe {$relative}");

            return self::FAILURE;
        }

        $params = ['--path' => $relative];

        if ($definition->usesOwnDatabase()) {
            $params['--database'] = $definition->connection;
        }

        if ($this->option('seed')) {
            $params['--seed'] = true;
        }

        $command = $this->option('fresh') ? 'migrate:fresh' : 'migrate';
        $code = Artisan::call($command, $params);
        $this->output->write(Artisan::output());

        return $code === 0 ? self::SUCCESS : self::FAILURE;
    }
}
