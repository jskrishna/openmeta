# `@openmeta/graphql`

> **GraphQL abstraction layer** — a framework-independent way to describe an OpenMeta GraphQL surface: types, queries, mutations, resolvers, schema, introspection. **Not a GraphQL server** (no Apollo, no GraphiQL, no query engine).

**Status:** ✅ Phase 13 · **v0.12.0-beta**
**Blueprint:** [SPEC.md](./SPEC.md)

It exposes OpenMeta services through GraphQL contracts and contains **no business logic**. Authorization reuses `@openmeta/security`; input validation reuses `@openmeta/validation`. **No package depends on GraphQL.**

```bash
composer test:graphql
composer ci
```

## Public API

```text
GraphQLManager (façade)
  → schema() / sdl() / introspect()
  → executeQuery() / executeMutation()
  → types() / queries() / mutations()

SchemaManager · TypeRegistry · QueryRegistry · MutationRegistry
```

Everything else (resolvers, scalars, inputs, unions, interfaces, directives, executor, error handler) sits behind interfaces in [`src/Contracts`](./src/Contracts).

## Spine

```text
Types → Registries (Type/Scalar/Input/Interface/Union/Directive/Query/Mutation/Resolver)
  → Schema (build → validate → version) → Execution (authorize → validate → resolve → events)
  → Errors → Introspection / SDL → Manager
```

## What it is / isn't

- **Is:** a type-system model + registries + schema generator + introspection + a named-operation executor that wires in Security and Validation.
- **Isn't:** a spec-compliant GraphQL query engine, an HTTP transport, or a playground. Those live outside this package.

## Extensibility

Third parties implement `SchemaExtensionInterface` (or push into the registries) to add types, queries, mutations, scalars, directives, and resolvers — without touching framework code.

## Docs

See [docs/README.md](./docs/README.md).
