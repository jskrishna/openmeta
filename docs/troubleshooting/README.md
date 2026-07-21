---
title: Troubleshooting
description: Solutions to common OpenMeta problems.
category: concepts
version: next
status: stable
last_updated: 2026-07-21
tags: [troubleshooting]
---

# Troubleshooting

Common issues and fixes. If something here is wrong or missing, please
[contribute](../CONTRIBUTING.md).

## Installation & autoload

- **Class not found / autoload errors** — run `composer dump-autoload`. In the
  monorepo, ensure the package is listed in the root `composer.json` PSR-4 map.
- **PHP version** — OpenMeta requires **8.3+**; check `php -v` (or run
  `php bin/openmeta doctor`).

## Configuration

- **Missing app key** — the Security package needs `app.key`; supply it at boot
  (see [Configuration](../concepts/configuration.md)).

## Migrations & database

- **Migration errors** — verify the connection config and that the driver is
  registered; the memory driver is available out of the box.

## REST & GraphQL

- **Route/command not found** — confirm the relevant service provider is
  registered (the [`framework`](../packages/framework.md) meta package registers
  all of them).
- **GraphQL "Invalid schema"** — every referenced type must be registered and
  the Query type must have at least one field.

## Extensions & CLI

- **Extension not activating** — check compatibility (core/PHP version, required
  dependencies) via the SDK's `Environment`.
- **`docs:*` / `make:*` missing** — run through `php bin/openmeta` (the repo
  console boots the full framework), not the CLI-package binary alone.

## Diagnostics

```bash
php bin/openmeta doctor   # environment checks
php bin/openmeta info     # runtime + package status
```

## Related

- [FAQ](../faq/README.md) · [Getting Started](../getting-started/README.md)
