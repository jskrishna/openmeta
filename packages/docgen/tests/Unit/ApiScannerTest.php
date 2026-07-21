<?php

declare(strict_types=1);

namespace OpenMeta\Docgen\Tests\Unit;

use OpenMeta\Docgen\Api\ApiScanner;
use OpenMeta\Docgen\Model\TypeDoc;
use PHPUnit\Framework\TestCase;

final class ApiScannerTest extends TestCase
{
    public function test_scans_a_package_src_tree(): void
    {
        $types = (new ApiScanner())->scan(dirname(__DIR__, 2) . '/src');

        self::assertNotEmpty($types);

        $byName = [];
        foreach ($types as $type) {
            $byName[$type->fqcn] = $type;
        }

        self::assertArrayHasKey(\OpenMeta\Docgen\Manager\DocumentationManager::class, $byName);

        $manager = $byName[\OpenMeta\Docgen\Manager\DocumentationManager::class];
        self::assertInstanceOf(TypeDoc::class, $manager);
        self::assertSame('class', $manager->kind);
        $methodNames = array_map(static fn ($m): string => $m->name, $manager->methods);
        self::assertContains('validate', $methodNames);
    }
}
