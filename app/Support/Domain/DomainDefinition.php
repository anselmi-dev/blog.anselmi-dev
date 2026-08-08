<?php

declare(strict_types=1);

namespace App\Support\Domain;

final class DomainDefinition
{
    /**
     * @param  list<string>  $hosts
     * @param  array{css?: string, js?: string}  $vite
     */
    public function __construct(
        public readonly string $key,
        public readonly string $name,
        public readonly array $hosts,
        public readonly ?string $connection = null,
        public readonly ?string $root = null,
        public readonly ?string $provider = null,
        public readonly array $vite = [],
    ) {}

    public function usesOwnDatabase(): bool
    {
        return filled($this->connection);
    }

    public function matchesHost(string $host): bool
    {
        $host = strtolower($host);

        foreach ($this->hosts as $allowed) {
            if ($host === strtolower($allowed)) {
                return true;
            }
        }

        return false;
    }
}
