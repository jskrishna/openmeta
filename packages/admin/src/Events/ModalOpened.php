<?php

declare(strict_types=1);

namespace OpenMeta\Admin\Events;

final class ModalOpened
{
    public function __construct(
        public readonly string $modalId,
        public readonly string $type,
    ) {
    }
}
