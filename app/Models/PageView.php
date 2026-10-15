<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\PageViewFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\MassPrunable;
use Illuminate\Database\Eloquent\Model;

/**
 * Una página vista del sitio público. Sin IP ni cookies: el visitante se
 * identifica con una huella que cambia cada día (ver RecordPageView).
 */
class PageView extends Model
{
    /** @use HasFactory<PageViewFactory> */
    use HasFactory, MassPrunable;

    /** Meses que se guardan las visitas; las más viejas se borran solas. */
    public const int KEEP_MONTHS = 13;

    public const UPDATED_AT = null;

    protected $fillable = [
        'path',
        'route_name',
        'locale',
        'visitor_hash',
        'country',
        'region',
        'city',
        'device',
        'browser',
        'os',
        'referrer_host',
        'utm_source',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }

    public function prunable(): Builder
    {
        return static::query()->where('created_at', '<', now()->subMonths(self::KEEP_MONTHS));
    }

    public function scopeSince(Builder $query, \DateTimeInterface $since): Builder
    {
        return $query->where('created_at', '>=', $since);
    }
}
