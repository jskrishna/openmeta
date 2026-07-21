# OpenMeta — Phase 06: Database Package

Principal / Staff PHP — build ONLY `@openmeta/database` (DAL, not ORM).

Read first: README, ARCHITECTURE, docs/adr (esp. ADR-0006), packages/*/SPEC for core→security, then `packages/database/SPEC.md`.

## Goal

Reusable Database Abstraction Layer. Independent from WordPress, Fields, REST, Admin, Builder.

Do NOT clone Eloquent/Doctrine. Do NOT implement `$wpdb` here.

## Expected src layout

Collections, Configuration, Connections, Contracts, Drivers, Events, Exceptions,
Metadata, Migrations, Pagination, Query, Relationships, Repositories, Schema,
Support, Transactions + tests/ + docs/

## Quality

```bash
php composer.phar test:database
php composer.phar phpstan
php composer.phar phpcs
```

Follow `.cursor/rules/phase-workflow.mdc`. Commit only when asked.
