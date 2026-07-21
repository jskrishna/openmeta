# `@openmeta/generator`

> **Code generator & scaffolding.** Template-driven `make:*` generators that produce production-ready boilerplate from stubs and project conventions — and **never overwrite user code without `--force`**.

**Status:** ✅ Phase 15 · **v0.14.0-beta**
**Blueprint:** [SPEC.md](./SPEC.md)

Depends only on **Core, Support, CLI**. It generalizes the CLI's tiny `make:command` into a full generator and mounts a `make:<type>` command for every generator back into the console — scaffolding and CLI **compose**, not duplicate. **No runtime package depends on the generator.**

```bash
composer test:generator
composer ci

# through the console (CLI + generator providers booted):
openmeta make:field Star
openmeta make:repository Post --dry-run
openmeta make:graphql-type Product --force
```

## Public API

```text
GeneratorManager (façade)   run(request) → GenerationResult
GeneratorRegistry            register / get / keys
TemplateEngine               render(stub, variables)
```

Everything else (stub loader, resolvers, file/conflict handling) sits behind interfaces in [`src/Contracts`](./src/Contracts).

## Generator types (18, data-driven)

`field` · `field-group` · `repository` · `migration` · `provider` · `event` · `listener` · `command` · `controller` · `middleware` · `rule` · `graphql-type` · `graphql-resolver` · `rest-resource` · `admin-page` · `builder-component` · `extension` · `package`

Each is a [`GeneratorDefinition`](./src/Registry/GeneratorDefinitions.php) + a stub in [`resources/stubs`](./resources/stubs) — **add a type without touching code**.

## Safety

- **Never overwrites** an existing file unless `force` is set.
- **Reserved-namespace** (`OpenMeta\…`) and **naming-collision** (already-declared class) conflicts always skip.
- **Dry-run / preview** compute contents without writing a byte.

## Stub engine

`{{ placeholders }}`, `{{#if flag}}…{{/if}}`, `{{#unless flag}}…{{/unless}}`, namespaces, imports, and license headers.

## Extensibility

Register custom `GeneratorInterface`s, extra stub paths (`StubLoader::addPath`), placeholder resolvers, and `FileProcessorInterface`s — without modifying framework code.

## Docs

See [docs/README.md](./docs/README.md).
