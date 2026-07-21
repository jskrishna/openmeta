# `@openmeta/cli`

> **CLI & Developer Tools** — a framework-aware, platform-independent console for OpenMeta: command registry, discovery, input/output, prompts, tasks, and environment tooling over the Core container. Artisan/Symfony-Console-style DX.

**Status:** ✅ Phase 14 · **v0.13.0-beta**
**Blueprint:** [SPEC.md](./SPEC.md)

Reuses `@openmeta/validation` for command input validation. **No package depends on the CLI** — it is an outer developer tool. No GUI, no marketplace, no cloud, no IDE plugins.

```bash
composer test:cli
composer ci

# via the binary (installed as a dependency):
vendor/bin/openmeta list
vendor/bin/openmeta doctor
vendor/bin/openmeta make:command GreetCommand
```

## Public API

```text
ConsoleApplication (façade)   run(argv) → exit code
CommandRegistry               register / registerLazy / get
TaskRunner                    register / run
```

Everything else (input parser, output formatter, prompts, environment inspector, logger, stub generator) sits behind interfaces in [`src/Contracts`](./src/Contracts).

## Spine

```text
Input (parser + definitions) → Registry / Discovery → ConsoleApplication
  → Commands (base + built-ins) → Output (formatter/table/progress/json)
  → Prompts · Logging · Configuration · Environment · Tasks · Events
```

## Built-in commands

`list` · `help <command>` · `version` · `info [--json]` · `doctor` · `make:command <Name> [-p path]`

These are **representative infrastructure** — the package provides the scaffolding for `make:*`, `migrate:*`, `cache:*`, etc.; it does not hardcode business-specific commands.

## Extensibility

Third parties implement `CommandProviderInterface` (drained by `CommandDiscovery`) to register commands, `TaskInterface` for tasks, and `OutputFormatterInterface` styles — without modifying framework code.

## Docs

See [docs/README.md](./docs/README.md).
