<?php

declare(strict_types=1);

namespace OpenMeta\Docgen\Cli;

use OpenMeta\Cli\Contracts\CommandProviderInterface;
use OpenMeta\Docgen\Manager\DocumentationManager;

/**
 * Exposes the `docs:*` commands to the console via the CLI extension point.
 */
final class DocsCommandProvider implements CommandProviderInterface
{
    public function __construct(private readonly DocumentationManager $docs)
    {
    }

    public function commands(): iterable
    {
        yield new DocsValidateCommand($this->docs);
        yield new DocsApiCommand($this->docs);
        yield new DocsBuildCommand($this->docs);
    }
}
