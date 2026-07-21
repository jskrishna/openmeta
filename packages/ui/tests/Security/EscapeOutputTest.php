<?php

declare(strict_types=1);

namespace OpenMeta\Ui\Tests\Security;

use OpenMeta\Ui\Primitives\Notice;
use PHPUnit\Framework\TestCase;

final class EscapeOutputTest extends TestCase
{
    public function test_notice_escapes_message(): void
    {
        $html = Notice::render('<script>x</script>', 'error');
        $this->assertStringNotContainsString('<script>x</script>', $html);
    }
}
