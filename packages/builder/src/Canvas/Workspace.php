<?php

declare(strict_types=1);

namespace OpenMeta\Builder\Canvas;

/**
 * Viewport metadata for the canvas workspace (zoom, pan, grid, snap).
 *
 * Architecture only — no rendering implementation.
 */
final class Workspace
{
    public function __construct(
        private float $zoom = 1.0,
        private int $panX = 0,
        private int $panY = 0,
        private bool $gridEnabled = true,
        private int $gridSize = 8,
        private bool $snapEnabled = true,
    ) {
    }

    public function zoom(): float
    {
        return $this->zoom;
    }

    public function withZoom(float $zoom): self
    {
        return new self(
            max(0.25, min(4.0, $zoom)),
            $this->panX,
            $this->panY,
            $this->gridEnabled,
            $this->gridSize,
            $this->snapEnabled,
        );
    }

    public function panX(): int
    {
        return $this->panX;
    }

    public function panY(): int
    {
        return $this->panY;
    }

    public function withPan(int $x, int $y): self
    {
        return new self($this->zoom, $x, $y, $this->gridEnabled, $this->gridSize, $this->snapEnabled);
    }

    public function gridEnabled(): bool
    {
        return $this->gridEnabled;
    }

    public function gridSize(): int
    {
        return $this->gridSize;
    }

    public function snapEnabled(): bool
    {
        return $this->snapEnabled;
    }

    public function snap(int $value): int
    {
        if (! $this->snapEnabled || $this->gridSize <= 0) {
            return $value;
        }

        return (int) (round($value / $this->gridSize) * $this->gridSize);
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return [
            'zoom' => $this->zoom,
            'pan' => ['x' => $this->panX, 'y' => $this->panY],
            'grid' => ['enabled' => $this->gridEnabled, 'size' => $this->gridSize],
            'snap' => ['enabled' => $this->snapEnabled],
        ];
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        $pan = is_array($data['pan'] ?? null) ? $data['pan'] : [];
        $grid = is_array($data['grid'] ?? null) ? $data['grid'] : [];
        $snap = is_array($data['snap'] ?? null) ? $data['snap'] : [];

        return new self(
            isset($data['zoom']) && is_numeric($data['zoom']) ? (float) $data['zoom'] : 1.0,
            isset($pan['x']) && is_numeric($pan['x']) ? (int) $pan['x'] : 0,
            isset($pan['y']) && is_numeric($pan['y']) ? (int) $pan['y'] : 0,
            (bool) ($grid['enabled'] ?? true),
            isset($grid['size']) && is_numeric($grid['size']) ? (int) $grid['size'] : 8,
            (bool) ($snap['enabled'] ?? true),
        );
    }
}
