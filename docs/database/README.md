# Database docs

How OpenMeta stores models and field values.

## Foundation

| Doc | Topic |
| --- | ----- |
| [schema.md](./schema.md) | Data model & schema philosophy |
| [meta-storage.md](./meta-storage.md) | WordPress meta strategy |
| [custom-tables.md](./custom-tables.md) | Custom table strategy |
| [migrations.md](./migrations.md) | Database migrations |
| [indexing.md](./indexing.md) | Performance & indexes |

## Persistence layer

| Doc | Topic |
| --- | ----- |
| [repositories.md](./repositories.md) | Repository API over storage |
| [storage-drivers.md](./storage-drivers.md) | Meta / tables / hybrid drivers |
| [caching.md](./caching.md) | Request & object cache |
| [transactions.md](./transactions.md) | Atomic multi-write behavior |
| [relationships.md](./relationships.md) | Relationship field persistence |
| [multisite.md](./multisite.md) | Multisite behavior |

Decision record: [adr/ADR-0006-storage-strategy.md](../adr/ADR-0006-storage-strategy.md).
