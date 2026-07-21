<?php

declare(strict_types=1);

namespace OpenMeta\Validation\Registry;

use OpenMeta\Validation\Contracts\RuleInterface;
use OpenMeta\Validation\Exceptions\InvalidRuleException;

/**
 * Parses and executes rule definitions against values.
 */
final class RuleEngine
{
    public function __construct(private readonly RuleRegistry $registry)
    {
    }

    public function registry(): RuleRegistry
    {
        return $this->registry;
    }

    /**
     * @param list<string|RuleInterface>|string|RuleInterface $rules
     * @return list<array{rule: RuleInterface, parameters: list<string>}>
     */
    public function parse(array|string|RuleInterface $rules): array
    {
        if ($rules instanceof RuleInterface) {
            return [['rule' => $rules, 'parameters' => []]];
        }

        if (is_string($rules)) {
            $rules = explode('|', $rules);
        }

        $parsed = [];

        foreach ($rules as $rule) {
            if ($rule instanceof RuleInterface) {
                $parsed[] = ['rule' => $rule, 'parameters' => []];
                continue;
            }

            if (! is_string($rule) || $rule === '') {
                throw new InvalidRuleException('Rules must be strings or RuleInterface instances.');
            }

            [$name, $parameters] = $this->parseStringRule($rule);
            $parsed[] = [
                'rule' => $this->registry->resolve($name),
                'parameters' => $parameters,
            ];
        }

        return $parsed;
    }

    /**
     * @param list<string> $parameters
     * @param array<string, mixed> $data
     */
    public function passes(
        RuleInterface $rule,
        string $attribute,
        mixed $value,
        array $parameters = [],
        array $data = []
    ): bool {
        return $rule->passes($attribute, $value, $parameters, $data);
    }

    /**
     * @return array{0: string, 1: list<string>}
     */
    private function parseStringRule(string $rule): array
    {
        if (! str_contains($rule, ':')) {
            return [$rule, []];
        }

        [$name, $paramString] = explode(':', $rule, 2);

        // Keep regex / date-time formats intact (may contain commas or colons).
        if (in_array($name, ['regex', 'date', 'datetime'], true)) {
            return [$name, $paramString === '' ? [] : [$paramString]];
        }

        return [$name, $paramString === '' ? [] : explode(',', $paramString)];
    }
}
