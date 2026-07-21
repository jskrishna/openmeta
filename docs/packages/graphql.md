---
title: GraphQL
description: A framework-independent GraphQL abstraction layer.
package: graphql
category: packages
version: next
status: stable
last_updated: 2026-07-21
tags: [graphql, package]
---

# GraphQL

A **framework-independent GraphQL abstraction layer** — types, queries,
mutations, resolvers, schema, and introspection — reusing Security for
authorization and Validation for input rules. Not a GraphQL server.

Authoritative reference: [`packages/graphql/README.md`](../../packages/graphql/README.md)
· [`SPEC.md`](../../packages/graphql/SPEC.md).

## Example

See [Create a GraphQL Type](../examples/create-a-graphql-type.md). Scaffold one
with `php bin/openmeta make:graphql-type Product`.

## Extension points

Implement `SchemaExtensionInterface` to add types/queries/mutations/scalars.

## Related

- [security package](./security.md) · [validation package](./validation.md)
- [ADR-0007](../adr/ADR-0007-api-strategy.md)
