<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class SiteColor extends Model
{
    protected $fillable = [
        'name',
        'hex',
        'rgb',
        'cmyk',
        'span',
        'ink',
        'column_index',
        'sort_order',
        'is_published',
    ];

    protected function casts(): array
    {
        return [
            'rgb' => 'array',
            'cmyk' => 'array',
            'is_published' => 'boolean',
            'column_index' => 'integer',
            'sort_order' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (SiteColor $color): void {
            $hex = ltrim((string) $color->hex, '#');
            if (strlen($hex) !== 6 || ! ctype_xdigit($hex)) {
                return;
            }

            $color->hex = '#'.strtoupper($hex);
            $r = hexdec(substr($hex, 0, 2));
            $g = hexdec(substr($hex, 2, 2));
            $b = hexdec(substr($hex, 4, 2));
            $color->rgb = [$r, $g, $b];

            $rn = $r / 255;
            $gn = $g / 255;
            $bn = $b / 255;
            $k = 1 - max($rn, $gn, $bn);
            if ($k >= 1) {
                $color->cmyk = [0, 0, 0, 100];

                return;
            }
            $color->cmyk = [
                (int) round(((1 - $rn - $k) / (1 - $k)) * 100),
                (int) round(((1 - $gn - $k) / (1 - $k)) * 100),
                (int) round(((1 - $bn - $k) / (1 - $k)) * 100),
                (int) round($k * 100),
            ];
        });
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query
            ->where('is_published', true)
            ->orderBy('column_index')
            ->orderBy('sort_order');
    }

    /**
     * @return array<string, mixed>
     */
    public function toViewArray(): array
    {
        return [
            'name' => $this->name,
            'hex' => $this->hex,
            'rgb' => $this->rgb ?? [],
            'cmyk' => $this->cmyk ?? [],
            'span' => $this->span,
            'ink' => $this->ink,
        ];
    }
}
