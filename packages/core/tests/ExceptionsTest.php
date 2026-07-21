<?php

declare(strict_types=1);

namespace OpenMeta\Core\Tests;

use OpenMeta\Core\Exceptions\BindingResolutionException;
use OpenMeta\Core\Exceptions\OpenMetaException;

final class ExceptionsTest extends CoreTestCase
{
    public function test_binding_resolution_extends_openmeta_exception(): void
    {
        $exception = new BindingResolutionException('missing');

        self::assertInstanceOf(OpenMetaException::class, $exception);
        self::assertSame('missing', $exception->getMessage());
    }

    public function test_openmeta_exception_is_throwable(): void
    {
        $this->expectException(OpenMetaException::class);
        $this->expectExceptionMessage('boom');

        throw new OpenMetaException('boom');
    }
}
