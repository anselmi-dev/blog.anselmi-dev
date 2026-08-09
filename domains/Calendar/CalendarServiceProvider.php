<?php

declare(strict_types=1);

namespace Domains\Calendar;

use App\Support\Domain\DomainServiceProvider;

class CalendarServiceProvider extends DomainServiceProvider
{
    public static function key(): string
    {
        return 'calendar';
    }

    public static function name(): string
    {
        return 'Calendar';
    }
}
