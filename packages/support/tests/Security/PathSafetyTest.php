<?php

declare(strict_types=1);

namespace OpenMeta\Support\Tests\Security;

use OpenMeta\Support\Paths\Path;
use OpenMeta\Support\Tests\SupportTestCase;

final class PathSafetyTest extends SupportTestCase
{
    public function test_join_keeps_segments_literal(): void
    {
        $joined = Path::join('/var', 'www', 'file.php');
        $this->assertStringContainsString('file.php', $joined);
        $this->assertDoesNotMatchRegularExpression('/;\s*rm\s+-rf/', $joined);
    }
}
