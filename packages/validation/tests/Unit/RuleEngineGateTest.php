<?php

declare(strict_types=1);

namespace OpenMeta\Validation\Tests\Unit;

use OpenMeta\Validation\Tests\ValidationTestCase;
use OpenMeta\Validation\Validation;

final class RuleEngineGateTest extends ValidationTestCase
{
    public function test_required_rule(): void
    {
        $v = Validation::make(['name' => ''], ['name' => 'required']);
        $this->assertTrue($v->fails());
    }
}
