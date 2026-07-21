<?php

declare(strict_types=1);

namespace OpenMeta\Support\Tests;

use OpenMeta\Support\Environment\Env;

final class EnvTest extends SupportTestCase
{
    public function test_typed_getters(): void
    {
        $_ENV['OPENMETA_SUPPORT_TEST_STR'] = 'alpha';
        $_ENV['OPENMETA_SUPPORT_TEST_BOOL'] = 'true';
        $_ENV['OPENMETA_SUPPORT_TEST_INT'] = '42';

        self::assertTrue(Env::has('OPENMETA_SUPPORT_TEST_STR'));
        self::assertSame('alpha', Env::string('OPENMETA_SUPPORT_TEST_STR'));
        self::assertTrue(Env::bool('OPENMETA_SUPPORT_TEST_BOOL'));
        self::assertSame(42, Env::int('OPENMETA_SUPPORT_TEST_INT'));
        self::assertSame('fallback', Env::string('OPENMETA_SUPPORT_MISSING', 'fallback'));
        self::assertFalse(Env::bool('OPENMETA_SUPPORT_MISSING', false));
        self::assertSame(7, Env::int('OPENMETA_SUPPORT_MISSING', 7));

        unset(
            $_ENV['OPENMETA_SUPPORT_TEST_STR'],
            $_ENV['OPENMETA_SUPPORT_TEST_BOOL'],
            $_ENV['OPENMETA_SUPPORT_TEST_INT']
        );
    }
}
