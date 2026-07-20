# Design Patterns

> **Document:** Design Patterns  
> **Status:** Draft  
> **Version:** Pre-Alpha

---

## Purpose

This section defines the official design patterns used throughout OpenMeta.

Design patterns provide reusable architectural solutions to recurring engineering problems. Their consistent use improves maintainability, extensibility, testability, and long-term scalability.

OpenMeta intentionally adopts a limited set of well-understood patterns rather than applying patterns everywhere.

The goal is clarity, not complexity.

---

## Design philosophy

- Prefer simplicity over abstraction.
- Use a pattern only when it solves a real problem.
- Avoid unnecessary layers.
- Composition over inheritance.
- Interfaces over implementations.
- Keep modules loosely coupled.
- Optimize for maintainability.

A design pattern is a tool—not a requirement.

---

## Pattern selection criteria

A pattern should be introduced only when it:

- Solves a recurring problem.
- Reduces coupling.
- Improves extensibility.
- Simplifies testing.
- Makes the codebase easier to understand.

If a pattern increases complexity without clear benefits, it should not be used.

---

## Official design patterns

| Pattern | Doc | Primary usage |
| ------- | --- | ------------- |
| Factory | [factory.md](./factory.md) | Object creation |
| Strategy | [strategy.md](./strategy.md) | Runtime behavior selection |
| Repository | [repository.md](./repository.md) | Data access abstraction |
| Builder | [builder.md](./builder.md) | Complex object construction |
| Observer | [observer.md](./observer.md) | Events and hooks |
| Adapter | [adapter.md](./adapter.md) | Third-party integrations |
| Command | [command.md](./command.md) | Background tasks and actions |
| Decorator | [decorator.md](./decorator.md) | Extending behavior |
| Pipeline | [pipeline.md](./pipeline.md) | Sequential processing |
| Specification | [specification.md](./specification.md) | Query rules and validation |
| Composite | [composite.md](./composite.md) | Nested field structures |
| State | [state.md](./state.md) | Workflow state management |

---

## General rules

- Prefer composition.
- Avoid deep inheritance trees.
- Keep classes focused.
- Patterns should remain implementation details.
- Public APIs should not expose unnecessary complexity.

---

## Pattern documentation format

Each approved pattern doc includes:

- Purpose
- Problem it solves
- Why OpenMeta uses it
- Where to use it
- Where NOT to use it
- Advantages
- Trade-offs
- Example architecture
- Future considerations

---

## Future review

The list of approved patterns should be reviewed before each major release.

New patterns may be introduced only through an Architecture Decision Record (ADR).

Patterns should never be added simply because they are fashionable.

---

## Summary

OpenMeta uses design patterns to solve real architectural problems—not to increase abstraction.

Consistency is more valuable than using many patterns.
