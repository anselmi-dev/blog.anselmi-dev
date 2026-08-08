<?php

declare(strict_types=1);

namespace Domains\Test;

use App\Support\Domain\DomainServiceProvider;

class TestServiceProvider extends DomainServiceProvider
{
    public static function key(): string
    {
        return 'test';
    }

    public static function name(): string
    {
        return 'Test';
    }
}
