---
title: Database
description: The Database Abstraction Layer — connections, schema, migrations, repositories.
package: database
category: packages
version: next
status: stable
last_updated: 2026-07-21
tags: [database, package, dal]
---

# Database

A **Database Abstraction Layer (DAL)** — no Active Record
([ADR-0023](../adr/ADR-0023-database-dal-no-active-record.md)). Connections and
driver contracts, query builder, schema, migrations, repositories, relationship
batch loaders, transactions, and pagination.

Authoritative reference: [`packages/database/README.md`](../../packages/database/README.md)
· [`SPEC.md`](../../packages/database/SPEC.md).

## Shape

```text
Application → Repository → Query Builder → Connection → Driver → Engine
```

Domain code depends on repositories, never on raw SQL or WordPress meta details.

## Related

- [fields package](./fields.md)
- [ADR-0006](../adr/ADR-0006-storage-strategy.md)
