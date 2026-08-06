<?php

namespace App\Support;

final class SpotifyEmbed
{
    private const ALLOWED_HOST = 'open.spotify.com';

    public static function isAllowed(?string $url): bool
    {
        if ($url === null || $url === '') {
            return false;
        }

        $host = parse_url($url, PHP_URL_HOST);

        if (! is_string($host)) {
            return false;
        }

        return strtolower($host) === self::ALLOWED_HOST;
    }

    public static function sanitize(?string $url): ?string
    {
        if (! self::isAllowed($url)) {
            return null;
        }

        return $url;
    }
}
