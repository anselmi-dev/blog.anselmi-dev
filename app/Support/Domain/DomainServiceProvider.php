<?php

declare(strict_types=1);

namespace App\Support\Domain;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Livewire\Livewire;

/**
 * Base para providers de dominio bajo domains/{Name}/.
 * El provider se registra en config/domains.php → sites.{key}.provider
 */
abstract class DomainServiceProvider extends ServiceProvider
{
    abstract public static function key(): string;

    abstract public static function name(): string;

    public function register(): void
    {
        $configFile = $this->domainPath('config/domain.php');

        if (is_file($configFile)) {
            $this->mergeConfigFrom($configFile, 'domain.'.static::key());
        }
    }

    public function boot(): void
    {
        $this->loadViews();
        $this->loadMigrations();
        $this->loadRoutes();
        $this->registerLivewire();
    }

    public static function definition(): DomainDefinition
    {
        /** @var array{hosts?: list<string>, connection?: string|null} $site */
        $site = config('domains.sites.'.static::key(), []);

        $studly = Str::studly(static::key());

        return new DomainDefinition(
            key: static::key(),
            name: static::name(),
            hosts: array_values($site['hosts'] ?? []),
            connection: $site['connection'] ?? null,
            root: base_path("domains/{$studly}"),
            provider: static::class,
            vite: [
                'css' => "domains/{$studly}/resources/css/app.css",
                'js' => "domains/{$studly}/resources/js/app.js",
            ],
        );
    }

    protected function domainPath(string $relative = ''): string
    {
        $root = dirname((new \ReflectionClass(static::class))->getFileName() ?: '');

        return $relative === ''
            ? $root
            : $root.DIRECTORY_SEPARATOR.ltrim(str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $relative), DIRECTORY_SEPARATOR);
    }

    protected function loadViews(): void
    {
        $views = $this->domainPath('resources/views');

        if (! is_dir($views)) {
            return;
        }

        $this->loadViewsFrom($views, static::key());
        View::addLocation($views);
    }

    protected function loadMigrations(): void
    {
        $migrations = $this->domainPath('database/migrations');

        if (is_dir($migrations)) {
            $this->loadMigrationsFrom($migrations);
        }
    }

    protected function loadRoutes(): void
    {
        $web = $this->domainPath('routes/web.php');

        if (! is_file($web)) {
            return;
        }

        $hosts = static::definition()->hosts;

        if ($hosts === []) {
            return;
        }

        foreach ($hosts as $host) {
            Route::middleware('web')
                ->domain($host)
                ->group($web);
        }
    }

    protected function registerLivewire(): void
    {
        $studly = Str::studly(static::key());
        $classNamespace = "Domains\\{$studly}\\Livewire";
        $classPath = $this->domainPath('Livewire');
        $viewPath = $this->domainPath('resources/views/livewire');

        if (! is_dir($classPath)) {
            return;
        }

        Livewire::addNamespace(
            static::key(),
            is_dir($viewPath) ? $viewPath : null,
            $classNamespace,
            $classPath,
            is_dir($viewPath) ? $viewPath : null,
        );
    }
}
