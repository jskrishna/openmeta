---
title: Best Practices
description: Recommended patterns for building on and with OpenMeta.
category: concepts
version: next
status: stable
last_updated: 2026-07-21
tags: [best-practices]
---

# Best Practices

Recommendations that keep OpenMeta code maintainable and consistent.

## Architecture

- Respect the [dependency direction](../concepts/architecture.md) — never import
  a package below you. Update the `SPEC.md` before adding a dependency edge.
- Depend on **contracts**, not concretes; bind them in a
  [service provider](../concepts/service-providers.md).
- Keep WordPress at the edges; domain classes stay host-independent.

## Naming

- Follow PSR-12 and the existing package idioms. Match the surrounding code's
  naming, comment density, and structure.
- Classes are `StudlyCase`, methods `camelCase`, config keys `snake.case`.

## Testing

- Write unit + integration tests per package (see [testing/](../testing/README.md)).
- Never claim a change done with a red gate — `composer ci` must be green.

## Performance

- Lazy-load heavy modules; avoid boot-time work a request doesn't need.
- Avoid N+1 field/relation loads; use the database batch loaders.
- Benchmark hot paths (`composer bench`) before and after a change.

## Security

- Check capabilities via the [Security gate](../packages/security.md); reuse it,
  never re-implement permission logic.
- Validate input with the [Validation](../packages/validation.md) package; escape
  output; use nonces for state-changing requests.

## Dependency management

- Keep each package's `composer.json` accurate; the meta package aggregates.
- Guard public API with the backward-compatibility check (`composer bc:check`).

## Error handling & logging

- Throw typed exceptions extending the package base; never swallow errors
  silently.
- Log through the console logger at appropriate verbosity; keep logs actionable.

## Versioning & documentation

- Follow SemVer ([ADR-0019](../adr/ADR-0019-versioning.md)) and the
  [doc versioning strategy](../VERSIONING.md).
- Ship docs with code (Documentation First); run `php bin/openmeta docs:validate`.

## Related

- [Concepts](../concepts/README.md) · [Testing](../testing/README.md) · [Security](../security/README.md)
