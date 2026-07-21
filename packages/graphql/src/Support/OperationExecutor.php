<?php

declare(strict_types=1);

namespace OpenMeta\GraphQL\Support;

use OpenMeta\Core\Contracts\EventDispatcherInterface;
use OpenMeta\GraphQL\Contracts\ErrorHandlerInterface;
use OpenMeta\GraphQL\Contracts\FieldAuthorizerInterface;
use OpenMeta\GraphQL\Contracts\InputValidatorInterface;
use OpenMeta\GraphQL\Errors\GraphQLValidationException;
use OpenMeta\GraphQL\Errors\SchemaException;
use OpenMeta\GraphQL\Events\ErrorRaised;
use OpenMeta\GraphQL\Events\MutationExecuted;
use OpenMeta\GraphQL\Events\QueryExecuted;
use OpenMeta\GraphQL\Events\ResolverInvoked;
use OpenMeta\GraphQL\Manager\ExecutionResult;
use OpenMeta\GraphQL\Resolvers\ResolutionContext;
use OpenMeta\GraphQL\Schema\SchemaRegistries;
use OpenMeta\GraphQL\Types\FieldDefinition;
use Throwable;

/**
 * Executes a single named root operation: look up the field, authorize,
 * validate arguments, invoke the resolver, and emit lifecycle events.
 *
 * This is deliberately a named-operation dispatcher, not a GraphQL query
 * engine — the package is an abstraction layer, not a server.
 */
final class OperationExecutor
{
    public function __construct(
        private readonly SchemaRegistries $registries,
        private readonly FieldAuthorizerInterface $authorizer,
        private readonly InputValidatorInterface $validator,
        private readonly ErrorHandlerInterface $errorHandler,
        private readonly EventDispatcherInterface $events,
    ) {
    }

    /**
     * @param array<string, mixed> $args
     */
    public function executeQuery(string $name, array $args = [], ?ResolutionContext $context = null): ExecutionResult
    {
        return $this->execute($name, $args, $context, false);
    }

    /**
     * @param array<string, mixed> $args
     */
    public function executeMutation(string $name, array $args = [], ?ResolutionContext $context = null): ExecutionResult
    {
        return $this->execute($name, $args, $context, true);
    }

    /**
     * @param array<string, mixed> $args
     */
    private function execute(string $name, array $args, ?ResolutionContext $context, bool $isMutation): ExecutionResult
    {
        $context ??= new ResolutionContext();

        try {
            $field = $isMutation
                ? $this->registries->mutations->get($name)
                : $this->registries->queries->get($name);

            $this->authorizer->authorize($field->permission, $context);
            $this->validateArguments($field, $args);

            $data = $this->invokeResolver($field, $args, $context);

            $this->dispatchExecuted($isMutation, $name, $args, true);

            return new ExecutionResult($data);
        } catch (Throwable $exception) {
            $error = $this->errorHandler->handle($exception, [$name]);
            $this->events->dispatch(new ErrorRaised($error));
            $this->dispatchExecuted($isMutation, $name, $args, false);

            return new ExecutionResult(null, [$error]);
        }
    }

    /**
     * @param array<string, mixed> $args
     */
    private function validateArguments(FieldDefinition $field, array $args): void
    {
        if ($field->rules === []) {
            return;
        }

        $outcome = $this->validator->validate($args, $field->rules);

        if ($outcome->failed()) {
            throw new GraphQLValidationException(
                sprintf('Validation failed for [%s].', $field->name),
                $outcome->errors,
            );
        }
    }

    /**
     * @param array<string, mixed> $args
     */
    private function invokeResolver(FieldDefinition $field, array $args, ResolutionContext $context): mixed
    {
        if ($field->resolver === null) {
            throw new SchemaException(sprintf('No resolver bound for field [%s].', $field->name));
        }

        $resolver = $this->registries->resolvers->get($field->resolver);
        $this->events->dispatch(new ResolverInvoked($field->name, $field->resolver));

        return $resolver->resolve(null, $args, $context);
    }

    /**
     * @param array<string, mixed> $args
     */
    private function dispatchExecuted(bool $isMutation, string $name, array $args, bool $succeeded): void
    {
        $this->events->dispatch(
            $isMutation
                ? new MutationExecuted($name, $args, $succeeded)
                : new QueryExecuted($name, $args, $succeeded),
        );
    }
}
