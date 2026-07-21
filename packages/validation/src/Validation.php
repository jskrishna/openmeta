<?php

declare(strict_types=1);

namespace OpenMeta\Validation;

use OpenMeta\Validation\Contracts\RuleInterface;
use OpenMeta\Validation\Context\ValidationContext;
use OpenMeta\Validation\Messages\MessageBag;
use OpenMeta\Validation\Registry\RuleEngine;
use OpenMeta\Validation\Registry\RuleRegistry;
use OpenMeta\Validation\Support\DataNormalizer;
use OpenMeta\Validation\Validator\Validator;

/**
 * Package façade — start a validation run against arrays or objects.
 */
final class Validation
{
    private static ?RuleRegistry $registry = null;

    private static ?RuleEngine $engine = null;

    public static function registry(): RuleRegistry
    {
        if (self::$registry === null) {
            self::$registry = new RuleRegistry();
            self::$registry->registerDefaults();
        }

        return self::$registry;
    }

    public static function engine(): RuleEngine
    {
        if (self::$engine === null) {
            self::$engine = new RuleEngine(self::registry());
        }

        return self::$engine;
    }

    /**
     * Register a custom rule (string id → RuleInterface, class-string, or Closure).
     *
     * @param RuleInterface|class-string<RuleInterface>|\Closure $rule
     *        Closure: (string, mixed, list<string>, array<string, mixed>): bool
     */
    public static function extend(string $name, RuleInterface|string|\Closure $rule, ?string $message = null): void
    {
        self::registry()->extend($name, $rule, $message);
    }

    /**
     * @param array<string, mixed>|object $data
     * @param array<string, list<string|RuleInterface>|string|RuleInterface> $rules
     * @param array<string, string> $messages
     */
    public static function make(array|object $data, array $rules, array $messages = []): Validator
    {
        return new Validator(
            DataNormalizer::normalize($data),
            $rules,
            self::engine(),
            new MessageBag($messages),
        );
    }

    public static function fromContext(ValidationContext $context): Validator
    {
        return self::make($context->data, $context->rules, $context->messages);
    }

    /**
     * @param array<string, mixed>|object $data
     * @param array<string, list<string|RuleInterface>|string|RuleInterface> $rules
     * @param array<string, string> $messages
     * @return array<string, mixed>
     */
    public static function validate(array|object $data, array $rules, array $messages = []): array
    {
        return self::make($data, $rules, $messages)->validate();
    }

    public static function flush(): void
    {
        self::$registry = null;
        self::$engine = null;
    }

    /** Wire façade to container-managed registry (used by {@see ValidationServiceProvider}). */
    public static function useRegistry(RuleRegistry $registry): void
    {
        self::$registry = $registry;
        self::$engine = new RuleEngine($registry);
    }
}
