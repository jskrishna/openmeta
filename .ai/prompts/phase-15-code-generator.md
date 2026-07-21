# OpenMeta — Phase 15: Code Generator & Scaffolding

You are a Principal Framework Architect and Developer Experience (DX) Engineer contributing to the OpenMeta framework.

Before writing any code, you MUST read:

- README.md
- ARCHITECTURE.md
- docs/
- docs/adr/
- packages/*/README.md
- packages/generator/README.md
- packages/generator/SPEC.md

Documentation is the source of truth.

Never violate documented architecture.

---

# Goal

Build ONLY the Code Generator package.

This package provides scaffolding and code generation capabilities for OpenMeta developers.

It should generate production-ready boilerplate based on templates and project conventions.

It must never overwrite user code without explicit confirmation.

---

# Responsibilities

Implement:

- Generator Manager
- Template Engine
- Stub Loader
- Placeholder Resolver
- Namespace Resolver
- File Generator
- Directory Generator
- Conflict Detector
- Generator Registry
- Generator Events
- Stub Variables
- Generator Configuration

---

# Folder Structure

packages/generator/

src/

Manager/
Templates/
Stubs/
Resolvers/
Files/
Registry/
Configuration/
Events/
Contracts/
Exceptions/
Support/

resources/
    stubs/

tests/
docs/

Respect the package structure.

---

# Development Rules

Follow: PSR-12, SOLID, DRY, KISS, Dependency Injection, Interfaces First.

---

# Dependency Rules

Generator may depend on: Core, Support, CLI.

Generator MUST NOT be required by runtime packages.

---

# Generator Types

Support generators for: Field, Field Group, Repository, Migration, Service Provider, Event, Listener, Command, Controller, Middleware, Validation Rule, GraphQL Type, GraphQL Resolver, REST Resource, Admin Page, Builder Component, Extension, Package.

The architecture should allow additional generators without modifying existing code.

---

# Stub Engine

Support: Placeholder Replacement, Conditional Blocks, Namespaces, Imports, File Headers, License Headers.

---

# File Generation

Support: Create Files, Create Directories, Merge Existing Files, Dry Run, Preview Changes.

Never overwrite files automatically.

---

# Conflict Handling

Implement: Existing File Detection, Naming Collision Detection, Reserved Namespace Detection.

---

# Events

Dispatch: Generation Started, File Generated, File Skipped, Generation Completed, Generation Failed. Reuse the Core Event Dispatcher.

---

# Extensibility

Allow third-party packages to register: Generators, Stub Templates, Placeholder Resolvers, File Processors — without modifying framework code.

---

# Public API

Expose only: Generator Manager, Generator Registry, Template Engine. Hide implementation details.

---

# Tests

Generate PHPUnit tests for: Stub Engine, Placeholder Resolver, File Generator, Conflict Detection, Registry, Events. Target high coverage.

---

# Quality

Run: PHPUnit, PHPStan, PHPCS. Resolve all issues.

---

# Constraints

Do NOT implement: AI Code Generation, Cloud Templates, Marketplace Integration, IDE Plugins.

---

# Deliverables

Produce: Production-ready code, PHPUnit tests, PHPDoc, Updated documentation, Suggested commit message.

Implement ONLY the Generator package.
