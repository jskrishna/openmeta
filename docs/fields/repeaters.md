# Repeaters

---

# Purpose

Repeaters allow a collection of fields to be repeated multiple times, enabling dynamic data structures without predefined limits.

---

# Architecture

```text
Repeater

↓

Rows

↓

Fields

↓

Storage
```

---

# Responsibilities

Repeaters manage:

- Dynamic rows
- Ordering
- Validation
- Serialization
- Persistence

---

# Row Lifecycle

```text
Create

↓

Edit

↓

Validate

↓

Store

↓

Retrieve

↓

Delete
```

---

# Nested Repeaters

Repeaters may contain:

- Groups
- Fields
- Other Repeaters
- Flexible Layouts

Nested repeaters should be used carefully to avoid excessive complexity.

---

# Storage

Repositories coordinate repeater persistence through Storage Drivers.

The implementation should remain storage-independent.

---

# Performance

Recommendations:

- Lazy load rows.
- Limit excessive nesting.
- Cache repeated structures.
- Batch persistence operations.

---

# Best Practices

- Keep rows small.
- Validate each row independently.
- Preserve ordering.
- Avoid unnecessary nesting.

---

# Summary

Repeaters enable dynamic, repeatable data structures while maintaining predictable validation, rendering, and storage behavior.