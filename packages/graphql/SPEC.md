# SPEC — `@openmeta/graphql`

> **Implementation contract.** Implement against this document. [README](./README.md) is the short overview.

**Status:** ✅ Phase 13 · `v0.12.0-beta` (GraphQL abstraction layer — [ADR-0007](../../docs/adr/ADR-0007-api-strategy.md))

---

## Purpose

Provide a **framework-independent GraphQL abstraction layer** that exposes OpenMeta services through GraphQL contracts. It is **not a GraphQL server** — no Apollo, no GraphiQL, no query-document engine — and contains **no business logic** (no business queries/mutations). It is API-first ([ADR-0007](../../docs/adr/ADR-0007-api-strategy.md)) and reuses Security and Validation rather than re-implementing them.

The package is an outer API layer: it may depend on Core→Extensions; **no lower package may depend on it**.

## Component map

```text
Types → Registries → Schema (build/validate/version) → Execution → Errors → Introspection/SDL → Manager
```

## Types
### Responsibilities
Immutable, engine-agnostic type model: `TypeReference` (list/non-null wrappers), `ObjectType`, `InterfaceType`, `UnionType`, `EnumType`/`EnumValue`, `ScalarType`, `InputType`/`InputField`, `FieldDefinition`, `ArgumentDefinition`, `DirectiveDefinition`, and a fluent `TypeBuilder`.
### Must not
Bind to any GraphQL runtime library or execute anything.

## Registries
### Responsibilities
`TypeRegistry` (objects + enums), `ScalarRegistry` (+ 5 built-ins), `InputRegistry`, `InterfaceRegistry`, `UnionRegistry`, `DirectiveRegistry` (+ spec directives), `QueryRegistry` (groups + discovery), `MutationRegistry` (discovery), `ResolverRegistry`. Duplicate registration throws; unknown lookups throw.
### Public contracts
`TypeRegistryInterface`, `QueryRegistryInterface`, `MutationRegistryInterface`, `ResolverRegistryInterface`.

## Schema
### Responsibilities
`SchemaBuilder` synthesises root `Query`/`Mutation` object types from registered root fields; `SchemaValidator` checks every referenced type resolves, union members exist, and interfaces are satisfied; `SchemaManager` builds/caches/versions and dispatches `SchemaBuilt`; `SchemaRegistry`/`SchemaVersion` store versions; `SchemaRegistries` is the shared registry aggregate handed to extensions.
### Public contracts
`SchemaManagerInterface`, `SchemaExtensionInterface`.

## Resolvers
### Responsibilities
`ResolverInterface::resolve($root, $args, ResolutionContext)`; `CallableResolver` and `PropertyResolver` helpers; `ResolutionContext` carries the viewer + attributes.
### Must not
Access storage directly — resolvers consume repositories/services injected via the container.

## Authorization
### Responsibilities
`FieldAuthorizer` enforces a field's optional `permission` through the Security `GateInterface`. Reuses Security; holds no permission logic.

## Validation
### Responsibilities
`RuleInputValidator` validates operation arguments through the Validation package (`Validation::make`). Returns a `ValidationOutcome`. Reuses Validation; never re-implements it.

## Errors
### Responsibilities
`ErrorCategory` (validation/authorization/schema/internal); `GraphQLError` response DTO; exception hierarchy (`GraphQLException`, `GraphQLAuthorizationException`, `GraphQLValidationException`, `SchemaException`, `TypeNotFoundException`, `DuplicateTypeException`); `ErrorHandler` maps any `Throwable` to a consistent `GraphQLError` (internal details never leak).

## Execution
### Responsibilities
`OperationExecutor` runs one named root operation: look up field → authorize → validate args → invoke resolver → emit events → return `ExecutionResult`. A dispatcher, not a query engine.

## Events
Reuse Core's dispatcher: `SchemaBuilt`, `QueryExecuted`, `MutationExecuted`, `ResolverInvoked`, `ErrorRaised`.

## Introspection / SDL
`SchemaPrinter` emits SDL; `IntrospectionGenerator` emits a `__schema` document (pragmatic subset).

## Subscriptions
`SubscriptionInterface` + `SubscriptionRegistry` — contracts only, transport-agnostic.

## Public Contracts (package index)

`GraphQLManagerInterface` · `SchemaManagerInterface` · `TypeRegistryInterface` · `QueryRegistryInterface` · `MutationRegistryInterface` · `ResolverInterface` / `ResolverRegistryInterface` · `FieldAuthorizerInterface` · `InputValidatorInterface` · `ErrorHandlerInterface` · `SchemaExtensionInterface` · `SubscriptionInterface`.

## Folder Structure

```text
packages/graphql/src/
  Authorization/  FieldAuthorizer
  Contracts/      all package interfaces
  Directives/     DirectiveDefinition, DirectiveRegistry
  Errors/         ErrorCategory, GraphQLError, ErrorHandler, exceptions
  Events/         Schema/Query/Mutation/Resolver/Error events
  Inputs/         InputType, InputField, InputRegistry
  Interfaces/     InterfaceType, InterfaceRegistry
  Manager/        GraphQLManager (façade), ExecutionResult
  Mutations/      MutationRegistry
  Queries/        QueryRegistry
  Resolvers/      ResolutionContext, CallableResolver, PropertyResolver, ResolverRegistry
  Scalars/        ScalarType, ScalarRegistry
  Schema/         Schema, SchemaRegistries, SchemaBuilder, SchemaValidator, SchemaManager, SchemaRegistry, SchemaVersion
  Support/        OperationExecutor, SchemaPrinter, IntrospectionGenerator, SubscriptionRegistry
  Types/          type model + TypeRegistry + TypeBuilder
  Unions/         UnionType, UnionRegistry
  Validation/     RuleInputValidator, ValidationOutcome
  GraphQLServiceProvider.php
```

## Dependency Rules

May depend on Core, Support, Validation, Security, Database, Fields, REST, WordPress, Admin, Builder, Extension SDK. **No lower package may depend on GraphQL.** In practice this implementation binds only to **Core** (container/events/provider), **Security** (`GateInterface`), and **Validation** (`Validation`) — the two mandated reuse points — keeping coupling minimal.

## Extension Points

Implement `SchemaExtensionInterface` to contribute types/queries/mutations/scalars/directives/resolvers; register resolvers in `ResolverRegistry`; add scalars/directives to their registries. No framework code changes required.

## Performance

Registries and schema assembly are O(n) over registered elements; schema build result is cached until an extension invalidates it. Execution is a single lookup + resolver call — no document parsing.

## Security

Field/operation authorization is delegated to the Security gate; internal exceptions are masked as generic internal errors so details never leak into responses.

## Testing Strategy

> Phase 16 gate:

| Layer | What to cover |
| ----- | ------------- |
| Unit | Type references, registries, schema build/validate/version, execution, authorization, mutation validation, events, SDL/introspection, resolvers |
| Integration | Boot the framework with `GraphQLServiceProvider` + Security + Validation; register and execute an operation through the wired container (`tests/Integration`) |
| WordPress Compatibility | **N/A** — the layer is host-agnostic; any WP HTTP transport lives in `@openmeta/wordpress` |
| Performance | Assembly/execution are linear; no dedicated budget at this size |

Full matrix: [TESTING.md](../TESTING.md).

## Future Scope

Explicitly **not** built here: Apollo/server runtime, GraphiQL/playground UI, a spec query-document parser/executor, subscription transport, and any business queries/mutations. Those belong to future packages or host adapters.
