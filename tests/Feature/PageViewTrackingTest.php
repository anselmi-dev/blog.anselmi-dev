<?php

use App\Filament\Widgets\VisitsBreakdown;
use App\Filament\Widgets\VisitsOverview;
use App\Models\PageView;
use App\Models\User;
use App\Support\UserAgent;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

const IPHONE = 'Mozilla/5.0 (iPhone; CPU iPhone OS 17_4 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.4 Mobile/15E148 Safari/604.1';
const DESKTOP_CHROME = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/129.0.0.0 Safari/537.36';

test('a public page visit is recorded with location and device', function () {
    $this->withHeaders([
        'User-Agent' => IPHONE,
        'CF-IPCountry' => 'UY',
        'CF-Region' => 'Montevideo Department',
        'CF-IPCity' => 'Montevideo',
        'CF-Connecting-IP' => '203.0.113.7',
        'Referer' => 'https://www.google.com/search?q=anselmi',
    ])->get('/?utm_source=newsletter')->assertOk();

    $view = PageView::query()->sole();

    expect($view)
        ->path->toBe('/')
        ->route_name->toBe('home')
        ->locale->toBe('es')
        ->country->toBe('UY')
        ->region->toBe('Montevideo Department')
        ->city->toBe('Montevideo')
        ->device->toBe('mobile')
        ->browser->toBe('Safari')
        ->os->toBe('iOS')
        ->referrer_host->toBe('google.com')
        ->utm_source->toBe('newsletter')
        ->and($view->visitor_hash)->toHaveLength(16)
        ->and($view->visitor_hash)->not->toContain('203.0.113.7');
});

test('english pages are recorded with their locale', function () {
    $this->withHeaders(['User-Agent' => DESKTOP_CHROME])->get('/en/about')->assertOk();

    expect(PageView::query()->sole())
        ->path->toBe('/en/about')
        ->locale->toBe('en')
        ->device->toBe('desktop')
        ->browser->toBe('Chrome')
        ->os->toBe('Windows')
        ->country->toBeNull()
        ->referrer_host->toBeNull();
});

test('the same visitor gets the same hash during the day and another one the next day', function () {
    $headers = ['User-Agent' => DESKTOP_CHROME, 'CF-Connecting-IP' => '198.51.100.4'];

    $this->withHeaders($headers)->get('/');
    $this->withHeaders($headers)->get('/en/about');
    $this->travel(1)->day();
    $this->withHeaders($headers)->get('/');

    $hashes = PageView::query()->orderBy('id')->pluck('visitor_hash');

    expect($hashes[0])->toBe($hashes[1])
        ->and($hashes[2])->not->toBe($hashes[0]);
});

test('bots, admins, prefetches and internal referrers are not counted as visits', function () {
    $this->withHeaders(['User-Agent' => 'Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)'])->get('/');
    $this->withHeaders(['User-Agent' => DESKTOP_CHROME, 'Sec-Purpose' => 'prefetch'])->get('/');
    $this->withHeaders(['User-Agent' => ''])->get('/');
    $admin = User::factory()->create();
    config(['app.admin_emails' => [strtolower($admin->email)]]);
    $this->actingAs($admin)->withHeaders(['User-Agent' => DESKTOP_CHROME])->get('/')->assertOk();

    expect(PageView::query()->count())->toBe(0);
});

test('logged in users without panel access are counted as visits', function () {
    config(['app.admin_emails' => ['otra@example.com']]);

    $this->actingAs(User::factory()->create())->withHeaders(['User-Agent' => DESKTOP_CHROME])->get('/')->assertOk();

    expect(PageView::query()->count())->toBe(1);
});

test('missing pages are not recorded', function () {
    $this->withHeaders(['User-Agent' => DESKTOP_CHROME])->get('/blog/no-existe');

    expect(PageView::query()->count())->toBe(0);
});

