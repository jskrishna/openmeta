<?php

declare(strict_types=1);

namespace OpenMeta\GraphQL\Tests\Unit;

use OpenMeta\GraphQL\Errors\ErrorCategory;
use OpenMeta\GraphQL\Events\QueryExecuted;
use OpenMeta\GraphQL\Events\ResolverInvoked;
use OpenMeta\GraphQL\Tests\GraphQLTestCase;
use OpenMeta\GraphQL\Types\FieldDefinition;
use OpenMeta\GraphQL\Types\TypeReference;

final class QueryExecutionTest extends GraphQLTestCase
{
    public function test_executes_query_and_emits_events(): void
    {
        $this->resolver('greet', static fn (mixed $root, array $args): string => 'Hello ' . ($args['name'] ?? 'world'));
        $this->query(new FieldDefinition('greeting', TypeReference::named('String'), resolver: 'greet'));

        /** @var list<object> $executed */
        $executed = [];
        /** @var list<object> $invoked */
        $invoked = [];
        $this->capture(QueryExecuted::class, $executed);
        $this->capture(ResolverInvoked::class, $invoked);

        $result = $this->graphql->executeQuery('greeting', ['name' => 'Ada']);

        self::assertFalse($result->hasErrors());
        self::assertSame('Hello Ada', $result->data);
        self::assertCount(1, $invoked);
        self::assertCount(1, $executed);
    }

    public function test_unknown_query_returns_schema_error(): void
    {
        $result = $this->graphql->executeQuery('missing');

        self::assertTrue($result->hasErrors());
        self::assertSame(ErrorCategory::Schema, $result->errors[0]->category);
        self::assertNull($result->data);
    }

    public function test_missing_resolver_is_an_error(): void
    {
        $this->query(new FieldDefinition('noResolver', TypeReference::named('String')));

        $result = $this->graphql->executeQuery('noResolver');

        self::assertTrue($result->hasErrors());
        self::assertSame(ErrorCategory::Schema, $result->errors[0]->category);
    }
}
