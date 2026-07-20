# Meta Storage

---

# Purpose

Meta Storage is OpenMeta's default persistence strategy for storing metadata using WordPress's native metadata tables.

It provides maximum compatibility with the WordPress ecosystem while remaining fully abstracted behind the Storage Driver interface.

Applications may continue using Meta Storage or migrate to Custom Tables without changing business logic.

---

# Goals

Meta Storage should provide:

- Native WordPress compatibility
- Zero database modifications
- Storage abstraction
- Plugin interoperability
- Easy migration
- Stable APIs
- Predictable behavior

---

# Design Principles

The Meta Storage strategy follows these principles:

- Use native WordPress APIs.
- Never bypass WordPress metadata functions.
- Keep storage abstract.
- Preserve plugin compatibility.
- Separate business logic from persistence.

---

# Architecture

```text
Application

↓

Repository

↓

Meta Storage Driver

↓

WordPress Meta API

↓

Database
```

The Domain Layer never communicates directly with WordPress.

---

# Supported Meta Tables

OpenMeta supports all native metadata tables.

```text
wp_postmeta

wp_usermeta

wp_termmeta

wp_commentmeta

wp_options
```

Additional metadata sources may be supported through custom drivers.

---

# Storage Flow

```text
Schema

↓

Repository

↓

Meta Driver

↓

WordPress Metadata API

↓

Database
```

All operations are routed through the Meta Storage Driver.

---

# Advantages

Meta Storage provides:

- Excellent compatibility
- Easy deployment
- Existing plugin support
- WordPress API integration
- No custom schema maintenance

---

# Limitations

Meta Storage may become inefficient when:

- Millions of metadata records exist.
- Complex filtering is required.
- Heavy JOIN operations occur.
- Large datasets are queried.

Applications with these requirements should consider Custom Tables.

---

# Repository Integration

Repositories remain storage independent.

```text
Repository

↓

Meta Driver
```

Changing storage implementation should not affect repository behavior.

---

# Query Optimization

The Meta Storage Driver should:

- Minimize metadata queries.
- Batch lookups.
- Cache metadata.
- Avoid repeated API calls.

---

# Compatibility

Meta Storage should remain compatible with:

- WordPress Core
- WooCommerce
- Gutenberg
- Multisite
- Third-party plugins

---

# Migration Path

Applications may migrate from Meta Storage to Custom Tables.

```text
Meta Storage

↓

Migration

↓

Custom Tables
```

No Domain Layer changes should be required.

---

# Error Handling

If metadata operations fail:

- Report exceptions.
- Log diagnostics.
- Preserve consistency.
- Avoid partial updates.

---

# Performance Considerations

The Meta Storage Driver should:

- Cache metadata.
- Reduce repeated lookups.
- Support lazy loading.
- Minimize API overhead.

---

# Testing

Recommended tests include:

- CRUD operations.
- Metadata retrieval.
- Compatibility testing.
- Performance benchmarks.
- Multisite support.

---

# Best Practices

- Use the Meta Storage Driver exclusively.
- Avoid direct database access.
- Cache frequently used metadata.
- Use native WordPress APIs.
- Migrate only when necessary.

---

# Future Considerations

Potential future enhancements include:

- Smart metadata caching.
- Hybrid storage.
- Automatic migration tools.
- Metadata compression.

---

# Summary

Meta Storage provides OpenMeta with a reliable, WordPress-native persistence strategy.

By encapsulating WordPress metadata behind a dedicated Storage Driver, OpenMeta achieves complete compatibility while preserving a clean separation between business logic and infrastructure.