test('a visit from inside the site keeps no referrer', function () {
    $this->withHeaders(['User-Agent' => DESKTOP_CHROME, 'Referer' => url('/sobre-mi')])->get('/')->assertOk();

    expect(PageView::query()->sole()->referrer_host)->toBeNull();
});

test('the user agent parser tells devices, browsers and systems apart', function (string $userAgent, array $expected) {
    expect(UserAgent::parse($userAgent))->toBe($expected);
})->with([
    'iphone' => [IPHONE, ['device' => 'mobile', 'browser' => 'Safari', 'os' => 'iOS']],
    'windows chrome' => [DESKTOP_CHROME, ['device' => 'desktop', 'browser' => 'Chrome', 'os' => 'Windows']],
    'android tablet' => ['Mozilla/5.0 (Linux; Android 14; SM-X710) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/129.0.0.0 Safari/537.36', ['device' => 'tablet', 'browser' => 'Chrome', 'os' => 'Android']],
    'mac firefox' => ['Mozilla/5.0 (Macintosh; Intel Mac OS X 14.6; rv:131.0) Gecko/20100101 Firefox/131.0', ['device' => 'desktop', 'browser' => 'Firefox', 'os' => 'macOS']],
    'windows edge' => ['Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/129.0.0.0 Safari/537.36 Edg/129.0.0.0', ['device' => 'desktop', 'browser' => 'Edge', 'os' => 'Windows']],
]);

test('visits older than the retention window are pruned', function () {
    PageView::factory()->create(['created_at' => now()->subMonths(PageView::KEEP_MONTHS)->subDay()]);
    $recent = PageView::factory()->create(['created_at' => now()->subMonth()]);

    $this->artisan('model:prune', ['--model' => [PageView::class]])->assertSuccessful();

    expect(PageView::query()->pluck('id')->all())->toBe([$recent->id]);
});

describe('dashboard', function () {
    beforeEach(function () {
        $user = User::factory()->create();
        config(['app.admin_emails' => [strtolower($user->email)]]);
        $this->actingAs($user);
        Filament::setCurrentPanel('admin');
    });

    test('the dashboard shows the visit widgets', function () {
        $this->get('/admin')
            ->assertOk()
            ->assertSeeLivewire(VisitsOverview::class)
            ->assertSeeLivewire(VisitsBreakdown::class);
    });

    test('the overview counts visits and distinct visitors', function () {
        PageView::factory()->count(3)->create(['created_at' => now(), 'visitor_hash' => 'aaaaaaaaaaaaaaaa']);
        PageView::factory()->create(['created_at' => now()->subDays(3), 'visitor_hash' => 'bbbbbbbbbbbbbbbb']);
        PageView::factory()->create(['created_at' => now()->subDays(20), 'visitor_hash' => 'cccccccccccccccc']);
        PageView::factory()->create(['created_at' => now()->subDays(60)]);

        expect(VisitsOverview::dailyCounts(7))->toHaveCount(7)
            ->and(array_sum(VisitsOverview::dailyCounts(7)))->toBe(4)
            ->and(VisitsOverview::dailyCounts(7)[6])->toBe(3);

        Livewire::test(VisitsOverview::class)
            ->assertSee('Hoy')
            ->assertSee('Visitantes en 30 días')
            ->assertSee('1,7 páginas por visitante', escape: false);
    });

    test('the breakdown lists countries, devices and referrers for the chosen period', function () {
        PageView::factory()->count(2)->create(['country' => 'UY', 'city' => 'Montevideo', 'region' => null, 'device' => 'mobile', 'referrer_host' => 'instagram.com', 'created_at' => now()]);
        PageView::factory()->create(['country' => 'AR', 'device' => 'desktop', 'created_at' => now()->subDays(40)]);

        Livewire::test(VisitsBreakdown::class)
            ->assertSee('Uruguay')
            ->assertSee('Montevideo')
            ->assertSee('Celular')
            ->assertSee('instagram.com')
            ->assertDontSee('Argentina')
            ->set('days', 90)
            ->assertSee('Argentina')
            ->assertSee('Escritorio');
    });
});
