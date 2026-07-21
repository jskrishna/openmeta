<?php

declare(strict_types=1);

namespace OpenMeta\Wordpress\Plugin;

/**
 * Environment gate before bootstrap.
 */
final class Requirements
{
    public const MIN_PHP = '8.3.0';

    public const MIN_WP = '6.4.0';

    /**
     * @return list<string> Failure messages (empty = ok)
     */
    public function check(?string $phpVersion = null, ?string $wpVersion = null): array
    {
        $failures = [];
        $phpVersion ??= PHP_VERSION;

        if (version_compare($phpVersion, self::MIN_PHP, '<')) {
            $failures[] = sprintf(
                'OpenMeta requires PHP %s or newer (running %s).',
                self::MIN_PHP,
                $phpVersion
            );
        }

        if ($wpVersion !== null && version_compare($wpVersion, self::MIN_WP, '<')) {
            $failures[] = sprintf(
                'OpenMeta requires WordPress %s or newer (running %s).',
                self::MIN_WP,
                $wpVersion
            );
        }

        return $failures;
    }

    public function passes(?string $phpVersion = null, ?string $wpVersion = null): bool
    {
        return $this->check($phpVersion, $wpVersion) === [];
    }
}
