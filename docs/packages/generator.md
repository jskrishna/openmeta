---
title: Code Generator
description: Template-driven make:* scaffolding for fields, providers, commands, and more.
package: generator
category: packages
version: next
status: stable
last_updated: 2026-07-21
tags: [generator, package, scaffolding]
---

# Code Generator

Template-driven scaffolding: a stub engine, placeholder/namespace resolvers, 18
generator types, conflict detection, and dry-run/preview. It **never overwrites**
without `--force`, and mounts `make:<type>` commands into the [CLI](./cli.md).

Authoritative reference: [`packages/generator/README.md`](../../packages/generator/README.md)
· [`SPEC.md`](../../packages/generator/SPEC.md).

## Example

```bash
php bin/openmeta make:repository Post --dry-run
php bin/openmeta make:graphql-type Product
```

## Extension points

Register custom `GeneratorInterface`s and extra stub paths.

## Related

- [cli package](./cli.md) · [docgen package](./docgen.md)
