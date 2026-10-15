<?php

declare(strict_types=1);

namespace App\Actions\Analytics;

use App\Models\PageView;
use App\Support\Country;
use App\Support\UserAgent;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * Guarda una visita al sitio público sin IP ni cookies.
 *
 * El visitante se cuenta con una huella (APP_KEY + día + IP + navegador) que
 * cambia cada día: alcanza para saber cuántas personas distintas vinieron,
 * pero no permite seguir a nadie de un día para otro. País, región y ciudad
 * vienen de los headers de Cloudflare.
 */
class RecordPageView
{
    public function handle(Request $request): PageView
    {
        $userAgent = (string) $request->userAgent();
        $country = strtoupper((string) $request->header('CF-IPCountry'));

        return PageView::query()->create([
            'path' => Str::limit('/'.ltrim($request->path(), '/'), 500, ''),
            'route_name' => $request->route()?->getName(),
            'locale' => Str::limit(app()->getLocale(), 5, ''),
            'visitor_hash' => $this->visitorHash($request, $userAgent),
            'country' => Country::isCountry($country) ? $country : null,
            'region' => $this->headerText($request, 'CF-Region'),
            'city' => $this->headerText($request, 'CF-IPCity'),
            ...UserAgent::parse($userAgent),
            'referrer_host' => $this->referrerHost($request),
            'utm_source' => Str::limit(trim((string) $request->query('utm_source')), 100, '') ?: null,
        ]);
    }

    private function visitorHash(Request $request, string $userAgent): string
    {
        $ip = $request->header('CF-Connecting-IP') ?: $request->ip();

        return substr(hash('sha256', config('app.key').'|'.now()->toDateString().'|'.$ip.'|'.$userAgent), 0, 16);
    }

    /**
     * Sitio externo desde el que llegó (sin "www."); null si es el propio sitio o no hay.
     */
    private function referrerHost(Request $request): ?string
    {
        $host = parse_url((string) $request->headers->get('referer'), PHP_URL_HOST);

        if (! is_string($host) || $host === '') {
            return null;
        }

        $host = Str::lower(Str::after($host, 'www.'));

        return $host === Str::lower(Str::after($request->getHost(), 'www.')) ? null : Str::limit($host, 255, '');
    }

    private function headerText(Request $request, string $header): ?string
    {
        $value = trim(rawurldecode((string) $request->header($header)));

        return $value === '' ? null : Str::limit($value, 100, '');
    }
}
