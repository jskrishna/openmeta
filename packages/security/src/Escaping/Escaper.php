<?php

declare(strict_types=1);

namespace OpenMeta\Security\Escaping;

use JsonException;

/**
 * Outbound escaping for display contexts (pure PHP — no WordPress APIs).
 *
 * Static helpers remain the public convenience API; inject {@see DefaultEscaper} for DI.
 */
final class Escaper
{
    public static function html(mixed $value): string
    {
        return htmlspecialchars(self::string($value), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }

    public static function attr(mixed $value): string
    {
        return htmlspecialchars(self::string($value), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }

    public static function url(mixed $value): string
    {
        $value = self::string($value);
        $filtered = filter_var($value, FILTER_SANITIZE_URL);

        return is_string($filtered) ? $filtered : '';
    }

    public static function js(mixed $value): string
    {
        $value = self::string($value);

        return str_replace(
            ['\\', "'", '"', "\n", "\r", '</'],
            ['\\\\', "\\'", '\\"', '\\n', '\\r', '<\\/'],
            $value
        );
    }

    public static function css(mixed $value): string
    {
        $value = self::string($value);

        return preg_replace('/[^a-zA-Z0-9\-_]/', '', $value) ?? '';
    }

    public static function json(mixed $value): string
    {
        try {
            return json_encode(
                $value,
                JSON_THROW_ON_ERROR | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT
            );
        } catch (JsonException) {
            return 'null';
        }
    }

    public static function textarea(mixed $value): string
    {
        return htmlspecialchars(self::string($value), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }

    private static function string(mixed $value): string
    {
        if ($value === null) {
            return '';
        }

        if (is_bool($value)) {
            return $value ? '1' : '0';
        }

        if (is_scalar($value)) {
            return (string) $value;
        }

        return '';
    }
}
