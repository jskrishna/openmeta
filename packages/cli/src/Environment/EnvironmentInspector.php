<?php

declare(strict_types=1);

namespace OpenMeta\Cli\Environment;

/**
 * Inspects the runtime: PHP version, extensions, and OpenMeta package status.
 */
final class EnvironmentInspector
{
    private const MINIMUM_PHP = '8.3.0';

    private const REQUIRED_EXTENSIONS = ['json', 'mbstring'];

    /** @var array<string, class-string> package id => a marker class */
    private const PACKAGES = [
        'core' => \OpenMeta\Core\Application\Application::class,
        'support' => \OpenMeta\Support\Str\Str::class,
        'validation' => \OpenMeta\Validation\Validation::class,
    ];

    public function phpVersion(): string
    {
        return PHP_VERSION;
    }

    public function meetsMinimumPhp(): bool
    {
        return version_compare(PHP_VERSION, self::MINIMUM_PHP, '>=');
    }

    /**
     * @return list<string>
     */
    public function extensions(): array
    {
        return get_loaded_extensions();
    }

    public function hasExtension(string $name): bool
    {
        return extension_loaded($name);
    }

    public function isWritable(string $path): bool
    {
        return is_writable($path);
    }

    /**
     * @return array<string, bool> package id => available
     */
    public function packageStatus(): array
    {
        $status = [];

        foreach (self::PACKAGES as $package => $marker) {
            $status[$package] = class_exists($marker);
        }

        return $status;
    }

    /**
     * @return list<EnvironmentCheck>
     */
    public function checks(): array
    {
        $checks = [];

        $checks[] = new EnvironmentCheck(
            'PHP >= ' . self::MINIMUM_PHP,
            $this->meetsMinimumPhp(),
            'Running ' . PHP_VERSION,
        );

        foreach (self::REQUIRED_EXTENSIONS as $extension) {
            $checks[] = new EnvironmentCheck(
                'ext-' . $extension,
                $this->hasExtension($extension),
                $this->hasExtension($extension) ? 'loaded' : 'missing',
            );
        }

        foreach ($this->packageStatus() as $package => $available) {
            $checks[] = new EnvironmentCheck(
                'openmeta/' . $package,
                $available,
                $available ? 'available' : 'not autoloadable',
            );
        }

        return $checks;
    }

    /**
     * @return array<string, mixed>
     */
    public function report(): array
    {
        return [
            'php' => PHP_VERSION,
            'os' => PHP_OS_FAMILY,
            'extensions' => count($this->extensions()),
            'packages' => $this->packageStatus(),
        ];
    }
}
