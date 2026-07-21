<?php

declare(strict_types=1);

namespace OpenMeta\Admin\Assets;

/**
 * Declarative asset handles for the WordPress Asset Manager bridge.
 *
 * Admin never calls wp_enqueue_* directly.
 */
final class AssetRegistry
{
    /** @var list<array{handle: string, src: string, type: string, deps: list<string>}> */
    private array $assets = [];

    /**
     * @param list<string> $deps
     */
    public function script(string $handle, string $src, array $deps = []): void
    {
        $this->assets[] = [
            'handle' => $handle,
            'src' => $src,
            'type' => 'script',
            'deps' => $deps,
        ];
    }

    /**
     * @param list<string> $deps
     */
    public function style(string $handle, string $src, array $deps = []): void
    {
        $this->assets[] = [
            'handle' => $handle,
            'src' => $src,
            'type' => 'style',
            'deps' => $deps,
        ];
    }

    /** @return list<array{handle: string, src: string, type: string, deps: list<string>}> */
    public function all(): array
    {
        return $this->assets;
    }

    /** @return list<array{handle: string, src: string, type: string, deps: list<string>}> */
    public function forScreen(string $screenId): array
    {
        return array_values(array_filter(
            $this->assets,
            static fn (array $asset): bool => str_starts_with($asset['handle'], 'openmeta-' . $screenId)
                || str_starts_with($asset['handle'], 'openmeta-admin')
        ));
    }
}
