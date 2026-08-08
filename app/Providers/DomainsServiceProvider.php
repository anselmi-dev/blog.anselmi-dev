<?php

declare(strict_types=1);

namespace App\Providers;

use App\Support\Domain\DomainManager;
use Illuminate\Support\ServiceProvider;

class DomainsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(DomainManager::class);

        foreach ($this->app->make(DomainManager::class)->providers() as $provider) {
            $this->app->register($provider);
        }
    }
}
