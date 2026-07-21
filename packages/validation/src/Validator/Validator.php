<?php

declare(strict_types=1);

namespace OpenMeta\Validation\Validator;

use OpenMeta\Support\Arr\Arr;
use OpenMeta\Validation\Contracts\RuleInterface;
use OpenMeta\Validation\Contracts\ValidatorInterface;
use OpenMeta\Validation\Exceptions\ValidationException;
use OpenMeta\Validation\Messages\MessageBag;
use OpenMeta\Validation\Registry\RuleEngine;
use OpenMeta\Validation\Results\ErrorBag;
use OpenMeta\Validation\Results\ValidationError;
use OpenMeta\Validation\Results\ValidationResult;

/**
 * Orchestrates Rule Engine runs and builds an Error Bag / Validation Result.
 */
final class Validator implements ValidatorInterface
{
    private ?ErrorBag $errors = null;

    private bool $validated = false;

    /**
     * @param array<string, mixed> $data
     * @param array<string, list<string|RuleInterface>|string|RuleInterface> $rules
     */
    public function __construct(
        private readonly array $data,
        private readonly array $rules,
        private readonly RuleEngine $engine,
        private readonly MessageBag $messages,
    ) {
    }

    public function passes(): bool
    {
        $this->run();

        return $this->errors !== null && $this->errors->isEmpty();
    }

    public function fails(): bool
    {
        return ! $this->passes();
    }

    public function errors(): ErrorBag
    {
        $this->run();

        return $this->errors ?? ErrorBag::empty();
    }

    public function result(): ValidationResult
    {
        $this->run();

        return new ValidationResult(
            $this->errors !== null && $this->errors->isEmpty(),
            $this->data,
            $this->errors ?? ErrorBag::empty(),
        );
    }

    public function validate(): array
    {
        if ($this->fails()) {
            throw new ValidationException($this->errors());
        }

        return $this->data;
    }

    private function run(): void
    {
        if ($this->validated) {
            return;
        }

        $this->validated = true;
        $this->errors = ErrorBag::empty();

        foreach ($this->rules as $attribute => $ruleSet) {
            $value = Arr::get($this->data, $attribute);
            $parsed = $this->engine->parse($ruleSet);
            $nullable = $this->isNullable($parsed);

            if ($nullable && ($value === null || $value === '')) {
                continue;
            }

            foreach ($parsed as $item) {
                $rule = $item['rule'];
                $parameters = $item['parameters'];

                if ($rule->name() === 'nullable') {
                    continue;
                }

                if ($this->engine->passes($rule, $attribute, $value, $parameters, $this->data)) {
                    continue;
                }

                $params = $this->messageParams($rule->name(), $parameters, $value);
                $message = $this->messages->make($attribute, $rule->name(), $rule->message(), $params);

                $this->errors = $this->errors->add(new ValidationError(
                    $attribute,
                    $rule->name(),
                    $message,
                    'validation.' . $rule->name(),
                    $params,
                ));

                // Short-circuit remaining rules for this attribute after first failure.
                break;
            }
        }
    }

    /**
     * @param list<array{rule: RuleInterface, parameters: list<string>}> $parsed
     */
    private function isNullable(array $parsed): bool
    {
        foreach ($parsed as $item) {
            if ($item['rule']->name() === 'nullable') {
                return true;
            }
        }

        return false;
    }

    /**
     * @param list<string> $parameters
     * @return array<string, string|int|float>
     */
    private function messageParams(string $rule, array $parameters, mixed $value): array
    {
        $params = [];

        if (is_bool($value)) {
            $params['value'] = $value ? '1' : '0';
        } elseif (is_string($value) || is_int($value) || is_float($value)) {
            $params['value'] = $value;
        } elseif ($value === null) {
            $params['value'] = '';
        }

        if (in_array($rule, ['min', 'between'], true) && isset($parameters[0])) {
            $params['min'] = $parameters[0];
        }

        if (in_array($rule, ['max', 'between'], true)) {
            if ($rule === 'between' && isset($parameters[1])) {
                $params['max'] = $parameters[1];
            } elseif (isset($parameters[0])) {
                $params['max'] = $parameters[0];
            }
        }

        if ($rule === 'length' && isset($parameters[0])) {
            $params['length'] = $parameters[0];
        }

        if (in_array($rule, ['in', 'not_in', 'starts_with', 'ends_with'], true)) {
            $params['values'] = implode(', ', $parameters);
        }

        if ($rule === 'contains' && isset($parameters[0])) {
            $params['contains'] = $parameters[0];
        }

        if ($rule === 'required_if' && count($parameters) >= 2) {
            $params['other'] = $parameters[0];
            $params['value'] = implode(',', array_slice($parameters, 1));
        }

        return $params;
    }
}
