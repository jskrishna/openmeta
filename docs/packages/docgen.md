---
title: Documentation Platform
description: Generate and validate docs — API docs, search index, sitemap, changelog.
package: docgen
category: packages
version: next
status: stable
last_updated: 2026-07-21
tags: [docgen, package, documentation]
---

# Documentation Platform

Generates the API reference (reflection + PHPDoc), package index, search index,
sitemap, and changelog, and **validates** the docs tree (links, markdown, code
blocks). Mounts `docs:*` commands into the [CLI](./cli.md).

Authoritative reference: [`packages/docgen/README.md`](../../packages/docgen/README.md)
· [`SPEC.md`](../../packages/docgen/SPEC.md).

## Example

```bash
php bin/openmeta docs:build
php bin/openmeta docs:validate
```

## Related

- [Documentation standards](../README.md) · [cli package](./cli.md)
