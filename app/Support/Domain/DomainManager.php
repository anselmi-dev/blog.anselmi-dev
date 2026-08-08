<?php

declare(strict_types=1);

namespace App\Support\Domain;

use Illuminate\Support\Str;

final class DomainManager
{
    private ?DomainDefinition $current = null;

    public function current(): ?DomainDefinition
    {
        return $this->current;
    }

    public function setCurrent(DomainDefinition $domain): void
    {
        $this->current = $domain;
    }

    public function clearCurrent(): void
    {
        $this->current = null;
    }

    public function resolveByHost(string $host): ?DomainDefinition
    {
        $host = strtolower(trim(explode(':', $host)[0]));

        foreach ($this->all() as $domain) {
            if ($domain->matchesHost($host)) {
                return $domain;
            }
        }

        return null;
    }

    public function get(string $key): ?DomainDefinition
    {
        return $this->all()[$key] ?? null;
    }

    /**
     * @return array<string, DomainDefinition>
     */
    public function all(): array
    {
        $definitions = [];

        /** @var array<string, array{provider?: class-string}> $sites */
        $sites = config('domains.sites', []);

        foreach ($sites as $key => $site) {
            $provider = $site['provider'] ?? null;

            if (! is_string($provider) || ! class_exists($provider)) {
                continue;
            }

            if (! is_subclass_of($provider, DomainServiceProvider::class)) {
                continue;
            }

            $definitions[$key] = $provider::definition();
        }

        return $definitions;
    }

    /**
     * @return list<class-string<DomainServiceProvider>>
     */
    public function providers(): array
    {
        $providers = [];

        /** @var array<string, array{provider?: class-string}> $sites */
        $sites = config('domains.sites', []);

        foreach ($sites as $site) {
            $provider = $site['provider'] ?? null;

            if (is_string($provider) && class_exists($provider) && is_subclass_of($provider, DomainServiceProvider::class)) {
                $providers[] = $provider;
            }
        }

        return $providers;
    }

    public function path(string $key, string $relative = ''): string
    {
        $base = base_path('domains/'.Str::studly($key));

        return $relative === ''
            ? $base
            : $base.DIRECTORY_SEPARATOR.ltrim(str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $relative), DIRECTORY_SEPARATOR);
    }
}
