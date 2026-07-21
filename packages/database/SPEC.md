# SPEC — `@openmeta/database`

> **Implementation contract.** [README](./README.md) · [docs](./docs/README.md)

**Status:** ✅ Complete — Phase 6 / `v0.5.0-alpha`

**Role:** OpenMeta **Database Abstraction Layer (DAL)**. Not Eloquent. Not Doctrine. Not Active Record.

**ADR:** [ADR-0023](../../docs/adr/ADR-0023-database-dal-no-active-record.md)

---

## Purpose

Provide a reusable, driver-ready persistence spine so Fields, API, Admin, Builder, and WordPress glue never talk to storage engines directly.

---

## Architecture decision (no Active Record)

```text
Application
      ↓
Repository
      ↓
Query Builder
      ↓
Connection
      ↓
Driver
      ↓
Database Engine
```

Storage-agnostic higher packages; WordPress tables + custom tables share repository contracts; multiple drivers (wpdb, PDO, SQLite) without rewriting Fields/API.

---

## Folder Structure

```text
packages/database/
├── src/
│   ├── Collections/
│   ├── Configuration/
│   ├── Connections/
│   ├── Contracts/
│   ├── Drivers/
│   ├── Events/
│   ├── Exceptions/
│   ├── Metadata/
│   ├── Migrations/
│   ├── Pagination/
│   ├── Query/
│   ├── Relationships/
│   ├── Repositories/
│   ├── Schema/
│   ├── Support/
│   └── Transactions/
├── tests/
├── docs/
├── README.md
└── SPEC.md
```

---

## Public Contracts (index)

| Area | Types |
| ---- | ----- |
| Connection | `ConnectionInterface`, `ConnectionManager`, `ConnectionFactory`, `ConnectionRegistry`, `MemoryConnection`, `PdoConnection` |
| Drivers | `DriverInterface`, `MemoryDriver`, `TableStorage` |
| Query | `QueryBuilder` |
| Repositories | `RepositoryInterface`, `TableRepository` |
| Schema | `Schema`, `Blueprint` |
| Migrations | `MigrationInterface`, `Migration`, `Migrator` |
| Relationships | `RelationLoader`, `RelationType` |
| Transactions | `TransactionManager` |
| Pagination | `LengthAwarePaginator`, `CursorPaginator` (stub) |
| Collections | `ResultCollection` |
| Configuration | `DatabaseConfig` |
| Metadata | `TableDefinition`, `ColumnDefinition`, `SchemaInspector` |
| Events | ConnectionOpened, QueryExecuted, Migration*, Transaction* |
| Exceptions | Database, Query, Connection, InvalidDriver, Migration, Schema, Transaction |
| Provider | `DatabaseServiceProvider` |

---

## Dependency Rules

| Direction | Rule |
| --------- | ---- |
| Required | `core`, `support`, `ext-pdo` |
| Optional | validation, security (not required for current spine) |
| Forbidden | `fields`, `api`, `admin`, `builder`, `wordpress`, WordPress `$wpdb` APIs |
| Consumers | `fields`, `api`, `wordpress` (future wpdb driver) |

---

## Must not

- Eloquent / Doctrine / Active Record clones  
- WordPress `$wpdb` integration inside this package  
- Field / REST / GraphQL / Admin persistence logic  
- Concatenate untrusted SQL  

---

## Testing Strategy

Unit + Integration on MemoryConnection; PDO when available. Phase 12 layers present.

See [packages/TESTING.md](../../TESTING.md).

---

## Future Scope

- WordPress `$wpdb` driver in `@openmeta/wordpress`  
- Full cursor pagination  
- Query/schema grammars per driver  
- Read replicas  

Never: field UI or HTTP inside Database.
