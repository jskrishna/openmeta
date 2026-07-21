<?php

declare(strict_types=1);

namespace OpenMeta\Rest\Transformers;

use OpenMeta\Rest\Contracts\TransformerInterface;
use OpenMeta\Rest\Exceptions\RestException;

/**
 * Named transformer registry.
 */
final class TransformerRegistry
{
    /** @var array<string, TransformerInterface> */
    private array $transformers = [];

    public function register(string $name, TransformerInterface $transformer): void
    {
        $this->transformers[$name] = $transformer;
    }

    public function get(string $name): TransformerInterface
    {
        if (! isset($this->transformers[$name])) {
            throw new RestException(sprintf('Transformer [%s] is not registered.', $name), 500, 'server_error');
        }

        return $this->transformers[$name];
    }

    public function has(string $name): bool
    {
        return isset($this->transformers[$name]);
    }
}
