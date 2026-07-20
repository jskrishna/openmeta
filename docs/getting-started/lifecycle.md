# Lifecycle

---

# Purpose

The OpenMeta lifecycle describes the high-level sequence of events from application startup to request completion.

Understanding this process helps developers know when services, schemas, fields, and extensions become available.

This guide provides a simplified overview. For implementation details, refer to the **Core → Application Lifecycle** documentation.

---

# High-Level Lifecycle

```text
Bootstrap

↓

Load Configuration

↓

Initialize Container

↓

Register Service Providers

↓

Boot Service Providers

↓

Register Modules

↓

Register Extensions

↓

Load Schemas

↓

Register Fields

↓

Initialize APIs

↓

Ready
```

---

# Bootstrap

The application starts by loading the framework.

Responsibilities include:

- Autoloading
- Environment Detection
- Initial Setup

---

# Configuration

Configuration files are loaded.

Framework settings become available to all services.

---

# Dependency Container

The Dependency Injection Container is initialized.

Services can now be registered and resolved.

---

# Service Providers

Service Providers register:

- Services
- Repositories
- Storage Drivers
- Event Listeners
- Configuration

---

# Modules

Framework modules are initialized.

Examples:

- Database
- Fields
- API
- Security

---

# Extensions

Installed extensions are discovered and registered.

Extensions may contribute:

- Fields
- Services
- Events
- Packages

---

# Schemas

All available Schemas are loaded.

Each Schema is validated before registration.

---

# Fields

Fields are registered and associated with their respective Schemas.

Validation rules are prepared.

---

# APIs

Framework interfaces become available.

Examples:

- PHP API
- REST API
- GraphQL
- CLI

---

# Ready State

The application is fully initialized.

Developers can now:

- Read Data
- Write Data
- Execute Queries
- Access APIs

---

# Request Lifecycle

For each request:

```text
Request

↓

Validation

↓

Repository

↓

Storage Driver

↓

Database

↓

Response
```

---

# Shutdown

After the request completes:

- Resources are released.
- Events are finalized.
- Runtime memory is cleared.

Persistent data remains unchanged.

---

# Summary

The OpenMeta lifecycle provides a predictable initialization process that ensures configuration, services, schemas, fields, and APIs are available in the correct order before handling application requests. For a detailed breakdown of each phase, refer to the **Core → Application Lifecycle** documentation.