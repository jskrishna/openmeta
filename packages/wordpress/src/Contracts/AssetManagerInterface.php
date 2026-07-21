<?php

declare(strict_types=1);

namespace OpenMeta\Wordpress\Contracts;

/**
 * Script/style registration and enqueue contract.
 */
interface AssetManagerInterface
{
    /**
     * @param list<string> $deps
     */
    public function registerScript(
        string $handle,
        string $src,
        array $deps = [],
        string|false $version = false,
        bool $inFooter = true,
    ): bool;

    /**
     * @param list<string> $deps
     */
    public function registerStyle(
        string $handle,
        string $src,
        array $deps = [],
        string|false $version = false,
    ): bool;

    public function enqueueScript(string $handle): void;

    public function enqueueStyle(string $handle): void;
}
