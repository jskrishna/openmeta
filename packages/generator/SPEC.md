# SPEC — `@openmeta/generator`

> **Implementation contract.** Implement against this document. [README](./README.md) is the short overview.

**Status:** ✅ Phase 15 · `v0.14.0-beta` (Code Generator — [ADR-0027](../../docs/adr/ADR-0027-dx-first-roadmap.md))

---

## Purpose

Provide scaffolding and code generation for OpenMeta developers: template-driven, convention-following boilerplate that is **production-ready** and **never overwrites user code without explicit confirmation**. It composes with the CLI (each generator becomes a `make:<key>` command) and holds no business logic.

Outer dev tool: depends only on **Core, Support, CLI**. **No runtime package may depend on it.**

## Component map

```text
Templates → Stubs → Resolvers → Registry → Files (conflict/dir/file) → Manager → Events → CLI bridge
```

## Templates
`TemplateEngine` (`TemplateEngineInterface`): `{{ placeholder }}`, `{{#if}}/{{#unless}}` blocks; unknown placeholders render empty.

## Stubs
`StubLoader` (`StubLoaderInterface`): loads `*.stub` from search paths; later paths override (extension point). Missing → `StubNotFoundException`.

## Resolvers
`PlaceholderResolver` (name → class/snake/kebab/plural/author/license/year, extras win) and `NamespaceResolver` (namespace/class + reserved detection).

## Registry
`GeneratorInterface` + `GeneratorRegistry` (`GeneratorRegistryInterface`); `GeneratorDefinition` (data) + `GeneratorDefinitions` (the 18 built-ins) + `GeneratorFactory` → `TemplateGenerator`. New types are data, not code.

## Files
`GeneratedFile`/`FileAction`; `FileGenerator` + `DirectoryGenerator` (write, create dirs); `ConflictDetector` (`ConflictDetectorInterface`) → reserved-namespace, naming-collision, existing-file (`Conflict`/`ConflictType`).

## Manager
`GeneratorManager` (`GeneratorManagerInterface`): resolve generator → produce files → apply `FileProcessorInterface`s → conflict-gate → dry-run/preview or write → emit events → `GenerationResult`. Existing files never overwritten unless `GenerationOptions::force`; reserved/naming conflicts always skip.

## Events
Reuse Core dispatcher: `GenerationStarted`, `FileGeneratedEvent`, `FileSkipped`, `GenerationCompleted`, `GenerationFailed`.

## CLI bridge
`GeneratorCommand` (`make:<key>`, extends CLI `Command`) + `GeneratorCommandProvider` (CLI `CommandProviderInterface`); `GeneratorServiceProvider` binds services, seeds the 18 generators, and mounts commands into the CLI registry when present.

## Public Contracts (package index)

`GeneratorManagerInterface` · `GeneratorRegistryInterface` · `TemplateEngineInterface` · `GeneratorInterface` · `StubLoaderInterface` · `PlaceholderResolverInterface` · `NamespaceResolverInterface` · `ConflictDetectorInterface` · `FileProcessorInterface`.

## Folder Structure

```text
packages/generator/
  resources/stubs/*.stub
  src/
    Configuration/  GeneratorConfiguration
    Contracts/      all package interfaces
    Events/         Generation*/File* events
    Exceptions/     GeneratorException + typed subclasses
    Files/          GeneratedFile, FileGenerator, DirectoryGenerator, ConflictDetector, enums
    Manager/        GeneratorManager, GenerationRequest/Options/Result, FileOutcome
    Registry/       GeneratorRegistry, GeneratorDefinition(s), TemplateGenerator, GeneratorFactory
    Resolvers/      PlaceholderResolver, NamespaceResolver
    Stubs/          StubLoader
    Support/        GeneratorCommand, GeneratorCommandProvider
    Templates/      TemplateEngine
    GeneratorServiceProvider.php
```

## Dependency Rules

May depend on **Core, Support, CLI** only. **No runtime package may depend on the generator.** Generated *output* may reference any package (stubs are text, not compiled here), so stub templates referencing GraphQL/Validation/CLI do not create a dependency.

## Extension Points

Custom `GeneratorInterface`; extra stub paths (`StubLoader::addPath`); `PlaceholderResolverInterface`; `FileProcessorInterface`.

## Testing Strategy

> Phase 16 gate:

| Layer | What to cover |
| ----- | ------------- |
| Unit | Template engine, placeholder resolver, stub loader, conflict detector, registry/defaults, file generator, manager (create/dry-run/skip/force/events) |
| Integration | Boot CLI + generator providers; `make:*` commands mount; manager previews without writing (`tests/Integration`) |
| WordPress Compatibility | **N/A** — a host-agnostic dev tool |
| Performance | O(files) per run; no dedicated budget |

Full matrix: [TESTING.md](../TESTING.md).

## Future Scope

Explicitly **not** built here: AI code generation, cloud templates, marketplace integration, IDE plugins.
