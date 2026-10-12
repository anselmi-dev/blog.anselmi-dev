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

    /**
     * Convierte un enlace de Spotify (track, album, playlist…) en su URL de embed.
     */
    public static function toEmbedUrl(?string $url): ?string
    {
        if (! self::isAllowed($url)) {
            return null;
        }

        $path = (string) parse_url($url, PHP_URL_PATH);
        $path = preg_replace('#^/(intl-[a-z-]+/)?(embed/)?#i', '', $path);

        if (! preg_match('#^(track|album|playlist|episode|show|artist)/[A-Za-z0-9]+$#', $path)) {
            return null;
        }

        return 'https://open.spotify.com/embed/'.$path;
    }
}
