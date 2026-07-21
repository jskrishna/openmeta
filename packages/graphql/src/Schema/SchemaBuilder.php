<?php

declare(strict_types=1);

namespace OpenMeta\GraphQL\Schema;

use OpenMeta\GraphQL\Types\ObjectType;

/**
 * Assembles a {@see Schema} from the registries by synthesising the root
 * Query and Mutation object types from the registered root fields.
 */
final class SchemaBuilder
{
    public function build(SchemaRegistries $registries): Schema
    {
        $queryType = new ObjectType(
            'Query',
            $registries->queries->all(),
            'The root query type.',
        );

        $mutations = $registries->mutations->all();
        $mutationType = $mutations === []
            ? null
            : new ObjectType('Mutation', $mutations, 'The root mutation type.');

        return new Schema($queryType, $mutationType, $registries);
    }
}
