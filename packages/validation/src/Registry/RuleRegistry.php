<?php

declare(strict_types=1);

namespace OpenMeta\Validation\Registry;

use Closure;
use OpenMeta\Validation\Contracts\RuleInterface;
use OpenMeta\Validation\Exceptions\InvalidRuleException;
use OpenMeta\Validation\Rules\ArrayRule;
use OpenMeta\Validation\Rules\BetweenRule;
use OpenMeta\Validation\Rules\BooleanRule;
use OpenMeta\Validation\Rules\ClosureRule;
use OpenMeta\Validation\Rules\ContainsRule;
use OpenMeta\Validation\Rules\DateRule;
use OpenMeta\Validation\Rules\DateTimeRule;
use OpenMeta\Validation\Rules\EmailRule;
use OpenMeta\Validation\Rules\EndsWithRule;
use OpenMeta\Validation\Rules\EnumRule;
use OpenMeta\Validation\Rules\FloatRule;
use OpenMeta\Validation\Rules\InRule;
use OpenMeta\Validation\Rules\IntegerRule;
use OpenMeta\Validation\Rules\LengthRule;
use OpenMeta\Validation\Rules\MaxRule;
use OpenMeta\Validation\Rules\MinRule;
use OpenMeta\Validation\Rules\NotInRule;
use OpenMeta\Validation\Rules\NullableRule;
use OpenMeta\Validation\Rules\NumericRule;
use OpenMeta\Validation\Rules\ObjectRule;
use OpenMeta\Validation\Rules\RegexRule;
use OpenMeta\Validation\Rules\RequiredIfRule;
use OpenMeta\Validation\Rules\RequiredRule;
use OpenMeta\Validation\Rules\StartsWithRule;
use OpenMeta\Validation\Rules\StringRule;
use OpenMeta\Validation\Rules\UrlRule;
use OpenMeta\Validation\Rules\UuidRule;

/**
 * Resolves rule ids to {@see RuleInterface} instances. Custom rules via {@see extend()}.
 */
final class RuleRegistry
{
    /** @var array<string, RuleInterface|class-string<RuleInterface>|Closure> */
    private array $rules = [];

    public function registerDefaults(): void
    {
        $this->extend('required', RequiredRule::class);
        $this->extend('required_if', RequiredIfRule::class);
        $this->extend('nullable', NullableRule::class);
        $this->extend('string', StringRule::class);
        $this->extend('integer', IntegerRule::class);
        $this->extend('int', IntegerRule::class);
        $this->extend('numeric', NumericRule::class);
        $this->extend('float', FloatRule::class);
        $this->extend('boolean', BooleanRule::class);
        $this->extend('bool', BooleanRule::class);
        $this->extend('array', ArrayRule::class);
        $this->extend('object', ObjectRule::class);
        $this->extend('email', EmailRule::class);
        $this->extend('url', UrlRule::class);
        $this->extend('uuid', UuidRule::class);
        $this->extend('date', DateRule::class);
        $this->extend('datetime', DateTimeRule::class);
        $this->extend('enum', EnumRule::class);
        $this->extend('min', MinRule::class);
        $this->extend('max', MaxRule::class);
        $this->extend('between', BetweenRule::class);
        $this->extend('length', LengthRule::class);
        $this->extend('in', InRule::class);
        $this->extend('not_in', NotInRule::class);
        $this->extend('regex', RegexRule::class);
        $this->extend('starts_with', StartsWithRule::class);
        $this->extend('ends_with', EndsWithRule::class);
        $this->extend('contains', ContainsRule::class);
    }

    /**
     * @param RuleInterface|class-string<RuleInterface>|Closure $rule
     *        Closure: (string, mixed, list<string>, array<string, mixed>): bool
     */
    public function extend(string $name, RuleInterface|string|Closure $rule, ?string $message = null): void
    {
        if ($rule instanceof Closure) {
            $this->rules[$name] = new ClosureRule($name, $rule, $message ?? 'The :attribute field is invalid.');

            return;
        }

        $this->rules[$name] = $rule;
    }

    public function has(string $name): bool
    {
        return isset($this->rules[$name]);
    }

    /**
     * @return list<string>
     */
    public function names(): array
    {
        return array_keys($this->rules);
    }

    public function resolve(string $name): RuleInterface
    {
        if (! isset($this->rules[$name])) {
            throw new InvalidRuleException(sprintf('Validation rule [%s] is not registered.', $name));
        }

        $rule = $this->rules[$name];

        if ($rule instanceof RuleInterface) {
            return $rule;
        }

        if (is_string($rule) && is_a($rule, RuleInterface::class, true)) {
            $instance = new $rule();
            $this->rules[$name] = $instance;

            return $instance;
        }

        throw new InvalidRuleException(sprintf('Validation rule [%s] is not resolvable.', $name));
    }
}
