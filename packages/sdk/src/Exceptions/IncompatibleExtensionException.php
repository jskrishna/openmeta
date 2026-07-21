<?php

declare(strict_types=1);

namespace OpenMeta\Sdk\Exceptions;

/**
 * Thrown when an extension is not compatible with the current environment.
 */
final class IncompatibleExtensionException extends SdkException
{
    /**
     * @param list<string> $issues
     */
    public static function forIssues(string $packageId, array $issues): self
    {
        return new self(sprintf(
            'Extension [%s] is not compatible: %s',
            $packageId,
            implode('; ', $issues)
        ));
    }
}
