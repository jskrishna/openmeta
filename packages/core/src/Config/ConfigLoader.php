<?php

declare(strict_types=1);

namespace OpenMeta\Core\Config;

use OpenMeta\Core\Exceptions\OpenMetaException;

/**
 * Loads PHP config files from a directory into a nested array.
 *
 * Each `*.php` file becomes a top-level key named after the file
 * (e.g. `app.php` → `app`, `database.php` → `database`).
 */
final class ConfigLoader
{
    /**
     * @return array<string, mixed>
     */
    public static function load(string $directory): array
    {
        if (! is_dir($directory)) {
            throw new OpenMetaException(sprintf(
                'Configuration directory [%s] does not exist.',
                $directory
            ));
        }

        $items = [];
        $files = glob(rtrim($directory, '/\\') . DIRECTORY_SEPARATOR . '*.php') ?: [];

        foreach ($files as $file) {
            $key = basename($file, '.php');
            /** @var mixed $value */
            $value = require $file;

            if (! is_array($value)) {
                throw new OpenMetaException(sprintf(
                    'Configuration file [%s] must return an array.',
                    $file
                ));
            }

            $items[$key] = $value;
        }

        ksort($items);

        return $items;
    }
}
