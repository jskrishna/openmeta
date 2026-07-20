# Service Container

> **Document:** Service Container
> **Status:** Draft
> **Version:** Pre-Alpha

---

# Purpose

This document defines the Service Container used by OpenMeta.

The Service Container is responsible for creating, managing, and resolving application services throughout the lifecycle of the plugin.

It acts as the central registry for all core services and ensures loose coupling between different parts of the system.

---

# Why a Service Container?

Traditional WordPress plugins often rely on:

- Global variables
- Singleton classes
- Static methods
- Direct object creation

While simple, these approaches become difficult to maintain as projects grow.

OpenMeta adopts a Service Container to provide a modern, scalable architecture.

---

# Goals

The container should provide:

- Loose coupling
- Dependency Injection
- Centralized service registration
- Better testing
- Better maintainability
- Predictable initialization
- Modular architecture

---

# Responsibilities

The Service Container is responsible for:

- Registering services
- Resolving dependencies
- Managing singleton instances
- Managing transient services
- Booting service providers
- Sharing common services across modules

The container should never contain business logic.

---

# Container Lifecycle

```text
Application Starts

↓

Container Created

↓

Core Services Registered

↓

Providers Registered

↓

Modules Registered

↓

Services Resolved

↓

Application Ready
```

---

# Core Services

The following services are expected to exist inside the container.

```text
Configuration

Logger

Cache

Database

Asset Manager

Hook Manager

Field Registry

Field Factory

Validation Engine

Location Engine

REST API

Admin UI

Settings

Permissions
```

---

# Registration Flow

Services should be registered before they are resolved.

Example:

```text
Configuration

↓

Logger

↓

Database

↓

Cache

↓

Assets

↓

Field Registry

↓

Validation

↓

REST API

↓

Admin UI
```

Never resolve a service before registration.

---

# Service Types

OpenMeta supports two service lifetimes.

## Singleton

Only one instance exists during a request.

Examples:

- Configuration
- Logger
- Database
- Cache
- Settings

---

## Transient

A new instance is created every time.

Examples:

- Validators
- Temporary Builders
- Export Jobs
- Import Jobs

---

# Service Providers

Every major module should have its own Service Provider.

Example:

```text
FieldServiceProvider

ApiServiceProvider

AdminServiceProvider

DatabaseServiceProvider

CacheServiceProvider
```

A Service Provider is responsible only for registering its own services.

---

# Dependency Resolution

Services should receive dependencies through constructor injection.

Correct:

```text
Field Registry

↓

Database

↓

Logger
```

Incorrect:

```text
Field Registry

↓

Creates Database

↓

Creates Logger
```

Dependencies should be provided by the container.

---

# Module Registration

Each module registers itself.

Example:

```text
Bootstrap

↓

Register Provider

↓

Provider Registers Services

↓

Module Ready
```

The bootstrap layer should not know implementation details.

---

# Container Rules

The Service Container must:

- Resolve services lazily where possible.
- Avoid circular dependencies.
- Keep registration deterministic.
- Never expose internal implementation.
- Support interfaces over concrete classes.

---

# Naming Conventions

Service names should be descriptive.

Examples:

```text
config

logger

database

cache

field.registry

field.factory

validation

rest.api

admin.ui
```

Avoid generic names like:

```text
service

manager

helper

utils
```

---

# Error Handling

If a service cannot be resolved:

- Throw a descriptive exception.
- Include the service identifier.
- Log the failure.
- Never fail silently.

---

# Performance

The container should:

- Instantiate services only when needed.
- Cache singleton instances.
- Avoid unnecessary object creation.
- Minimize memory usage.

---

# Testing

The container should make testing easier.

Benefits:

- Mock dependencies
- Replace services
- Isolate modules
- Unit test individual components

---

# Future Enhancements

Possible future improvements:

- Auto-discovery of Service Providers
- Package-based registration
- Extension loading
- Lazy Provider Booting
- Plugin Extensions API

---

# Summary

The Service Container is the backbone of OpenMeta's architecture.

It provides a centralized, testable, and maintainable way to manage application services.

Every major subsystem should interact through the container instead of creating dependencies directly.