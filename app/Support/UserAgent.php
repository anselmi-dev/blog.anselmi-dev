<?php

declare(strict_types=1);

namespace App\Support;

/**
 * Lectura simple del user agent: tipo de dispositivo, navegador y sistema,
 * y si es un bot. Alcanza para estadísticas; no intenta ser exhaustiva.
 */
final class UserAgent
{
    private const string BOTS = '/bot|crawl|spider|slurp|mediapartners|facebookexternalhit|embedly|quora link preview|'
        .'whatsapp|telegrambot|discordbot|slackbot|linkedinbot|twitterbot|pinterest|skypeuripreview|'
        .'headless|lighthouse|pagespeed|gtmetrix|pingdom|uptime|monitor|curl|wget|python-requests|'
        .'go-http-client|httpclient|axios|node-fetch|okhttp|java\/|libwww|scrapy|preview/i';

    /**
     * @return array{device: 'desktop'|'mobile'|'tablet', browser: string, os: string}
     */
    public static function parse(?string $userAgent): array
    {
        $ua = (string) $userAgent;

        return [
            'device' => self::device($ua),
            'browser' => self::browser($ua),
            'os' => self::os($ua),
        ];
    }

    public static function isBot(?string $userAgent): bool
    {
        return blank($userAgent) || preg_match(self::BOTS, $userAgent) === 1;
    }

    /**
     * @return 'desktop'|'mobile'|'tablet'
     */
    private static function device(string $ua): string
    {
        if (preg_match('/iPad|Tablet|PlayBook|Silk|Kindle|Android(?!.*Mobile)/i', $ua) === 1) {
            return 'tablet';
        }

        if (preg_match('/Mobi|iPhone|iPod|Android.*Mobile|Windows Phone|BlackBerry|Opera Mini/i', $ua) === 1) {
            return 'mobile';
        }

        return 'desktop';
    }

    private static function browser(string $ua): string
    {
        return match (true) {
            str_contains($ua, 'Edg/'), str_contains($ua, 'EdgiOS'), str_contains($ua, 'EdgA/') => 'Edge',
            str_contains($ua, 'OPR/'), str_contains($ua, 'Opera') => 'Opera',
            str_contains($ua, 'SamsungBrowser') => 'Samsung Internet',
            str_contains($ua, 'Firefox/'), str_contains($ua, 'FxiOS') => 'Firefox',
            str_contains($ua, 'CriOS'), str_contains($ua, 'Chrome/') => 'Chrome',
            str_contains($ua, 'Safari/') => 'Safari',
            default => 'Otro',
        };
    }

    private static function os(string $ua): string
    {
        return match (true) {
            str_contains($ua, 'Windows') => 'Windows',
            str_contains($ua, 'iPhone'), str_contains($ua, 'iPod') => 'iOS',
            str_contains($ua, 'iPad') => 'iPadOS',
            str_contains($ua, 'Android') => 'Android',
            str_contains($ua, 'CrOS') => 'ChromeOS',
            str_contains($ua, 'Mac OS X'), str_contains($ua, 'Macintosh') => 'macOS',
            str_contains($ua, 'Linux') => 'Linux',
            default => 'Otro',
        };
    }
}
