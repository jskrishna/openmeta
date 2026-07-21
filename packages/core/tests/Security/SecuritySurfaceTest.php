<?php

declare(strict_types=1);

namespace OpenMeta\Core\Tests\Security;

use OpenMeta\Core\Exceptions\OpenMetaException;
use OpenMeta\Core\Tests\CoreTestCase;

final class SecuritySurfaceTest extends CoreTestCase
{
    public function test_typed_exceptions_do_not_expose_stack_in_message(): void
    {
        $e = new OpenMetaException('Access denied');
        $this->assertSame('Access denied', $e->getMessage());
        $this->assertStringNotContainsString('vendor/', $e->getMessage());
    }
}
