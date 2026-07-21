<?php

declare(strict_types=1);

namespace OpenMeta\Validation\Tests;

use OpenMeta\Validation\Validation;

abstract class ValidationTestCase extends \PHPUnit\Framework\TestCase
{
    protected function tearDown(): void
    {
        Validation::flush();
        parent::tearDown();
    }
}
