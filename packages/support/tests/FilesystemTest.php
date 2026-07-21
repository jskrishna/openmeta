<?php

declare(strict_types=1);

namespace OpenMeta\Support\Tests;

use OpenMeta\Support\Filesystem\LocalFilesystem;
use OpenMeta\Support\Paths\Path;

final class FilesystemTest extends SupportTestCase
{
    private string $dir;

    private LocalFilesystem $fs;

    protected function setUp(): void
    {
        parent::setUp();
        $this->dir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'openmeta-support-' . uniqid('', true);
        $this->fs = new LocalFilesystem();
        $this->fs->makeDirectory($this->dir);
    }

    protected function tearDown(): void
    {
        $file = Path::join($this->dir, 'note.txt');
        if ($this->fs->exists($file)) {
            $this->fs->delete($file);
        }
        if ($this->fs->isDirectory($this->dir)) {
            $this->fs->delete($this->dir);
        }
        parent::tearDown();
    }

    public function test_put_get_exists_delete(): void
    {
        $file = Path::join($this->dir, 'note.txt');

        $this->fs->put($file, 'hello');
        self::assertTrue($this->fs->exists($file));
        self::assertTrue($this->fs->isFile($file));
        self::assertSame('hello', $this->fs->get($file));
        self::assertTrue($this->fs->delete($file));
        self::assertFalse($this->fs->exists($file));
    }
}
