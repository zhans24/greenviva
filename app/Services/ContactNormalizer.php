<?php

namespace App\Services;

use Illuminate\Support\Str;

class ContactNormalizer
{
    public static function onlyDigits(string $s): string
    {
        return preg_replace('/\D+/', '', $s) ?? '';
    }

    public static function normalizeTel(?string $raw): ?string
    {
        if (!$raw) return null;

        $digits = self::onlyDigits($raw);
        if ($digits === '') return null;

        if (Str::startsWith($digits, '8')) {
            $digits = '7' . substr($digits, 1);
        }

        return 'tel:+' . $digits;
    }

    public static function normalizeMailto(?string $raw): ?string
    {
        if (!$raw) return null;
        $raw = trim($raw);
        if ($raw === '') return null;

        if (Str::startsWith($raw, ['mailto:'])) return $raw;

        if (filter_var($raw, FILTER_VALIDATE_EMAIL)) {
            return 'mailto:' . $raw;
        }
        return null;
    }

    public static function normalizeWhatsapp(?string $raw): ?string
    {
        if (!$raw) return null;
        $raw = trim($raw);

        if (Str::startsWith($raw, ['http://', 'https://'])) {
            return preg_match('~^https?://(wa\.me|api\.whatsapp\.com)/~i', $raw)
                ? $raw
                : null;
        }

        $digits = self::onlyDigits($raw);
        if ($digits === '') return null;

        if (Str::startsWith($digits, '8')) {
            $digits = '7' . substr($digits, 1);
        }
        return 'https://wa.me/' . $digits;
    }

    public static function normalizeYouTube(?string $raw): ?string
    {
        if (!$raw) return null;
        $raw = trim($raw);

        if (Str::startsWith($raw, ['http://','https://'])) {
            return filter_var($raw, FILTER_VALIDATE_URL) ? $raw : null;
        }

        $handle = ltrim($raw, '@');
        return 'https://youtube.com/@' . $handle;
    }

    public static function normalizeTelegram(?string $raw): ?string
    {
        if (!$raw) return null;
        $raw = trim($raw);

        if (Str::startsWith($raw, ['http://','https://'])) {
            return filter_var($raw, FILTER_VALIDATE_URL) ? $raw : null;
        }

        $handle = ltrim($raw, '@');
        return 'https://t.me/' . $handle;
    }
}
