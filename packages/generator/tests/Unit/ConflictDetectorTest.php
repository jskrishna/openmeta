<?php

declare(strict_types=1);

namespace OpenMeta\Generator\Tests\Unit;

use OpenMeta\Generator\Configuration\GeneratorConfiguration;
use OpenMeta\Generator\Files\ConflictDetector;
use OpenMeta\Generator\Files\ConflictType;
use OpenMeta\Generator\Resolvers\NamespaceResolver;
use OpenMeta\Generator\Tests\Fixtures\InMemoryFilesystem;
use PHPUnit\Framework\TestCase;

final class ConflictDetectorTest extends TestCase
{
    private function detector(InMemoryFilesystem $fs): ConflictDetector
    {
        return new ConflictDetector($fs, new NamespaceResolver());
    }

    public function test_no_conflict_when_clear(): void
    {
        $detector = $this->detector(new InMemoryFilesystem());

        self::assertNull($detector->detect('src/App/Foo.php', 'App\\Foo', new GeneratorConfiguration()));
    }

    public function test_reserved_namespace_conflict(): void
    {
        $detector = $this->detector(new InMemoryFilesystem());

        $conflict = $detector->detect('src/Foo.php', 'OpenMeta\\Core\\Foo', new GeneratorConfiguration());

        self::assertNotNull($conflict);
        self::assertSame(ConflictType::ReservedNamespace, $conflict->type);
    }

    public function test_naming_collision_conflict(): void
    {
        $detector = $this->detector(new InMemoryFilesystem());
        $config = new GeneratorConfiguration(reservedNamespaces: []);

        $conflict = $detector->detect('src/stdClass.php', 'stdClass', $config);

        self::assertNotNull($conflict);
        self::assertSame(ConflictType::NamingCollision, $conflict->type);
    }

    public function test_existing_file_conflict(): void
    {
        $detector = $this->detector(new InMemoryFilesystem(['src/App/Foo.php' => 'x']));

        $conflict = $detector->detect('src/App/Foo.php', 'App\\Foo', new GeneratorConfiguration());

        self::assertNotNull($conflict);
        self::assertSame(ConflictType::ExistingFile, $conflict->type);
    }
}
