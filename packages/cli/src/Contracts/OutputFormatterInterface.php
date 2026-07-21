<?php

declare(strict_types=1);

namespace OpenMeta\Cli\Contracts;

/**
 * Applies named styles to output. Third parties may register custom styles.
 */
interface OutputFormatterInterface
{
    public function format(string $style, string $message): string;

    /**
     * Register/override a style with an ANSI SGR code (e.g. "32" for green).
     */
    public function addStyle(string $style, string $ansi): void;

    public function setDecorated(bool $decorated): void;

    public function isDecorated(): bool;
}
