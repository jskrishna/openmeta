# Repository Pattern

---

## Purpose

The Repository Pattern abstracts data access from business logic.

Instead of allowing modules to communicate directly with WordPress APIs, custom database tables, or external storage systems, all data operations pass through repositories.

This creates a consistent data access layer that is easier to maintain, test, and extend.

---

# Problem

Without a Repository Pattern, business logic becomes tightly coupled to the storage mechanism.

Example:

```php
update_post_meta();

get_post_meta();

delete_post_meta();

$wpdb->insert();

$wpdb->update();
```

These calls become scattered across hundreds of files.

If OpenMeta later changes its storage engine, every file must be updated.

---

# Solution

Introduce repositories as the single source of truth for data access.

Instead of:

```text
Field Service

↓

WordPress Meta API
```

Use:

```text
Field Service

↓

Field Repository

↓

Storage Engine
```

Business logic no longer knows where data is stored.

---

# Why OpenMeta Uses It

OpenMeta is expected to support multiple storage mechanisms.

Examples:

- WordPress Post Meta
- Custom Database Tables
- User Meta
- Term Meta
- Options API
- Remote Storage
- Future Storage Drivers

Repositories isolate business logic from storage implementation.

---

# Responsibilities

A Repository is responsible for:

- Retrieving data
- Persisting data
- Updating records
- Deleting records
- Querying records

A Repository should never:

- Validate business rules
- Render UI
- Execute workflows
- Handle HTTP requests

---

# Repository Flow

```text
Application

↓

Service

↓

Repository

↓

Storage Driver

↓

Database
```

---

# Repository Types

Examples:

```text
FieldRepository

FieldGroupRepository

LocationRepository

ValidationRepository

SettingsRepository

AssetRepository

UserRepository
```

Each repository manages one aggregate or entity.

---

# Repository Interface

Every repository should expose a consistent contract.

Typical responsibilities include:

- find()
- findById()
- findAll()
- create()
- update()
- delete()
- exists()

Implementation details should remain hidden.

---

# Storage Independence

Repositories should not assume a specific storage backend.

Example:

```text
FieldRepository

↓

Storage Interface

↓

WordPress Meta
```

or

```text
FieldRepository

↓

Storage Interface

↓

Custom Tables
```

The repository API remains unchanged.

---

# Dependency Injection

Repositories receive dependencies through constructor injection.

Example:

```text
FieldRepository

↓

Database Connection

↓

Cache

↓

Logger
```

Repositories should never create database connections directly.

---

# Query Responsibility

Repositories are responsible for constructing queries.

Business services should not contain SQL or WordPress database logic.

Correct:

```text
FieldService

↓

FieldRepository

↓

Database
```

Incorrect:

```text
FieldService

↓

SQL Query
```

---

# Caching

Repositories may integrate with caching.

Flow:

```text
Repository

↓

Cache

↓

Database
```

Cache implementation should remain transparent to callers.

---

# Transactions

When supported by the storage engine, repositories should manage transactional operations for data consistency.

Business services should not manually manage transactions.

---

# Extensibility

Third-party storage drivers should integrate without changing repository consumers.

Example:

```text
Plugin

↓

Custom Storage Driver

↓

Repository

↓

Application
```

No business logic changes should be required.

---

# Error Handling

Repositories should:

- Throw descriptive exceptions.
- Log storage failures.
- Never return invalid data silently.

Storage-specific exceptions should not leak into higher layers.

---

# Performance

Repositories should:

- Avoid duplicate queries.
- Batch operations where possible.
- Use indexes effectively.
- Cache frequently accessed data.
- Avoid N+1 query patterns.

---

# Testing

Repositories should be tested independently.

Recommended tests:

- Create
- Read
- Update
- Delete
- Query filters
- Error scenarios
- Cache integration

---

# Advantages

- Centralized data access.
- Storage independence.
- Easier testing.
- Cleaner business logic.
- Improved maintainability.
- Future-proof architecture.

---

# Trade-offs

- Additional abstraction layer.
- More classes to maintain.
- Requires well-defined interfaces.

These trade-offs are justified for a framework like OpenMeta.

---

# Where to Use

Use repositories for:

- Field storage
- Field groups
- Settings
- Layouts
- Relationships
- Logs
- Metadata
- User preferences

---

# Where NOT to Use

Do not create repositories for:

- Simple helper functions.
- Value objects.
- Configuration constants.
- Stateless utilities.

Repositories should exist only for persistent data.

---

# Related Patterns

The Repository Pattern works closely with:

- Factory Pattern (creates repositories)
- Strategy Pattern (selects storage drivers)
- Service Container (resolves repositories)
- Specification Pattern (complex query criteria)

---

# Future Considerations

Possible future enhancements include:

- Read/Write repositories
- Query object support
- Pagination abstractions
- Async storage providers
- Distributed caching
- Multi-database support

These enhancements should preserve the repository contract.

---

# Summary

The Repository Pattern provides a consistent abstraction over data access in OpenMeta.

By separating persistence logic from business logic, OpenMeta remains storage-independent, easier to test, and simpler to evolve as new storage mechanisms are introduced.