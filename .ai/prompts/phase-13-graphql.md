# OpenMeta — Phase 13: GraphQL Package

You are a Principal API Architect and Senior PHP Framework Engineer contributing to the OpenMeta framework.

Before writing any code, you MUST read:

- README.md
- ARCHITECTURE.md
- docs/
- docs/adr/
- packages/*/README.md
- packages/graphql/README.md
- packages/graphql/SPEC.md

Documentation is the source of truth.

Never violate the documented architecture.

---

# Goal

Build ONLY the GraphQL package.

This package provides a framework-independent GraphQL abstraction layer.

It MUST NOT implement business logic.

It should expose OpenMeta services through GraphQL.

---

# Responsibilities

Implement:

- GraphQL Manager
- Schema Manager
- Type Registry
- Type Builder
- Query Registry
- Mutation Registry
- Resolver Registry
- Directive Registry
- Scalar Registry
- Input Registry
- Union Registry
- Interface Registry
- Subscription Contracts
- Authorization Integration
- Validation Integration
- Error Handler
- Schema Generator
- Introspection Support
- GraphQL Events

---

# Folder Structure

packages/graphql/

src/

Manager/
Schema/
Types/
Queries/
Mutations/
Resolvers/
Scalars/
Inputs/
Interfaces/
Unions/
Directives/
Authorization/
Validation/
Errors/
Events/
Contracts/
Support/

tests/
docs/

Respect the package structure.

---

# Development Rules

Follow:

- PSR-12
- SOLID
- DRY
- KISS
- Interfaces First
- Constructor Injection
- Composition over inheritance

---

# Dependency Rules

GraphQL may depend on:

- Core
- Support
- Validation
- Security
- Database
- Fields
- REST
- WordPress
- Admin
- Builder
- Extension SDK

GraphQL MUST NOT be required by lower packages.

---

# Schema

Implement:

- Schema Builder
- Schema Registry
- Schema Versioning
- Schema Validation

Support schema extensions.

---

# Types

Support:

- Object Types
- Interfaces
- Unions
- Scalars
- Enums
- Inputs

Third-party extensions should be able to register new types.

---

# Queries

Support:

- Query Registry
- Query Discovery
- Query Groups

No business queries.

---

# Mutations

Support:

- Mutation Registry
- Mutation Validation
- Mutation Discovery

No business mutations.

---

# Resolvers

Implement resolver contracts.

Resolvers should consume repositories and services.

Never access storage directly.

---

# Authorization

Reuse the Security package.

Do not duplicate permission logic.

---

# Validation

Reuse the Validation package.

Do not implement validation twice.

---

# Errors

Support:

- Validation Errors
- Authorization Errors
- Internal Errors
- Schema Errors

Provide consistent GraphQL error responses.

---

# Introspection

Support GraphQL introspection.

Allow extensions to contribute metadata.

---

# Events

Dispatch:

- Schema Built
- Query Executed
- Mutation Executed
- Resolver Invoked
- Error Raised

Reuse the Core Event Dispatcher.

---

# Extensibility

Allow third-party extensions to register:

- Types
- Queries
- Mutations
- Scalars
- Directives
- Resolvers

without modifying framework code.

---

# Public API

Expose only:

- GraphQL Manager
- Schema Manager
- Type Registry
- Query Registry
- Mutation Registry

Hide implementation details.

---

# Tests

Generate PHPUnit tests for:

- Schema
- Types
- Queries
- Mutations
- Resolvers
- Validation
- Authorization
- Events

Target high coverage.

---

# Quality

Run:

- PHPUnit
- PHPStan
- PHPCS

Resolve all issues.

---

# Constraints

Do NOT implement:

- Apollo Server
- UI Playground
- GraphiQL
- Business Queries
- Business Mutations

Keep the package framework-independent.

---

# Deliverables

Produce:

- Production-ready code
- PHPUnit tests
- PHPDoc
- Updated documentation
- Suggested commit message

Implement ONLY the GraphQL package.
