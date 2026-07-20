# Container/

**Role:** Dependency injection container.

## Planned responsibilities

- Bind interface → implementation
- Resolve services (singleton / transient as designed)
- Support test rebinding

## Non-responsibilities

- Service locator abuse from random domain classes
- Knowing about fields/storage

## Milestone

**v0.1 — in scope.**

See [docs/architecture/service-container.md](../../../../docs/architecture/service-container.md) and [dependency-injection.md](../../../../docs/architecture/dependency-injection.md).
