<?php

declare(strict_types=1);

namespace App\Models;

use App\Support\CursorStyle;
use App\Support\Localization;
use BackedEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = [
        'group',
        'key',
        'value',
    ];

    public static function get(string $key, mixed $default = null): mixed
    {
        [$group, $name] = static::splitKey($key);

        $raw = static::query()
            ->where('group', $group)
            ->where('key', $name)
            ->value('value');

        if ($raw === null) {
            return $default;
        }

        return static::decode($raw);
    }

    public static function put(string $key, mixed $value): void
    {
        [$group, $name] = static::splitKey($key);

        static::query()->updateOrCreate(
            ['group' => $group, 'key' => $name],
            ['value' => static::encode($value)],
        );

        static::forgetGroupCache($group);
    }

    /**
     * @param  array<string, mixed>  $values
     */
    public static function putMany(string $group, array $values): void
    {
        foreach ($values as $key => $value) {
            static::query()->updateOrCreate(
                ['group' => $group, 'key' => $key],
                ['value' => static::encode($value)],
            );
        }

        static::forgetGroupCache($group);
    }

    /**
     * @param  array<string, mixed>  $defaults
     * @return array<string, mixed>
     */
    public static function group(string $group, array $defaults = []): array
    {
        return Cache::remember("settings.group.{$group}", 3600, function () use ($group, $defaults): array {
            $stored = static::query()
                ->where('group', $group)
                ->pluck('value', 'key')
                ->map(fn (?string $value): mixed => static::decode($value))
                ->all();

            return array_replace($defaults, $stored);
        });
    }

    /**
     * @return array<string, mixed>
     */
    public static function contactDefaults(): array
    {
        return [
            'email' => config('mail.from.address', 'hola@ejemplo.com'),
            'email_label' => 'Escribinos',
            'email_description' => 'Respuesta humana, sin vueltas.',
            'location_label' => 'Ubicación',
            'location_description' => 'Trabajo en remoto; podemos coordinar videollamada cuando haga falta.',
            'schedule_label' => 'Horario',
            'schedule_description' => 'Lun a vie, horario flexible (UTC−3).',
            'form_title' => '¿Ideas en mente? Sumemos.',
            'form_description' => 'Contame sobre vos y qué tenés en mente.',
            'submit_label' => '¡Arranquemos!',
            'success_title' => '¡Recibido!',
            'success_description' => 'Gracias por escribir. Te respondo a la brevedad.',
            'social_links' => [],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public static function siteDefaults(): array
    {
        return [
            'meta_description' => 'Desarrollador web: diseño y construyo productos digitales con foco en claridad, performance y experiencia de uso.',
            'author_name' => config('app.name', 'Sitio'),
            'tools_loader' => false,
            'cursor_style' => CursorStyle::Circle->value,
            'background_squares' => true,
            'background_squares_opacity' => 75,
            'background_squares_speed' => 1,
        ];
    }

    /**
     * @return array{
     *     email: string,
     *     email_label: string,
     *     email_description: string|null,
     *     location_label: string|null,
     *     location_description: string|null,
     *     schedule_label: string|null,
     *     schedule_description: string|null,
     *     form_title: string|null,
     *     form_description: string|null,
     *     submit_label: string|null,
     *     success_title: string|null,
     *     success_description: string|null,
     *     social_links: list<array{label: string, url: string|null}>
     * }
     */
    public static function contact(): array
    {
        $contact = static::group('contact', static::contactDefaults());

        $contact['social_links'] = collect($contact['social_links'] ?? [])
            ->filter(fn ($link): bool => is_array($link) && filled($link['label'] ?? null))
            ->map(fn (array $link): array => [
                'label' => (string) $link['label'],
                'url' => filled($link['url'] ?? null) ? (string) $link['url'] : null,
            ])
            ->values()
            ->all();

        return $contact;
    }

    /**
     * @return array{meta_description: string|null, author_name: string|null, tools_loader: bool|null, cursor_style: string|null, background_squares: bool|null, background_squares_opacity: int|float|null, background_squares_speed: int|float|null}
     */
    public static function site(): array
    {
        return static::group('site', static::siteDefaults());
    }

    /**
     * Textos de contacto que tienen versión en inglés ("{clave}_en").
     *
     * @var list<string>
     */
    public const TRANSLATABLE_CONTACT_KEYS = [
        'email_label',
        'email_description',
        'location_label',
        'location_description',
        'schedule_label',
        'schedule_description',
        'form_title',
        'form_description',
        'submit_label',
        'success_title',
        'success_description',
    ];

    /**
     * Ajustes de contacto en el idioma actual del sitio público.
     *
     * @return array<string, mixed>
     */
    public static function localizedContact(): array
    {
        return static::localize(static::contact(), self::TRANSLATABLE_CONTACT_KEYS);
    }

    /**
     * Ajustes del sitio en el idioma actual del sitio público.
     *
     * @return array<string, mixed>
     */
    public static function localizedSite(): array
    {
        return static::localize(static::site(), ['meta_description']);
    }

    /**
     * En inglés usa "{clave}_en" si tiene valor; si no, la traducción del texto
     * en español cuando existe en lang/en.json (los textos por defecto la
     * tienen) o el texto en español tal cual.
     *
     * @param  array<string, mixed>  $values
     * @param  list<string>  $keys
     * @return array<string, mixed>
     */
    protected static function localize(array $values, array $keys): array
    {
        if (! Localization::isEnglish()) {
            return $values;
        }

        foreach ($keys as $key) {
            $englishValue = $values[$key.'_en'] ?? null;

            if (filled($englishValue)) {
                $values[$key] = $englishValue;
            } elseif (is_string($values[$key] ?? null) && filled($values[$key])) {
                $values[$key] = __($values[$key]);
            }
        }

        return $values;
    }

    public static function forgetGroupCache(string $group): void
    {
        Cache::forget("settings.group.{$group}");
    }

    /**
     * @return array{0: string, 1: string}
     */
    protected static function splitKey(string $key): array
    {
        if (! str_contains($key, '.')) {
            throw new \InvalidArgumentException("Setting key [{$key}] must use group.key format.");
        }

        return explode('.', $key, 2);
    }

    protected static function encode(mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }

        if ($value instanceof BackedEnum) {
            return (string) $value->value;
        }

        if (is_bool($value) || is_int($value) || is_float($value) || is_array($value)) {
            return json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        }

        return (string) $value;
    }

    protected static function decode(?string $value): mixed
    {
        if ($value === null || $value === '') {
            return null;
        }

        $trimmed = ltrim($value);

        if ($trimmed !== '' && ($trimmed[0] === '{' || $trimmed[0] === '[' || $trimmed === 'true' || $trimmed === 'false' || $trimmed === 'null' || is_numeric($trimmed))) {
            $decoded = json_decode($value, true);

            if (json_last_error() === JSON_ERROR_NONE) {
                return $decoded;
            }
        }

        return $value;
    }
}
