<?php

declare(strict_types=1);

namespace OpenMeta\Validation\Results;

/**
 * Immutable attribute → structured validation failures.
 */
final class ErrorBag
{
    /** @param array<string, list<ValidationError>> $messages */
    private function __construct(private readonly array $messages = [])
    {
    }

    public static function empty(): self
    {
        return new self();
    }

    public function add(ValidationError $error): self
    {
        $messages = $this->messages;
        $messages[$error->attribute][] = $error;

        return new self($messages);
    }

    public function has(string $attribute = ''): bool
    {
        if ($attribute === '') {
            return $this->messages !== [];
        }

        return isset($this->messages[$attribute]) && $this->messages[$attribute] !== [];
    }

    public function first(?string $attribute = null): ?string
    {
        if ($attribute !== null) {
            $errors = $this->messages[$attribute] ?? [];

            return $errors === [] ? null : $errors[0]->message;
        }

        foreach ($this->messages as $errors) {
            if ($errors !== []) {
                return $errors[0]->message;
            }
        }

        return null;
    }

    /**
     * @return array<string, list<string>>
     */
    public function all(): array
    {
        $result = [];

        foreach ($this->messages as $attribute => $errors) {
            $result[$attribute] = array_map(
                static fn (ValidationError $error): string => $error->message,
                $errors
            );
        }

        return $result;
    }

    /**
     * @return list<ValidationError>
     */
    public function get(string $attribute): array
    {
        return $this->messages[$attribute] ?? [];
    }

    /**
     * @return array<string, list<ValidationError>>
     */
    public function errors(): array
    {
        return $this->messages;
    }

    public function merge(self $other): self
    {
        $messages = $this->messages;

        foreach ($other->errors() as $attribute => $errors) {
            foreach ($errors as $error) {
                $messages[$attribute][] = $error;
            }
        }

        return new self($messages);
    }

    public function isEmpty(): bool
    {
        return $this->messages === [];
    }

    public function count(): int
    {
        $count = 0;

        foreach ($this->messages as $errors) {
            $count += count($errors);
        }

        return $count;
    }
}
