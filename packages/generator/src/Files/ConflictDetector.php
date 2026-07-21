<?php

declare(strict_types=1);

namespace OpenMeta\Generator\Files;

use OpenMeta\Generator\Configuration\GeneratorConfiguration;
use OpenMeta\Generator\Contracts\ConflictDetectorInterface;
use OpenMeta\Generator\Contracts\NamespaceResolverInterface;
use OpenMeta\Support\Filesystem\FilesystemInterface;

/**
 * Detects, in order: reserved-namespace, naming-collision (already-declared
 * class), and existing-file conflicts.
 */
final class ConflictDetector implements ConflictDetectorInterface
{
    public function __construct(
        private readonly FilesystemInterface $filesystem,
        private readonly NamespaceResolverInterface $namespaces,
    ) {
    }

    public function detect(string $path, string $fqcn, GeneratorConfiguration $config): ?Conflict
    {
        if ($fqcn !== '') {
            $namespace = $this->namespaceOf($fqcn);

            if ($this->namespaces->isReserved($config, $namespace)) {
                return new Conflict(
                    ConflictType::ReservedNamespace,
                    $namespace,
                    sprintf('Namespace [%s] is reserved.', $namespace),
                );
            }

            if (class_exists($fqcn)) {
                return new Conflict(
                    ConflictType::NamingCollision,
                    $fqcn,
                    sprintf('Class [%s] already exists.', $fqcn),
                );
            }
        }

        if ($this->filesystem->isFile($path)) {
            return new Conflict(
                ConflictType::ExistingFile,
                $path,
                sprintf('File [%s] already exists.', $path),
            );
        }

        return null;
    }

    private function namespaceOf(string $fqcn): string
    {
        $fqcn = ltrim($fqcn, '\\');
        $position = strrpos($fqcn, '\\');

        return $position === false ? '' : substr($fqcn, 0, $position);
    }
}
