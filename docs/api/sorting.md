# Sorting

---

# Purpose

Sorting defines the order in which resources are returned from repositories and APIs.

The sorting system should provide predictable ordering while remaining independent of the underlying storage implementation.

---

# Sorting Flow

```text
Client

↓

Sort Parameters

↓

Repository

↓

Storage Driver

↓

Ordered Results
```

---

# Supported Sort Orders

OpenMeta supports:

- Ascending
- Descending
- Multiple Fields
- Nested Fields

Repositories determine how sorting is translated into storage operations.

---

# Multi-field Sorting

Resources may be sorted using multiple properties.

Example order:

```text
Published Date

↓

Title

↓

Identifier
```

This produces deterministic result ordering.

---

# Default Sorting

Every resource collection should define a sensible default sort order.

Clients may override this when permitted.

---

# Validation

Sorting parameters should be validated before execution.

Invalid fields or unsupported sort directions should return descriptive errors.

---

# Performance

Sorting performance depends on:

- Indexed columns
- Query optimization
- Efficient repositories
- Storage driver capabilities

Large datasets should avoid sorting on non-indexed properties.

---

# Storage Independence

Repositories should expose a common sorting interface regardless of whether data is stored in:

- WordPress Meta
- Custom Tables
- SQLite
- JSON

Sorting behavior should remain consistent across all storage drivers.

---

# Best Practices

- Provide default sorting.
- Validate sort fields.
- Support multi-field sorting.
- Avoid storage-specific syntax.
- Document sortable properties.

---

# Summary

The OpenMeta sorting system provides a consistent, extensible, and storage-independent approach to ordering resources across PHP, REST, and GraphQL APIs.