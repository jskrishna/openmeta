<?php

declare(strict_types=1);

namespace OpenMeta\Cli\Input;

use OpenMeta\Cli\Exceptions\InvalidInputException;

/**
 * Binds raw argv tokens to a command's {@see InputDefinition}.
 *
 * Supports `--name`, `--name=value`, `--name value`, and `-s` shortcuts;
 * positional tokens fill declared arguments in order. Applies defaults and
 * enforces required arguments and known options.
 */
final class InputParser
{
    /**
     * @param list<string> $tokens Tokens after the command name
     *
     * @throws InvalidInputException
     */
    public function parse(array $tokens, InputDefinition $definition): Input
    {
        /** @var array<string, mixed> $options */
        $options = [];
        /** @var list<string> $positionals */
        $positionals = [];

        $count = count($tokens);

        for ($i = 0; $i < $count; $i++) {
            $token = $tokens[$i];

            if (str_starts_with($token, '--')) {
                $i = $this->parseLongOption($token, $tokens, $i, $definition, $options);

                continue;
            }

            if (str_starts_with($token, '-') && $token !== '-') {
                $i = $this->parseShortOption($token, $tokens, $i, $definition, $options);

                continue;
            }

            $positionals[] = $token;
        }

        return new Input(
            $this->bindArguments($positionals, $definition),
            $this->applyOptionDefaults($options, $definition),
        );
    }

    /**
     * @param list<string>         $tokens
     * @param array<string, mixed> $options
     */
    private function parseLongOption(
        string $token,
        array $tokens,
        int $index,
        InputDefinition $definition,
        array &$options,
    ): int {
        $body = substr($token, 2);
        $value = null;
        $hasInline = false;

        if (str_contains($body, '=')) {
            [$body, $value] = explode('=', $body, 2);
            $hasInline = true;
        }

        $option = $definition->option($body);

        if ($option === null) {
            throw InvalidInputException::unknownOption($body);
        }

        if (! $option->acceptsValue) {
            $options[$option->name] = true;

            return $index;
        }

        if ($hasInline) {
            $options[$option->name] = $value;

            return $index;
        }

        $next = $tokens[$index + 1] ?? null;

        if ($next === null || str_starts_with($next, '-')) {
            throw InvalidInputException::optionNeedsValue($option->name);
        }

        $options[$option->name] = $next;

        return $index + 1;
    }

    /**
     * @param list<string>         $tokens
     * @param array<string, mixed> $options
     */
    private function parseShortOption(
        string $token,
        array $tokens,
        int $index,
        InputDefinition $definition,
        array &$options,
    ): int {
        $shortcut = substr($token, 1);
        $option = $definition->optionByShortcut($shortcut);

        if ($option === null) {
            throw InvalidInputException::unknownOption($shortcut);
        }

        if (! $option->acceptsValue) {
            $options[$option->name] = true;

            return $index;
        }

        $next = $tokens[$index + 1] ?? null;

        if ($next === null || str_starts_with($next, '-')) {
            throw InvalidInputException::optionNeedsValue($option->name);
        }

        $options[$option->name] = $next;

        return $index + 1;
    }

    /**
     * @param list<string> $positionals
     *
     * @return array<string, mixed>
     */
    private function bindArguments(array $positionals, InputDefinition $definition): array
    {
        $arguments = [];

        foreach ($definition->arguments() as $offset => $argument) {
            if (array_key_exists($offset, $positionals)) {
                $arguments[$argument->name] = $positionals[$offset];

                continue;
            }

            if ($argument->required) {
                throw InvalidInputException::missingArgument($argument->name);
            }

            $arguments[$argument->name] = $argument->default;
        }

        return $arguments;
    }

    /**
     * @param array<string, mixed> $options
     *
     * @return array<string, mixed>
     */
    private function applyOptionDefaults(array $options, InputDefinition $definition): array
    {
        foreach ($definition->options() as $option) {
            if (! array_key_exists($option->name, $options)) {
                $options[$option->name] = $option->acceptsValue ? $option->default : false;
            }
        }

        return $options;
    }
}
