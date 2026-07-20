# Bootstrap/

**Role:** Bridge from WordPress plugin load into the OpenMeta kernel.

## Planned responsibilities

- Plugin main-file / early load hook
- PHP / WP requirement checks
- Create container, load config, hand off to `Kernel`
- Fail gracefully when requirements are unmet

## Non-responsibilities

- Registering field types or REST routes
- Heavy business logic in the plugin entry file

## Milestone

**v0.1 (`0.1.0-alpha`) — shipped.** Kernel bootstrap spine is implemented.

See [docs/architecture/plugin-bootstrap.md](../../../../docs/architecture/plugin-bootstrap.md).
