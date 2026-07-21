---
title: WordPress
description: The WordPress adapter — plugin bootstrap, hooks, caps, REST mount, Gutenberg glue.
package: wordpress
category: packages
version: next
status: stable
last_updated: 2026-07-21
tags: [wordpress, package, adapter]
---

# WordPress

The outermost glue: plugin bootstrap and lifecycle, hooks/filters, capabilities,
mounting `rest` onto WP REST, and Gutenberg/admin slot glue. It bridges only —
no domain engines live here. Host calls are guarded with `function_exists()`, so
the framework also boots headless.

Authoritative reference: [`packages/wordpress/README.md`](../../packages/wordpress/README.md)
· [`SPEC.md`](../../packages/wordpress/SPEC.md).

## Related

- [WordPress integration overview](../wordpress/README.md)
- [ADR-0003](../adr/ADR-0003-wordpress-first.md) · [ADR-0009](../adr/ADR-0009-plugin-system.md)
