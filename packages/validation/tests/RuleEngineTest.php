<?php

declare(strict_types=1);

namespace OpenMeta\Validation\Tests;

use OpenMeta\Validation\Registry\RuleEngine;
use OpenMeta\Validation\Registry\RuleRegistry;
use OpenMeta\Validation\Exceptions\InvalidRuleException;
use OpenMeta\Validation\Rules\RequiredRule;

final class RuleEngineTest extends ValidationTestCase
{
    public function test_parses_pipe_and_parameter_rules(): void
    {
        $registry = new RuleRegistry();
        $registry->registerDefaults();
        $engine = new RuleEngine($registry);

        $parsed = $engine->parse('required|min:3|in:a,b');
        self::assertCount(3, $parsed);
        self::assertSame('required', $parsed[0]['rule']->name());
        self::assertSame(['3'], $parsed[1]['parameters']);
        self::assertSame(['a', 'b'], $parsed[2]['parameters']);

        $parsedObject = $engine->parse(new RequiredRule());
        self::assertSame('required', $parsedObject[0]['rule']->name());
    }

    public function test_unknown_rule_throws(): void
    {
        $registry = new RuleRegistry();
        $engine = new RuleEngine($registry);

        $this->expectException(InvalidRuleException::class);
        $engine->parse('nope');
    }
}
