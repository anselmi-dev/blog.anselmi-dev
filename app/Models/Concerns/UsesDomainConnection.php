<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use App\Support\Domain\DomainManager;

trait UsesDomainConnection
{
    public function getConnectionName(): ?string
    {
        $domain = app(DomainManager::class)->current();

        if ($domain?->usesOwnDatabase()) {
            return $domain->connection;
        }

        return $this->connection ?? null;
    }
}
