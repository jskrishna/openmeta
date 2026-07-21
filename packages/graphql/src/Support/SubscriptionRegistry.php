<?php

declare(strict_types=1);

namespace OpenMeta\GraphQL\Support;

use OpenMeta\GraphQL\Contracts\SubscriptionInterface;
use OpenMeta\GraphQL\Errors\TypeNotFoundException;

/**
 * Registry of subscription contracts, keyed by topic.
 *
 * Transport-agnostic: it stores the contracts only; delivery lives outside
 * this package.
 */
final class SubscriptionRegistry
{
    /** @var array<string, SubscriptionInterface> */
    private array $subscriptions = [];

    public function register(SubscriptionInterface $subscription): void
    {
        $this->subscriptions[$subscription->topic()] = $subscription;
    }

    public function has(string $topic): bool
    {
        return isset($this->subscriptions[$topic]);
    }

    public function get(string $topic): SubscriptionInterface
    {
        return $this->subscriptions[$topic] ?? throw TypeNotFoundException::named($topic);
    }

    /**
     * @return list<SubscriptionInterface>
     */
    public function all(): array
    {
        return array_values($this->subscriptions);
    }
}
