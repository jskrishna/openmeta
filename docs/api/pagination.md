# Pagination

---

# Purpose

Pagination divides large collections into manageable subsets, improving performance, reducing bandwidth, and providing a better developer experience.

All collection-based APIs should support pagination.

---

# Pagination Flow

```text
Client

↓

Pagination Request

↓

Repository

↓

Query

↓

Subset

↓

Response
```

---

# Pagination Strategies

OpenMeta supports multiple pagination strategies.

- Offset Pagination
- Cursor Pagination
- Page-based Pagination

The appropriate strategy depends on the use case.

---

# Offset Pagination

Uses an offset and limit to retrieve records.

Suitable for small and medium-sized datasets.

---

# Cursor Pagination

Uses a cursor to navigate sequential records.

Recommended for:

- Large datasets
- Infinite scrolling
- High-performance APIs

---

# Page Pagination

Retrieves data by page number.

This approach is easy to understand and suitable for administration interfaces.

---

# Pagination Metadata

Collection responses may include:

- Current Page
- Total Pages
- Total Records
- Page Size
- Next Link
- Previous Link

---

# Repository Integration

Repositories should implement pagination independently of storage technology.

Business logic should not depend on SQL-specific pagination behavior.

---

# Performance

Recommendations:

- Index sortable columns.
- Avoid loading unnecessary relationships.
- Limit maximum page sizes.
- Prefer cursor pagination for large datasets.

---

# Best Practices

- Always paginate large collections.
- Return pagination metadata.
- Keep pagination consistent across APIs.
- Avoid unlimited queries.
- Document default page sizes.

---

# Summary

Pagination enables OpenMeta to efficiently expose large datasets while maintaining consistent API behavior and scalable performance.