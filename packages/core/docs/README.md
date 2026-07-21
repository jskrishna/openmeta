# Core package docs

What each part of `@openmeta/core` **does**. Implementation detail lives in code, not here.

Project-wide docs: repo `docs/core/`, `docs/architecture/`.

| Doc | Defines |
| --- | ------- |
| [architecture.md](./architecture.md) | Core’s role and boundaries |
| [lifecycle.md](./lifecycle.md) | Boot stages to “Framework Ready” |
| [container.md](./container.md) | Service container responsibility |
| [kernel.md](./kernel.md) | Provider orchestration |
| [service-providers.md](./service-providers.md) | Register / boot extension model |
| [configuration.md](./configuration.md) | Runtime configuration responsibility |
| [events.md](./events.md) | In-process event dispatch |
| [first-classes.md](./first-classes.md) | Canonical class inventory |
| [build-order.md](./build-order.md) | What to build after Core |
| [milestone-v0.1.0-alpha.md](./milestone-v0.1.0-alpha.md) | Current bootstrap milestone |

Package contract: [../README.md](../README.md) · Dependency rules: [../../README.md](../../README.md)

## Non-goals

These docs do not cover database, fields, REST, GraphQL, admin UI, builder, or WordPress screens.
