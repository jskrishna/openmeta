# Plugin Bootstrap

> **Document:** Plugin Bootstrap
> **Status:** Draft
> **Version:** Pre-Alpha

---

# Purpose

This document defines how OpenMeta starts inside WordPress.

The bootstrap process is responsible for preparing the plugin, registering services, loading dependencies, and initializing all core modules in a predictable and maintainable way.

Every request should follow the same initialization sequence.

---

# Goals

The bootstrap process should be:

- Fast
- Predictable
- Testable
- Extensible
- Easy to understand

---

# Bootstrap Principles

OpenMeta follows these bootstrap principles.

- Keep the entry point minimal.
- Initialize only what is required.
- Register services before using them.
- Never execute business logic in the plugin entry file.
- Load dependencies only once.
- Fail gracefully when requirements are not met.

---

# Bootstrap Flow

```text
WordPress

↓

Plugin Loaded

↓

Environment Check

↓

Autoloader

↓

Configuration

↓

Service Container

↓

Core Service Registration

↓

Hook Registration

↓

REST API

↓

Admin UI

↓

Plugin Ready
```

---

# Plugin Entry Point

The plugin entry file should only perform the following tasks.

- Define plugin constants
- Load Composer autoloader
- Verify environment requirements
- Create the application instance
- Start the application

The entry file should **not** contain:

- Database logic
- Field registration
- Admin rendering
- Business logic
- REST routes

---

# Environment Validation

Before initialization, OpenMeta must verify:

- Minimum PHP version
- Minimum WordPress version
- Required PHP extensions
- Composer autoload availability

If any requirement fails:

- Stop execution safely
- Show a meaningful admin notice
- Avoid fatal errors

---

# Constants

OpenMeta should expose only essential constants.

Examples:

```php
OPENMETA_VERSION

OPENMETA_PATH

OPENMETA_URL

OPENMETA_PLUGIN_FILE

OPENMETA_ASSETS_URL
```

Avoid creating unnecessary global constants.

---

# Composer Autoload

Composer is the only supported autoloader.

Requirements:

- PSR-4 compliant
- No manual include chains
- No custom autoload implementation

---

# Application Instance

The bootstrap process creates a single application instance.

Responsibilities:

- Initialize the container
- Register services
- Start modules
- Handle lifecycle events

Only one application instance should exist during a request.

---

# Service Registration

The bootstrap process should register services in a defined order.

Example:

```text
Configuration

↓

Logger

↓

Cache

↓

Database

↓

Asset Manager

↓

Hook Manager

↓

Field Registry

↓

Validation Engine

↓

REST API

↓

Admin UI
```

Every service must be registered before it is used.

---

# Hook Registration

WordPress hooks should be registered only after all required services are available.

Separate hooks into categories.

Example:

- Core Hooks
- Admin Hooks
- Frontend Hooks
- REST Hooks
- CLI Hooks

---

# Module Initialization

Every module should initialize itself.

The bootstrapper should never know module internals.

Example:

```text
Bootstrap

↓

Field Module

↓

Field Module initializes itself
```

Instead of

```text
Bootstrap

↓

Create field

↓

Register field

↓

Load assets

↓

Load validation

↓

...
```

---

# Error Handling

Bootstrap failures must never expose raw PHP errors.

Errors should be:

- Logged
- Developer friendly
- User safe

---

# Lifecycle Events

The plugin lifecycle consists of:

```text
Plugin Loaded

↓

Booting

↓

Services Registered

↓

Modules Loaded

↓

Hooks Registered

↓

Ready
```

Future events may include:

- Installing
- Updating
- Uninstalling

---

# Activation

Activation should perform only essential tasks.

Examples:

- Create database tables
- Store plugin version
- Register capabilities
- Flush rewrite rules if required

Activation must not:

- Import demo data
- Execute long-running tasks
- Download remote resources

---

# Deactivation

Responsibilities:

- Remove scheduled events
- Flush rewrite rules if necessary
- Clear temporary caches

Do not delete user data.

---

# Uninstall

The uninstall process should be independent.

Possible responsibilities:

- Remove plugin options
- Remove custom tables (optional)
- Remove scheduled jobs
- Clean transient data

User content should never be deleted automatically without explicit consent.

---

# Performance

Bootstrap should:

- Avoid unnecessary queries
- Avoid loading admin code on frontend requests
- Lazy-load optional services
- Keep initialization lightweight

---

# Security

During bootstrap:

- Verify environment
- Prevent direct file access
- Validate required dependencies
- Never trust user input

---

# Future Improvements

Future bootstrap enhancements may include:

- Module discovery
- Package auto-registration
- Extension loading
- Plugin marketplace support

---

# Summary

The bootstrap layer is responsible only for starting OpenMeta.

Business logic belongs inside modules.

Keeping the bootstrap lightweight improves maintainability, testing, performance, and long-term scalability.