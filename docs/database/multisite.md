# Multisite

---

# Purpose

The Multisite Architecture defines how OpenMeta operates within a WordPress Multisite network while ensuring complete tenant isolation, predictable behavior, and centralized management.

Every site in a Multisite network should behave as an independent OpenMeta installation unless explicitly configured otherwise.

The framework must support both single-site and multisite environments without requiring changes to business logic.

---

# Goals

The Multisite architecture should provide:

- Full WordPress Multisite compatibility
- Site isolation
- Shared framework code
- Independent metadata
- Independent configuration
- Network administration support
- Predictable behavior
- Scalable deployment

---

# Design Principles

The Multisite architecture follows these principles:

- Each site owns its own data.
- Core framework code is shared.
- Configuration may be local or network-wide.
- Storage Drivers must respect the active site.
- Cross-site communication is explicit.
- The Domain Layer remains multisite agnostic.

---

# Architecture

```text
Network

↓

Site

↓

OpenMeta

↓

Repository

↓

Storage Driver

↓

Site Database
```

Each site interacts only with its own storage context.

---

# Site Isolation

Every site should maintain independent:

- Schemas
- Field Groups
- Fields
- Configuration
- Metadata
- Cache
- Migrations

No site should accidentally access another site's data.

---

# Shared Components

The following components are shared across the network:

- Framework code
- Packages
- Extensions
- Contracts
- Core Services

These components remain identical for every site.

---

# Configuration

Configuration may exist at two levels.

### Network Configuration

Applies to every site.

Examples:

- License
- Installed Packages
- Framework Version

---

### Site Configuration

Applies only to the current site.

Examples:

- Active Schemas
- Storage Driver
- Settings
- Cache

---

# Storage Drivers

Storage Drivers must always operate inside the active site context.

```text
Current Site

↓

Storage Driver

↓

Database
```

Switching sites should automatically update storage context.

---

# Cache Isolation

Cache keys should include the Site Identifier.

Example:

```text
site:12:schema:product
```

This prevents cache collisions.

---

# Migration Strategy

Each site maintains its own migration history.

```text
Network

↓

Site A

↓

Migration History
```

```text
Network

↓

Site B

↓

Migration History
```

Network-wide migrations should be explicitly supported.

---

# Package Support

Packages should work without modification in Multisite.

Package developers should never need to detect Multisite manually.

The framework should provide the necessary abstractions.

---

# Repository Integration

Repositories automatically operate on the active site.

```text
Repository

↓

Site Context

↓

Storage Driver
```

Repositories remain unaware of multisite implementation details.

---

# Security

The Multisite architecture should prevent:

- Cross-site data access
- Shared cache corruption
- Invalid site switching
- Unauthorized administration

Site boundaries must always be enforced.

---

# Performance Considerations

The Multisite implementation should:

- Cache per site.
- Minimize site switching.
- Optimize network operations.
- Reduce duplicate initialization.

---

# Testing

Recommended tests include:

- Site isolation.
- Cache isolation.
- Migration execution.
- Repository behavior.
- Package compatibility.
- Network administration.

---

# Best Practices

- Respect current site context.
- Never share mutable data between sites.
- Namespace cache keys.
- Keep repositories site-aware.
- Keep the Domain Layer multisite independent.

---

# Future Considerations

Potential future enhancements include:

- Shared schema registry.
- Cross-site synchronization.
- Network analytics.
- Shared package marketplace.
- Global configuration service.

---

# Summary

The Multisite Architecture enables OpenMeta to operate reliably within WordPress Multisite by maintaining strict tenant isolation while sharing framework code and infrastructure. This design ensures scalability, predictable behavior, and compatibility across both single-site and enterprise multisite deployments.