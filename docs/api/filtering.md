# Filtering

---

# Purpose

Filtering allows clients to retrieve only the resources that match specified criteria.

The filtering system should remain storage-independent and consistent across PHP, REST, and GraphQL APIs.

---

# Architecture

```text
Client

↓

Filter Request

↓

Query Builder

↓

Repository

↓

Storage Driver

↓

Results
```

---

# Supported Filters

Common filter types include:

- Equals
- Not Equals
- Contains
- Starts With
- Ends With
- Between
- Greater Than
- Less Than
- Exists
- In List

---

# Multiple Filters

Multiple filters may be combined to produce more precise queries.

Repositories should compose filters without exposing database-specific syntax.

---

# Nested Filtering

Filters may target:

- Related resources
- Nested Fields
- Repeater Fields
- Relationships

The implementation should remain consistent regardless of storage.

---

# Validation

All filter parameters should be validated before execution.

Invalid filters should return descriptive errors.

---

# Performance

Filtering performance depends on:

- Proper indexing
- Efficient repositories
- Optimized storage drivers
- Query caching

---

# Best Practices

- Validate every filter.
- Keep filtering storage-independent.
- Avoid exposing database syntax.
- Support composable filters.
- Optimize commonly used filters.

---

# Summary

Filtering provides a flexible, storage-independent mechanism for narrowing query results while maintaining consistent behavior across all OpenMeta APIs.