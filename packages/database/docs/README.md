# Database package docs

`@openmeta/database` is OpenMeta’s **DAL** — a reusable abstraction over storage.

Contract: [../SPEC.md](../SPEC.md) · Overview: [../README.md](../README.md) · ADR: `docs/adr/ADR-0006-storage-strategy.md`

## Design principles

1. **Not an ORM / no Active Record** — see [ADR-0023](../../../docs/adr/ADR-0023-database-dal-no-active-record.md)
2. **Layered DAL:** Application → Repository → Query Builder → Connection → Driver → Engine
3. **Driver-ready** — memory + PDO now; WordPress/MySQL/Postgres adapters via `DriverInterface`
4. **Consumers use repositories + query builder** — never raw engine handles from Fields/API
5. **No WordPress `$wpdb` in this package** — glue belongs in `@openmeta/wordpress`

## Spine

```text
ConnectionManager → ConnectionInterface
        ↓
QueryBuilder / Schema / Migrator / TransactionManager
        ↓
TableRepository / RelationLoader
```

## Non-goals

Field persistence UI, REST/GraphQL persistence, wpdb integration, full cursor pagination (stub only).
