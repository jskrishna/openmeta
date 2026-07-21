<?php

declare(strict_types=1);

namespace OpenMeta\Validation\Tests\Integration;

use OpenMeta\Validation\Tests\ValidationTestCase;
use OpenMeta\Validation\Validation;

final class ValidationPipelineGateTest extends ValidationTestCase
{
    public function test_engine_to_error_bag(): void
    {
        $v = Validation::make(['email' => 'bad'], ['email' => 'required|email']);
        $this->assertTrue($v->fails());
        $this->assertNotEmpty($v->errors()->all());
    }
}
