# Application Lifecycle

---

# Purpose

The Application Lifecycle defines how OpenMeta initializes, executes, and shuts down during every WordPress request.

Instead of relying on scattered bootstrap logic, OpenMeta follows a predictable lifecycle inspired by modern frameworks such as Laravel and Symfony while remaining fully compatible with the WordPress execution model.

Every request—whether it is an Admin request, REST API request, AJAX request, WP-CLI command, Cron execution, or frontend page load—passes through the same lifecycle.

---

# Goals

The lifecycle should provide:

- Predictable startup process
- Centralized bootstrapping
- Consistent dependency initialization
- Modular loading
- Service registration
- Event-driven initialization
- Extensible boot process
- Reliable shutdown

---

# Design Principles

The lifecycle follows these principles:

- Bootstrap once
- Initialize in a deterministic order
- Avoid global state
- Load services lazily when possible
- Register before boot
- Boot before execution
- Keep WordPress-specific code isolated

---

# High-Level Lifecycle

```text
WordPress Loads Plugin

↓

Composer Autoload

↓

Bootstrap Application

↓

Create Application Instance

↓

Load Configuration

↓

Initialize Service Container

↓

Register Service Providers

↓

Boot Service Providers

↓

Register Modules

↓

Register Events

↓

Register Hooks

↓

Initialize APIs

↓

Application Ready

↓

Handle Request

↓

Shutdown
```

Every request follows this sequence.

---

# Lifecycle Phases

## Phase 1 — Plugin Discovery

WordPress discovers the plugin through the plugin header.

Responsibilities:

- Plugin metadata
- Version information
- Minimum PHP version
- WordPress compatibility

No framework logic executes during this phase.

---

## Phase 2 — Composer Autoload

Composer initializes.

Responsibilities:

- PSR-4 autoloading
- Helper functions
- Third-party packages

No application services are created yet.

---

## Phase 3 — Bootstrap

Bootstrap prepares the framework.

Responsibilities:

- Create Application instance
- Define constants
- Register base paths
- Initialize environment

The application is not yet ready.

---

## Phase 4 — Application Initialization

The Application becomes the central object.

Responsibilities:

- Store application paths
- Store environment
- Hold service container
- Manage lifecycle state

From this point onward, all framework components communicate through the Application.

---

## Phase 5 — Configuration Loading

Configuration files are loaded.

Examples:

```text
config/app.php

config/database.php

config/cache.php

config/api.php
```

Configuration should load before any services are registered.

---

## Phase 6 — Service Container Initialization

The Dependency Injection Container is initialized.

Responsibilities:

- Bind interfaces
- Register singletons
- Resolve dependencies
- Share services

No business logic executes here.

---

## Phase 7 — Service Provider Registration

Every provider registers its services.

Examples:

```text
Configuration Provider

↓

Logging Provider

↓

Database Provider

↓

Event Provider

↓

Field Provider

↓

REST Provider
```

Providers should only register services.

They should not execute application logic.

---

## Phase 8 — Service Provider Boot

After every provider is registered, each provider is booted.

Booting may include:

- Registering hooks
- Registering events
- Loading routes
- Loading modules
- Initializing integrations

Boot methods may safely depend on other providers because registration has already completed.

---

## Phase 9 — Module Registration

Internal modules are registered.

Examples:

```text
Fields

Validation

Storage

REST API

GraphQL

Admin UI

Import

Export
```

Third-party modules are also discovered during this phase.

---

## Phase 10 — Event Registration

The Event Dispatcher registers listeners.

Examples:

```text
FieldCreated

FieldUpdated

PluginActivated

ModuleLoaded

SchemaImported
```

No events should execute before registration completes.

---

## Phase 11 — Hook Registration

WordPress hooks are registered.

Examples:

```text
init

admin_menu

rest_api_init

admin_init

plugins_loaded
```

WordPress integration begins here.

---

## Phase 12 — API Initialization

Framework interfaces become available.

Examples:

- REST API
- GraphQL
- Admin Pages
- AJAX
- CLI Commands

Each interface initializes only when required.

---

## Phase 13 — Application Ready

The framework is now fully initialized.

At this point:

- Services are available
- Modules are loaded
- Events are registered
- Hooks are active
- APIs are available

Business logic may now execute safely.

---

## Phase 14 — Request Handling

The appropriate subsystem handles the current request.

Examples:

Frontend Request

↓

Render Fields

Admin Request

↓

Load UI

REST Request

↓

Execute Controller

CLI Request

↓

Execute Command

Each request uses the same initialized application.

---

## Phase 15 — Shutdown

After request completion:

- Flush logs
- Persist caches
- Dispatch shutdown events
- Release resources

OpenMeta should leave no inconsistent state.

---

# Boot Sequence Diagram

```text
WordPress

↓

Plugin

↓

Composer

↓

Bootstrap

↓

Application

↓

Configuration

↓

Container

↓

Providers (Register)

↓

Providers (Boot)

↓

Modules

↓

Events

↓

Hooks

↓

Interfaces

↓

Ready

↓

Request

↓

Shutdown
```

---

# Lifecycle States

The Application transitions through these states:

```text
Discovered

↓

Bootstrapping

↓

Initialized

↓

Configured

↓

Registered

↓

Booted

↓

Ready

↓

Running

↓

Shutdown
```

The current lifecycle state should always be known by the Application.

---

# Error Handling

If initialization fails:

- Stop boot process immediately.
- Log the exception.
- Display developer-friendly information in development mode.
- Display generic errors in production.

Partial initialization should never continue.

---

# Performance Considerations

The lifecycle should:

- Avoid unnecessary service creation.
- Use lazy loading where appropriate.
- Cache configuration.
- Register lightweight providers.
- Defer expensive operations until needed.

Application startup should remain as fast as possible.

---

# Extensibility

Third-party packages may participate in the lifecycle by providing:

- Service Providers
- Modules
- Events
- Hooks
- Configuration
- Commands

Extensions should integrate without modifying the core lifecycle.

---

# Best Practices

- Keep bootstrap minimal.
- Register before boot.
- Never execute business logic during registration.
- Use Dependency Injection exclusively.
- Avoid global state.
- Ensure deterministic initialization order.
- Keep WordPress-specific code inside adapters.

---

# Future Considerations

Potential future enhancements include:

- Deferred booting
- Module hot-loading
- Parallel initialization
- Cached provider manifests
- Performance profiling
- Lifecycle debugging tools

These enhancements should preserve the existing lifecycle contract.

---

# Summary

The Application Lifecycle defines the complete startup and shutdown process of OpenMeta.

By separating bootstrapping, configuration, dependency registration, provider booting, module loading, and request handling into clearly defined phases, OpenMeta achieves a predictable, extensible, and maintainable initialization process that scales from small plugins to enterprise-grade applications while remaining fully compatible with WordPress.