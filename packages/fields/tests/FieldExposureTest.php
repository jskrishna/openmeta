<?php

declare(strict_types=1);

namespace OpenMeta\Fields\Tests;

use OpenMeta\Fields\GraphQL\FieldGraphQLTypeMap;
use OpenMeta\Fields\Rest\FieldRestSerializer;

final class FieldExposureTest extends FieldsTestCase
{
    public function test_rest_and_graphql_contracts(): void
    {
        $field = $this->fields->make('text', 'title', ['label' => 'Title']);
        $rest = (new FieldRestSerializer())->serialize($field, 'Hello');
        self::assertSame('text', $rest['type']);
        self::assertSame('Hello', $rest['value']);

        $gql = (new FieldGraphQLTypeMap())->map($field);
        self::assertSame('String', $gql['graphql_type']);
        self::assertTrue($gql['nullable']);
    }
}
