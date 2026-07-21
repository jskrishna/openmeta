<?php

declare(strict_types=1);

namespace OpenMeta\GraphQL\Tests\Unit;

use OpenMeta\GraphQL\Errors\ErrorCategory;
use OpenMeta\GraphQL\Tests\GraphQLTestCase;
use OpenMeta\GraphQL\Types\FieldDefinition;
use OpenMeta\GraphQL\Types\TypeReference;

final class AuthorizationTest extends GraphQLTestCase
{
    public function test_denied_permission_yields_authorization_error(): void
    {
        $this->resolver('secret', static fn (): string => 'top-secret');
        $this->query(new FieldDefinition(
            'secret',
            TypeReference::named('String'),
            resolver: 'secret',
            permission: 'view_secret',
        ));

        $result = $this->graphql->executeQuery('secret');

        self::assertTrue($result->hasErrors());
        self::assertSame(ErrorCategory::Authorization, $result->errors[0]->category);
        self::assertNull($result->data);
    }

    public function test_granted_permission_allows_execution(): void
    {
        $this->gate->grant('view_secret');
        $this->resolver('secret', static fn (): string => 'top-secret');
        $this->query(new FieldDefinition(
            'secret',
            TypeReference::named('String'),
            resolver: 'secret',
            permission: 'view_secret',
        ));

        $result = $this->graphql->executeQuery('secret');

        self::assertFalse($result->hasErrors());
        self::assertSame('top-secret', $result->data);
    }
}
