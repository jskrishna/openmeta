# Kernel/

**Role:** Orchestrate boot order after Bootstrap.

## Planned responsibilities

- Run provider `register()` then `boot()` in defined order
- Expose boot state
- Coordinate early lifecycle without domain features

## Non-responsibilities

- Implementing feature modules themselves
- HTTP routing

## Milestone

**v0.1 — in scope.**

See [docs/core/application-lifecycle.md](../../../../docs/core/application-lifecycle.md).
