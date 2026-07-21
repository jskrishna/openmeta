# Example — Create a GraphQL Type

tags: graphql, example

Scaffold and register an object type on the GraphQL abstraction layer.

## Scaffold

```bash
php bin/openmeta make:graphql-type Product
```

## Register a type + query

```php
use OpenMeta\GraphQL\Contracts\GraphQLManagerInterface;
use OpenMeta\GraphQL\Resolvers\CallableResolver;
use OpenMeta\GraphQL\Resolvers\ResolverRegistry;
use OpenMeta\GraphQL\Types\FieldDefinition;
use OpenMeta\GraphQL\Types\ObjectType;
use OpenMeta\GraphQL\Types\TypeBuilder;
use OpenMeta\GraphQL\Types\TypeReference;

/** @var GraphQLManagerInterface $graphql */
$graphql = $app->get(GraphQLManagerInterface::class);
/** @var ResolverRegistry $resolvers */
$resolvers = $app->get(ResolverRegistry::class);

$graphql->types()->register(
    TypeBuilder::object('Product')
        ->field('id', TypeReference::named('ID')->nonNull())
        ->field('name', TypeReference::named('String'))
        ->build()
);

$resolvers->register('products', new CallableResolver(fn () => $repository->all()));
$graphql->queries()->register(
    new FieldDefinition('products', TypeReference::named('Product')->listOf(true), resolver: 'products')
);
```

Full reference: [graphql package](../../packages/graphql/README.md).
