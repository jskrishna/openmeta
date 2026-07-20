# Storage Mapping

---

# Purpose

Storage Mapping defines how Field values are translated between the Domain Model and underlying Storage Drivers.

Fields never communicate directly with the database.

---

# Architecture

```text
Field

↓

Storage Mapper

↓

Repository

↓

Storage Driver

↓

Database
```

---

# Responsibilities

The Storage Mapper:

- Maps domain values
- Converts data types
- Handles serialization
- Supports multiple storage engines
- Maintains compatibility

---

# Mapping Types

Supported mappings include:

- Primitive Values
- Arrays
- JSON
- Relationships
- Nested Structures
- Binary Assets

---

# Storage Drivers

Mappings should work consistently across:

- WordPress Meta
- Custom Tables
- SQLite
- JSON Files
- External Storage

---

# Data Transformation

The mapper may:

- Normalize values
- Convert types
- Compress data
- Encrypt sensitive fields
- Serialize complex objects

---

# Performance

Recommendations:

- Batch writes
- Cache mappings
- Avoid redundant transformations
- Optimize nested structures

---

# Extensibility

Developers may register:

- Custom mappers
- Type converters
- Serialization strategies
- Storage adapters

---

# Best Practices

- Keep mappings deterministic.
- Avoid storage-specific logic.
- Reuse converters.
- Maintain backward compatibility.

---

# Summary

Storage Mapping bridges the Domain Model and persistence layer, enabling storage-independent field behavior across every supported Storage Driver.