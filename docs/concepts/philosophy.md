---
title: Framework Philosophy
description: The principles that shape every OpenMeta decision.
category: concepts
version: next
status: stable
last_updated: 2026-07-21
tags: [philosophy, architecture, principles]
---

# Framework Philosophy

OpenMeta is a **modern PHP content-modeling framework with a WordPress-first
adapter** — not "only a plugin". A handful of principles drive every decision.

## Architecture First

Every major capability is designed before it is implemented. Each package has a
`SPEC.md` (the implementation contract) written and accepted before code. This
is where most projects drift; OpenMeta treats the SPEC as law. See
[ARCHITECTURE.md](../../ARCHITECTURE.md) and [ADR-0002](../adr/ADR-0002-architecture-style.md).

## Documentation First

Public behavior is documented as it is built — code and docs ship together
([ADR-0021](../adr/ADR-0021-documentation-first.md)). The [documentation
standards](../README.md) make that sustainable.

## Package-based & host-independent

Domain packages know nothing about WordPress. The framework boots headless (CLI,
tests) and mounts onto WordPress at the edges. See
[Architecture](./architecture.md) and the
[packages guide](../packages/README.md).

## SOLID · DI · PSR

- Program to **interfaces**, inject collaborators via the
  [container](./dependency-injection.md).
- One reason to change per class; small, replaceable units.
- PSR-12 coding standard, enforced in CI.

## Strict dependency direction

A package may only depend on packages **above** it in the graph — never
downhill. This is the single most important rule; violating it is broken
architecture. See [Architecture › dependency direction](./architecture.md).

## Related

- [Architecture](./architecture.md) · [ADR index](../adr/README.md)
- [Contributing](../../CONTRIBUTING.md)

## Next steps

- [Architecture Overview](./architecture.md)
