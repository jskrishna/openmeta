<?php

declare(strict_types=1);

namespace OpenMeta\GraphQL\Contracts;

use OpenMeta\GraphQL\Resolvers\ResolutionContext;

/**
 * Subscription contract (transport-agnostic).
 *
 * The abstraction layer defines the contract only; a concrete transport
 * (WebSocket, SSE, …) lives outside this package.
 */
interface SubscriptionInterface
{
    /**
     * A stable topic name the transport subscribes to.
     */
    public function topic(): string;

    /**
     * Whether the current context may subscribe.
     *
     * @param array<string, mixed> $args
     */
    public function authorize(array $args, ResolutionContext $context): bool;
}
