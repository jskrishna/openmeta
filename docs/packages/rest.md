---
title: REST
description: Framework-independent HTTP — router, middleware, resources, auth contracts.
package: rest
category: packages
version: next
status: stable
last_updated: 2026-07-21
tags: [rest, package, http]
---

# REST

A framework-independent HTTP layer: router, request/response, middleware,
resources, auth contracts, and a Security gate authorizer. No WordPress mount
lives here — the adapter mounts it onto WP REST. The application route surface
is the [`api`](./api.md) package.

Authoritative reference: [`packages/rest/README.md`](../../packages/rest/README.md)
· [`SPEC.md`](../../packages/rest/SPEC.md).

## Related

- [api package](./api.md) · [wordpress package](./wordpress.md)
- [ADR-0007](../adr/ADR-0007-api-strategy.md)
