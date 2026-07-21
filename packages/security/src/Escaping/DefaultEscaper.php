<?php

declare(strict_types=1);

namespace OpenMeta\Security\Escaping;

use OpenMeta\Security\Contracts\EscaperInterface;

/**
 * Injectable escaper delegating to {@see Escaper} static helpers.
 */
final class DefaultEscaper implements EscaperInterface
{
    public function html(mixed $value): string
    {
        return Escaper::html($value);
    }

    public function attr(mixed $value): string
    {
        return Escaper::attr($value);
    }

    public function url(mixed $value): string
    {
        return Escaper::url($value);
    }

    public function js(mixed $value): string
    {
        return Escaper::js($value);
    }

    public function css(mixed $value): string
    {
        return Escaper::css($value);
    }

    public function json(mixed $value): string
    {
        return Escaper::json($value);
    }

    public function textarea(mixed $value): string
    {
        return Escaper::textarea($value);
    }
}
