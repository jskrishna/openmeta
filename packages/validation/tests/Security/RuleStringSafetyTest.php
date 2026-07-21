<?php

declare(strict_types=1);

namespace OpenMeta\Validation\Tests\Security;

use OpenMeta\Validation\Exceptions\InvalidRuleException;
use OpenMeta\Validation\Tests\ValidationTestCase;
use OpenMeta\Validation\Validation;

final class RuleStringSafetyTest extends ValidationTestCase
{
    public function test_unknown_rule_does_not_execute_arbitrary_code(): void
    {
        $this->expectException(InvalidRuleException::class);
        Validation::make(['x' => '1'], ['x' => 'not_a_real_rule'])->passes();
    }
}
