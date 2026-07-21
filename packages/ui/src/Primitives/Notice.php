<?php

declare(strict_types=1);

namespace OpenMeta\Ui\Primitives;

use OpenMeta\Security\Escaping\Escaper;

/**
 * Inline notice / alert primitive.
 */
final class Notice
{
    public static function render(string $message, string $status = 'info'): string
    {
        return sprintf(
            '<div class="om-notice om-notice--%s" role="status">%s</div>',
            Escaper::attr($status),
            Escaper::html($message)
        );
    }
}
