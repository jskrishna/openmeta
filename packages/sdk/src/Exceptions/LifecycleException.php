<?php

declare(strict_types=1);

namespace OpenMeta\Sdk\Exceptions;

use OpenMeta\Sdk\Lifecycle\ExtensionState;

/**
 * Thrown when a lifecycle transition is not allowed from the current state.
 */
final class LifecycleException extends SdkException
{
    public static function illegalTransition(string $packageId, ExtensionState $from, string $action): self
    {
        return new self(sprintf(
            'Cannot %s extension [%s] from state [%s].',
            $action,
            $packageId,
            $from->value
        ));
    }

    public static function alreadyInstalled(string $packageId): self
    {
        return new self(sprintf('Extension [%s] is already installed.', $packageId));
    }
}
