# Custom Tables

---

# Purpose

Custom Tables provide a high-performance storage strategy for applications that exceed the capabilities of the native WordPress metadata tables.

While WordPress Meta Storage offers excellent compatibility, large-scale applications often require optimized schemas, efficient indexing, and complex relationships that are better served by dedicated database tables.

Custom Tables enable OpenMeta to support enterprise-scale workloads without compromising flexibility.

---

# Goals

The Custom Table strategy should provide:

- High performance
- Better query efficiency
- Scalable architecture
- Optimized indexing
- Complex relationships
- Storage abstraction
- Future extensibility
- WordPress compatibility

---

# Design Principles

The Custom Table strategy follows these principles:

- Storage remains abstract.
- Domain objects never depend on tables.
- Tables should be normalized where appropriate.
- Every table has a single responsibility.
- Indexes optimize common queries.
- Storage Drivers manage persistence.

---

# Why Custom Tables?

WordPress Meta tables work well for small datasets but become inefficient as data grows.

Common limitations include:

- Large meta tables
- Multiple JOIN operations
- Slow filtering
- Duplicate metadata
- Limited indexing
- Complex query performance

Custom Tables solve these problems through optimized schemas.

---

# Architecture

```text
Application

↓

Repository

↓

Custom Table Driver

↓

Database
```

The Domain Layer remains unaware of the storage implementation.

---

# Table Strategy

Instead of storing everything inside `wp_postmeta`, OpenMeta organizes data into dedicated tables.

Example:

```text
Schemas

↓

Field Groups

↓

Fields

↓

Validation Rules

↓

Location Rules
```

Each entity has its own storage structure.

---

# Example Table Structure

```text
openmeta_schemas

↓

openmeta_field_groups

↓

openmeta_fields

↓

openmeta_validation_rules

↓

openmeta_location_rules
```

Additional tables may be introduced as the framework evolves.

---

# Relationships

Typical relationships include:

```text
Schema

↓

Field Groups

↓

Fields
```

```text
Field

↓

Validation Rules
```

Relationships should be maintained through the Repository Layer.

---

# Primary Keys

Every table should include:

- Internal ID
- UUID
- Created At
- Updated At

Primary Keys should remain stable.

---

# Foreign Keys

Where supported, foreign keys should enforce relationship integrity.

Examples:

- Schema → Field Group
- Field Group → Field
- Field → Validation Rule

If foreign keys are unavailable, integrity should be maintained by the Repository Layer.

---

# Indexing

Indexes should be created for:

- UUID
- Slug
- Lookup fields
- Foreign keys
- Frequently queried columns

Composite indexes may be used where beneficial.

---

# Storage Driver

Custom Tables are accessed exclusively through the Custom Table Storage Driver.

Responsibilities include:

- CRUD operations
- Query execution
- Relationship mapping
- Transactions
- Data conversion

No component should query tables directly.

---

# Migration Support

All table changes should be managed through versioned migrations.

Examples:

- Create table
- Add column
- Modify index
- Rename column
- Drop table

Schema changes should be repeatable and version-controlled.

---

# Performance Considerations

Custom Tables should:

- Minimize JOIN operations.
- Reduce duplicate data.
- Support efficient indexing.
- Optimize read-heavy workloads.
- Scale to millions of records.

Database design should prioritize predictable query performance.

---

# WordPress Compatibility

Custom Tables should coexist with WordPress.

Requirements include:

- Respect WordPress lifecycle.
- Support Multisite.
- Use WordPress database abstraction where appropriate.
- Avoid modifying WordPress core tables.

The framework should remain installable as a standard WordPress plugin.

---

# Error Handling

If table operations fail:

- Roll back transactions.
- Log diagnostics.
- Return meaningful exceptions.
- Preserve data consistency.

Partial writes should never occur.

---

# Testing

Recommended tests include:

- CRUD operations.
- Relationship integrity.
- Migration execution.
- Index validation.
- Transaction handling.
- Performance benchmarks.

The Custom Table Driver should satisfy the same repository contracts as other storage implementations.

---

# Best Practices

- One responsibility per table.
- Use UUIDs for stable identifiers.
- Index frequently queried columns.
- Keep tables normalized.
- Access tables only through Storage Drivers.
- Version every schema change.
- Preserve backward compatibility.

---

# Future Considerations

Potential future enhancements include:

- PostgreSQL support.
- SQLite support.
- Partitioned tables.
- Read replicas.
- Distributed storage.
- Automatic table optimization.

These enhancements should preserve the existing Storage Driver contracts.

---

# Summary

The Custom Table strategy provides OpenMeta with a scalable and high-performance persistence model for enterprise applications.

By organizing metadata into dedicated tables and accessing them exclusively through Storage Drivers and Repositories, OpenMeta achieves efficient querying, improved scalability, and complete independence from WordPress's native metadata limitations while maintaining compatibility with the overall framework architecture.