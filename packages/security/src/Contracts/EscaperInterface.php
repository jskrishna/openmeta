<?php

declare(strict_types=1);

namespace OpenMeta\Security\Contracts;

/**
 * Outbound escaping for display contexts.
 */
interface EscaperInterface
{
    public function html(mixed $value): string;

    public function attr(mixed $value): string;

    public function url(mixed $value): string;

    public function js(mixed $value): string;

    public function css(mixed $value): string;

    public function json(mixed $value): string;

    public function textarea(mixed $value): string;
}
