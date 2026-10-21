<?php

namespace Tests;

use App\Support\SiteNavAvailability;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Laravel\Fortify\Features;

abstract class TestCase extends BaseTestCase
{
    /**
     * SiteNavAvailability memoriza en una propiedad estática, que sobrevive
     * entre tests: se limpia para que cada test calcule sus propios datos.
     */
    protected function setUp(): void
    {
        parent::setUp();

        SiteNavAvailability::forget();
    }

    protected function skipUnlessFortifyHas(string $feature, ?string $message = null): void
    {
        if (! Features::enabled($feature)) {
            $this->markTestSkipped($message ?? "Fortify feature [{$feature}] is not enabled.");
        }
    }
}
