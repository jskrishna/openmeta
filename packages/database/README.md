# `@openmeta/database`

> OpenMeta **Database Abstraction Layer (DAL)** — not Eloquent, not Doctrine, **not Active Record**.  
> Layering: Application → Repository → Query Builder → Connection → Driver → Engine.

**Status:** ✅ Complete (Phase 6) · **v0.5.0-alpha**  
**Blueprint:** [SPEC.md](./SPEC.md) · ADR: [ADR-0023](../../docs/adr/ADR-0023-database-dal-no-active-record.md) · Docs: [docs/README.md](./docs/README.md)

---

## Purpose

Connection management, query builder, repositories, schema/migrations, relationships, transactions, pagination, and metadata — **without** Active Record, `$wpdb` leakage, or field/HTTP concerns.

---

## Layout

```text
Collections/ Configuration/ Connections/ Contracts/ Drivers/
Events/ Exceptions/ Metadata/ Migrations/ Pagination/ Query/
Relationships/ Repositories/ Schema/ Support/ Transactions/
```

---

## Public APIs

| Area | Types |
| ---- | ----- |
| Connections | `ConnectionInterface`, `ConnectionManager`, `MemoryConnection`, `PdoConnection` |
| Query | `QueryBuilder` (select/insert/update/delete, where*, join, aggregates, paginate) |
| Repositories | `RepositoryInterface`, `TableRepository` |
| Schema / Migrations | `Schema`, `Blueprint`, `Migration`, `Migrator` |
| Relationships | `RelationLoader`, `RelationType` |
| Transactions | `TransactionManager` |
| Pagination | `LengthAwarePaginator`, `CursorPaginator` (stub) |
| Drivers | `DriverInterface`, `MemoryDriver`, `TableStorage` |

Default CI driver: **memory**. PDO sqlite/mysql when configured. WordPress `$wpdb` adapter belongs in `@openmeta/wordpress` later.

---

## Verify

```bash
composer test:database
composer ci
```
