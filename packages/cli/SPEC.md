# SPEC — `@openmeta/cli`

> **Implementation contract.** Implement against this document. [README](./README.md) is the short overview.

**Status:** ✅ Phase 14 · `v0.13.0-beta` (CLI & Developer Tools — [ADR-0027](../../docs/adr/ADR-0027-dx-first-roadmap.md))

---

## Purpose

Provide the primary developer interface for OpenMeta: a **framework-aware, platform-independent** console that simplifies development, scaffolding, diagnostics, and maintenance — the DX pillar mirroring Laravel Artisan and Symfony Console. It reuses Validation for input validation and holds **no project-specific business commands**.

Outer developer tool: it may depend on Core→GraphQL; **no package may depend on it**. In practice it binds only to **Core**, **Support**, and **Validation**.

## Component map

```text
Input → Registry → Discovery → Application → Commands → Output
  → Prompts → Configuration → Environment → Logging → Tasks → Events
```

## Application
### Responsibilities
`ConsoleApplication` boot flow: argv → command lookup (registry) → input parse (bound to the command's definition) → `CommandStarted` → execute → `CommandFinished`/`CommandFailed`. Maps outcomes to exit codes (`SUCCESS`/`FAILURE`/`INVALID`). Lazy command resolution via the registry.
### Public contracts
`ConsoleApplicationInterface`.

## Input
### Responsibilities
`ArgumentDefinition`, `OptionDefinition`, `InputDefinition`; `InputParser` binds argv (`--opt`, `--opt=v`, `--opt v`, `-s`, positionals) to a definition, applying defaults and enforcing required arguments / known options; `Input` is the bound result.
### Public contracts
`InputInterface`.

## Registry / Discovery
### Responsibilities
`CommandRegistry` (eager + lazy factories, sorted names, descriptions without forcing resolution); `CommandDiscovery` drains `CommandProviderInterface`s — the extension point for third-party commands.
### Public contracts
`CommandRegistryInterface`, `CommandProviderInterface`.

## Commands
### Responsibilities
`Command` base (name/description, overridable `definition()`, Validation-backed `validateInput()`). Representative built-ins: `list`, `help`, `version`, `info`, `doctor`, `make:command`.
### Must not
Hardcode project-specific/business commands; provide infrastructure + representative examples only.
### Public contracts
`CommandInterface`.

## Output
### Responsibilities
`OutputInterface` with leveled messages (success/warning/error/info/comment), tables, JSON, and verbosity; `OutputFormatter` (extensible ANSI styles), `ConsoleOutput` (stream), `BufferedOutput` (capture), `TableRenderer`, `ProgressBar`.
### Public contracts
`OutputInterface`, `OutputFormatterInterface`.

## Prompts
### Responsibilities
`Prompt` (`ask`/`confirm`/`choice`/`secret`) reading a stream-backed input (STDIN by default; scriptable in tests).
### Public contracts
`PromptInterface`.

## Configuration / Environment
### Responsibilities
`ConfigurationLoader`/`Configuration` (array + JSON, dot-path); `EnvironmentInspector` (PHP version, extensions, file permissions, package status) + `EnvironmentCheck` diagnostics powering `doctor`/`info`.

## Logging / Tasks / Events
### Responsibilities
`ConsoleLogger` (verbosity-gated levels); `TaskRunner` + `CallableTask` (build/test/lint/docs/release infra); events `CommandStarted`/`CommandFinished`/`CommandFailed` via the Core dispatcher.
### Public contracts
`LoggerInterface`, `TaskInterface`, `TaskRunnerInterface`.

## Public Contracts (package index)

`ConsoleApplicationInterface` · `CommandInterface` · `CommandRegistryInterface` · `CommandProviderInterface` · `InputInterface` · `OutputInterface` / `OutputFormatterInterface` · `PromptInterface` · `LoggerInterface` · `TaskInterface` / `TaskRunnerInterface`.

## Folder Structure

```text
packages/cli/
  bin/openmeta            executable entry point
  src/
    Application/          ConsoleApplication
    Commands/             Command base + built-ins
    Configuration/        Configuration, ConfigurationLoader
    Contracts/            all package interfaces
    Discovery/            CommandDiscovery
    Environment/          EnvironmentInspector, EnvironmentCheck
    Events/               CommandStarted/Finished/Failed
    Exceptions/           CliException + typed subclasses
    Input/                definitions, InputParser, Input
    Logging/              ConsoleLogger
    Output/               Output, ConsoleOutput, BufferedOutput, OutputFormatter, TableRenderer, ProgressBar, Verbosity
    Prompts/              Prompt
    Registry/             CommandRegistry
    Support/              ExitCode, ConsoleErrorHandler, StubGenerator
    Tasks/                TaskRunner, CallableTask
    CliServiceProvider.php
```

## Dependency Rules

May depend on Core, Support, Validation, Security, Database, Fields, REST, WordPress, Admin, Builder, Extension SDK, GraphQL. **No package may depend on the CLI.** This implementation binds only to **Core** (container/events/provider/application), **Support** (`Arr`, `FilesystemInterface`), and **Validation** (`Validation`).

## Extension Points

`CommandProviderInterface` (commands), `TaskInterface` (tasks), `OutputFormatterInterface` styles. Commands, tasks, and formatters register without framework changes.

## Performance

Lazy command registration avoids constructing every command per invocation; parsing and dispatch are O(tokens)/O(1). No I/O outside the invoked command.

## Security

The CLI runs in a trusted developer context; `make:*` writes through the injected `FilesystemInterface` and refuses to overwrite existing files.

## Testing Strategy

> Phase 16 gate:

| Layer | What to cover |
| ----- | ------------- |
| Unit | Input parsing, command registry (incl. lazy), console application + exit codes/events, output/formatter/table/json, prompts, task runner, environment inspector, make:command stub generation |
| Integration | Boot the framework with `CliServiceProvider`; assert built-in commands registered; run a command through the wired registry via a buffered output (`tests/Integration`) |
| WordPress Compatibility | **N/A** — the console is host-agnostic (WP-optional); any WP-specific command lives behind the adapter |
| Performance | Parsing/dispatch are linear; no dedicated budget at this size |

Full matrix: [TESTING.md](../TESTING.md).

## Future Scope

Explicitly **not** built here: GUI, marketplace, cloud features, IDE plugins, and the full library of business commands. Those belong to future packages/host adapters.
