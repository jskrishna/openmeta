# Package System

---

# Purpose

The Package System enables OpenMeta to be extended through reusable, installable, and independently versioned packages.

Instead of placing all functionality inside the core framework, features can be distributed as packages that integrate seamlessly through the Service Container, Service Providers, Events, Hooks, and Modules.

The Package System is designed to encourage a healthy ecosystem while keeping the core framework lightweight.

---

# Goals

The Package System should provide:

- Modular architecture
- Composer integration
- Automatic package discovery
- Version compatibility
- Dependency management
- Independent releases
- Easy installation
- Third-party extensibility

---

# Design Principles

The Package System follows these principles:

- Everything is a package.
- Packages are self-contained.
- Packages communicate through contracts.
- Packages should never modify the framework directly.
- Installation should be automated.
- Removal should not affect unrelated packages.

---

# What is a Package?

A Package is an independently developed unit of functionality that extends OpenMeta.

A package may contain:

- Service Providers
- Modules
- Configuration
- Events
- Hooks
- Commands
- Field Types
- Validation Rules
- Storage Drivers
- REST Endpoints
- GraphQL Extensions
- Assets
- Translations

Packages should encapsulate everything required for their functionality.

---

# Package Architecture

```text
OpenMeta

↓

Package Manager

↓

Package

├── Service Provider
├── Configuration
├── Contracts
├── Services
├── Commands
├── Resources
├── Assets
├── Routes
└── Tests
```

Every package integrates through a Service Provider.

---

# Package Lifecycle

Each package follows a predictable lifecycle.

```text
Installed

↓

Discovered

↓

Registered

↓

Booted

↓

Ready

↓

Running

↓

Updated

↓

Removed
```

The framework should manage this lifecycle automatically.

---

# Package Discovery

Packages may be discovered from:

- Composer packages
- Local packages
- Vendor packages
- Premium packages
- Enterprise packages

Discovery should require no manual registration whenever possible.

---

# Composer Integration

Composer is the primary installation mechanism.

```text
composer require vendor/package

↓

Composer Install

↓

Package Discovery

↓

Register Provider

↓

Boot Package
```

Packages should follow PSR-4 autoloading.

---

# Service Providers

Every package should expose at least one Service Provider.

Responsibilities include:

- Register services
- Merge configuration
- Register hooks
- Register events
- Register commands
- Register routes

Packages should never bootstrap themselves directly.

---

# Configuration

Packages may publish configuration.

Example:

```text
Package

↓

config/package.php

↓

Configuration Repository
```

Configuration is merged during application boot.

---

# Module Integration

Packages may register one or more modules.

Example:

```text
SEO Package

↓

SEO Module

↓

OpenMeta
```

Modules remain isolated from the framework core.

---

# Event Integration

Packages may:

- Publish events
- Listen for events
- Register subscribers

Example:

```text
FieldCreated

↓

SEO Package

↓

Generate Metadata
```

Packages should prefer events over direct dependencies.

---

# Hook Integration

Packages may expose or consume hooks.

Example:

```text
field.created

↓

Package Callback

↓

Additional Processing
```

Hooks provide extension points without modifying core code.

---

# Command Integration

Packages may register CLI commands.

Examples:

```text
Generate Sitemap

Optimize Metadata

Import Templates
```

Commands are registered through the package's Service Provider.

---

# Routes

Packages may register:

- REST API routes
- GraphQL extensions
- Admin routes
- AJAX handlers

Route registration occurs during the boot phase.

---

# Assets

Packages may include:

- JavaScript
- CSS
- Images
- Icons
- Fonts

Assets should remain inside the package.

---

# Translations

Packages may include localization resources.

Example:

```text
resources/lang/

en

de

fr

hi
```

Translations should be loaded automatically.

---

# Dependency Management

Packages may depend on:

- Core framework
- Other packages
- PHP libraries

Dependencies should be declared explicitly.

Circular dependencies are prohibited.

---

# Version Compatibility

Packages should declare supported framework versions.

Example:

```text
OpenMeta

>=1.0

<2.0
```

The Package Manager should validate compatibility before loading.

---

# Package Manifest

Every package should provide metadata.

Typical information includes:

- Name
- Version
- Description
- Author
- Service Providers
- Dependencies
- Compatibility
- License

The manifest enables automatic discovery and validation.

---

# Package Isolation

Packages should never:

- Modify framework internals.
- Override core services without permission.
- Access private classes.
- Depend on undocumented APIs.

Interaction should occur through public contracts only.

---

# Error Handling

If a package fails during initialization:

- Skip package boot.
- Log the failure.
- Report diagnostics.
- Continue loading other compatible packages when possible.

A faulty package should not compromise the framework.

---

# Security Considerations

Packages should:

- Validate input.
- Escape output.
- Respect capability checks.
- Follow framework security policies.
- Avoid arbitrary code execution.
- Use only documented extension points.

Package installation should never bypass security restrictions.

---

# Performance Considerations

The Package System should:

- Discover packages efficiently.
- Cache package manifests.
- Lazy-load optional services.
- Avoid unnecessary initialization.
- Minimize startup overhead.

Large package ecosystems should remain performant.

---

# Testing

Every package should include its own test suite.

Recommended tests include:

- Service registration.
- Provider booting.
- Event handling.
- Hook execution.
- Configuration loading.
- Dependency resolution.
- Compatibility validation.

Integration tests should verify interaction with the framework.

---

# Best Practices

- Keep packages focused.
- Use one primary responsibility.
- Register through Service Providers.
- Communicate through contracts.
- Avoid modifying core behavior directly.
- Follow Semantic Versioning.
- Document all public APIs.

---

# Future Considerations

Potential future enhancements include:

- Official package marketplace.
- Digital package signatures.
- Package verification.
- Dependency conflict resolution.
- Package update notifications.
- Visual package manager.
- Remote package repositories.

These enhancements should preserve the existing Package System contracts.

---

# Summary

The Package System provides OpenMeta with a scalable foundation for distributing and integrating reusable functionality.

By leveraging Composer, Service Providers, Modules, Events, Hooks, and well-defined public contracts, OpenMeta enables both first-party and third-party packages to extend the framework safely, efficiently, and independently while keeping the core lightweight and maintainable.