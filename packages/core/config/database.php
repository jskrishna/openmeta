<?php

/**
 * Database configuration placeholders.
 *
 * Core Bootstrap does not connect to a database — values are reserved for later packages.
 *
 * @return array<string, mixed>
 */

declare(strict_types=1);

return [
    'default' => 'memory',
    'connections' => [
        'memory' => [
            'driver' => 'memory',
            'prefix' => 'om_',
        ],
        // Reserved for @openmeta/wordpress wpdb adapter (maps to memory until bound).
        'wordpress' => [
            'driver' => 'wordpress',
            'prefix' => 'om_',
        ],
    ],
];
