<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Actions\Analytics\RecordPageView;
use App\Support\UserAgent;
use Closure;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Cuenta las visitas de las páginas públicas. Se guarda en terminate(), cuando
 * la respuesta ya salió, así no demora la página.
 */
class TrackPageView
{
    public function __construct(private RecordPageView $recordPageView) {}

    public function handle(Request $request, Closure $next): Response
    {
        return $next($request);
    }

    public function terminate(Request $request, Response $response): void
    {
        if (! $this->shouldRecord($request, $response)) {
            return;
        }

        try {
            $this->recordPageView->handle($request);
        } catch (\Throwable $exception) {
            report($exception);
        }
    }

    /**
     * Solo páginas HTML que respondieron bien, vistas por personas que no
     * administran el sitio (quien puede entrar al panel no suma visitas).
     */
    private function shouldRecord(Request $request, Response $response): bool
    {
        return $request->isMethod('GET')
            && $response->getStatusCode() === 200
            && str_contains((string) $response->headers->get('Content-Type'), 'text/html')
            && ! $this->isAdmin($request)
            && ! $this->isPrefetch($request)
            && ! UserAgent::isBot($request->userAgent());
    }

    private function isAdmin(Request $request): bool
    {
        $user = $request->user();

        return $user instanceof FilamentUser && $user->canAccessPanel(filament()->getPanel('admin'));
    }

    private function isPrefetch(Request $request): bool
    {
        $purpose = strtolower((string) ($request->header('Sec-Purpose') ?: $request->header('Purpose')));

        return str_contains($purpose, 'prefetch');
    }
}
