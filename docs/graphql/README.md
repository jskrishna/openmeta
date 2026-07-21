# GraphQL

OpenMeta ships a **framework-independent GraphQL abstraction layer** — types,
queries, mutations, resolvers, schema, and introspection — reusing Security for
authorization and Validation for input rules. It is **not** a GraphQL server.

Full reference: [`packages/graphql`](../../packages/graphql/README.md).

- [Example — Create a GraphQL Type](../examples/create-a-graphql-type.md)
- Public API: `GraphQLManager`, `SchemaManager`, `TypeRegistry`, `QueryRegistry`,
  `MutationRegistry`.
