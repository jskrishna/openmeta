# OpenMeta GraphQL — developer guide

`@openmeta/graphql` lets you describe a GraphQL surface over OpenMeta services
and execute named operations. It is an **abstraction layer**, not a server —
see [../SPEC.md](../SPEC.md) for the contract.

## 1. Register types

```php
use OpenMeta\GraphQL\Types\TypeBuilder;
use OpenMeta\GraphQL\Types\TypeReference;

$registries->types->register(
    TypeBuilder::object('Post')
        ->field('id', TypeReference::named('ID')->nonNull())
        ->field('title', TypeReference::named('String'))
        ->build()
);
```

## 2. Register a resolver + query

Resolvers consume repositories/services (never storage directly):

```php
use OpenMeta\GraphQL\Resolvers\CallableResolver;
use OpenMeta\GraphQL\Types\FieldDefinition;
use OpenMeta\GraphQL\Types\TypeReference;

$resolvers->register('posts', new CallableResolver(
    fn ($root, array $args, $ctx) => $postRepository->all()  // injected service
));

$graphql->queries()->register(new FieldDefinition(
    'posts',
    TypeReference::named('Post')->listOf(true),
    resolver: 'posts',
    permission: 'read_posts',           // enforced via @openmeta/security
));
```

## 3. Mutations validate their arguments

```php
$graphql->mutations()->register(new FieldDefinition(
    'createPost',
    TypeReference::named('Post'),
    resolver: 'createPost',
    rules: ['title' => ['required', 'string']],  // enforced via @openmeta/validation
));
```

## 4. Execute

```php
$result = $graphql->executeQuery('posts', [], $context);
$response = $result->toArray();   // { data: ..., errors?: [ { message, extensions: { category } } ] }
```

The executor: authorizes → validates arguments → invokes the resolver →
emits `QueryExecuted` / `MutationExecuted` / `ResolverInvoked` / `ErrorRaised`.

## 5. Introspect / print SDL

```php
$sdl    = $graphql->sdl();        // "schema { query: Query } ... type Post { ... }"
$schema = $graphql->introspect(); // ['__schema' => ['queryType' => ['name' => 'Query'], 'types' => [...]]]
```

## 6. Extend from a third-party package

```php
use OpenMeta\GraphQL\Contracts\SchemaExtensionInterface;
use OpenMeta\GraphQL\Schema\SchemaRegistries;

final class SeoGraphQL implements SchemaExtensionInterface
{
    public function extend(SchemaRegistries $r): void
    {
        $r->scalars->register(new ScalarType('Seo'));
        $r->queries->register(new FieldDefinition('seo', TypeReference::named('Seo'), resolver: 'seo'));
    }
}

$schemaManager->extend(new SeoGraphQL());
```

## Not included

No Apollo/server runtime, no GraphiQL playground, no query-document parser, no
subscription transport, and no business queries/mutations — those live in host
adapters or future packages.
