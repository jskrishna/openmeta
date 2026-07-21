<?php

declare(strict_types=1);

namespace OpenMeta\Generator\Tests\Unit;

use OpenMeta\Generator\Files\FileGenerator;
use OpenMeta\Generator\Files\GeneratedFile;
use OpenMeta\Generator\Tests\Fixtures\InMemoryFilesystem;
use PHPUnit\Framework\TestCase;

final class FileGeneratorTest extends TestCase
{
    public function test_writes_file_contents(): void
    {
        $fs = new InMemoryFilesystem();
        $generator = new FileGenerator($fs);

        $generator->write(new GeneratedFile('src/App/Foo.php', '<?php // foo'));

        self::assertTrue($fs->isFile('src/App/Foo.php'));
        self::assertSame('<?php // foo', $fs->get('src/App/Foo.php'));
    }

    public function test_exists_reflects_filesystem(): void
    {
        $generator = new FileGenerator(new InMemoryFilesystem(['a.php' => 'x']));

        self::assertTrue($generator->exists('a.php'));
        self::assertFalse($generator->exists('b.php'));
    }
}
