<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Support\Domain\DomainManager;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

/**
 * Resuelve el dominio por Host y, si aplica, cambia la conexión DB del request.
 * No altera el portfolio cuando el host no está en config/domains.php.
 */
class IdentifyDomain
{
    public function __construct(
        private readonly DomainManager $domains,
    ) {}

    /**
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $domain = $this->domains->resolveByHost($request->getHost());

        if ($domain === null) {
            return $next($request);
        }

        $this->domains->setCurrent($domain);
        $request->attributes->set('domain', $domain);

        if ($domain->usesOwnDatabase()) {
            Config::set('database.default', $domain->connection);
            DB::setDefaultConnection((string) $domain->connection);
            DB::purge((string) $domain->connection);
            DB::reconnect((string) $domain->connection);
        }

        return $next($request);
    }
}
