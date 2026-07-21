<?php

declare(strict_types=1);

namespace OpenMeta\Support\Tests;

use OpenMeta\Core\Bootstrap\Bootstrap;
use OpenMeta\Support\Filesystem\FilesystemInterface;
use OpenMeta\Support\Helpers\Helpers;
use OpenMeta\Support\Paths\Path;
use OpenMeta\Support\SupportServiceProvider;

final class SupportIntegrationTest extends SupportTestCase
{
    public function test_provider_binds_filesystem_and_helpers_compose_path_write(): void
    {
        $app = Bootstrap::run([], [SupportServiceProvider::class]);

        /** @var FilesystemInterface $fs */
        $fs = $app->get(FilesystemInterface::class);

        $dir = Path::join(sys_get_temp_dir(), 'openmeta-support-int-' . uniqid('', true));
        $file = Path::join($dir, 'probe.txt');

        Helpers::tap($file, static function (string $path) use ($fs): void {
            $fs->put($path, 'support');
        });

        self::assertSame('support', $fs->get($file));
        self::assertTrue($fs->delete($file));
        self::assertTrue($fs->delete($dir));
        self::assertTrue($app->has('filesystem'));
    }
}
