---
title: CLI
description: The framework-aware console — commands, output, prompts, tasks.
package: cli
category: packages
version: next
status: stable
last_updated: 2026-07-21
tags: [cli, package]
---

# CLI

The `openmeta` console over the Core container: command registry & discovery,
input parsing, output formatting, prompts, tasks, an environment inspector, a
logger, and events. Third parties add commands via `CommandProviderInterface`.

Authoritative reference: [`packages/cli/README.md`](../../packages/cli/README.md)
· [`SPEC.md`](../../packages/cli/SPEC.md).

## Example

```bash
php bin/openmeta list
php bin/openmeta doctor
php bin/openmeta make:field Star
```

## Related

- [CLI overview](../cli/README.md) · [generator package](./generator.md)
