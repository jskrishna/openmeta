# Dependency Injection

> **Document:** Dependency Injection
> **Status:** Draft
> **Version:** Pre-Alpha

---

# Purpose

This document defines the Dependency Injection (DI) strategy used throughout OpenMeta.

Dependency Injection is a core architectural principle that ensures modules remain independent, testable, and maintainable.

All application components should receive their dependencies instead of creating them internally.

---

# Why Dependency Injection?

Without Dependency Injection, classes become tightly coupled.

Example:

❌ Bad

FieldRegistry creates its own:

- Database
- Logger
- Cache
- Validator

Every dependency is hardcoded.

Testing becomes difficult.

Maintenance becomes harder.

---

With Dependency Injection:

✅ Good

The Service Container provides all required dependencies.

FieldRegistry simply receives them.

This makes the system:

- Testable
- Flexible
- Replaceable
- Predictable

---

# Design Goals

Dependency Injection should provide:

- Loose coupling
- High cohesion
- Easy testing
- Better maintainability
- Explicit dependencies
- Modular development

---

# Supported Injection Type

OpenMeta officially supports:

✅ Constructor Injection

Example

```text
Database

↓

Logger

↓

Field Registry
```

Dependencies are injected during object construction.

---

# Unsupported Injection Types

The following patterns should be avoided.

❌ Property Injection

```text
class Example

↓

public Logger $logger;
```

Reason:

Hidden dependencies.

---

❌ Setter Injection

```text
setLogger()
```

Reason:

Objects may become invalid before setters are called.

---

❌ Static Dependency Access

```text
Logger::instance()
```

Reason:

Creates global state.

---

❌ Direct Object Creation

```text
new Database()
```

inside business logic.

Reason:

Creates tight coupling.

---

# Constructor Injection

Every required dependency must be declared inside the constructor.

Example:

```text
Field Registry

↓

Database

↓

Logger

↓

Configuration
```

Dependencies should always be visible.

---

# Dependency Rules

A class should only depend on:

- Interfaces
- Contracts
- Abstractions

Avoid depending on concrete implementations whenever possible.

---

# Dependency Direction

Dependencies should always flow downward.

```text
Application

↓

Services

↓

Repositories

↓

Utilities
```

Lower layers should never know higher layers.

---

# Allowed Dependencies

Example

```text
Field Registry

↓

Field Factory

↓

Validator
```

Valid.

---

Example

```text
REST API

↓

Field Registry
```

Valid.

---

# Forbidden Dependencies

Avoid circular dependencies.

Example

```text
Field Registry

↓

Validator

↓

Field Registry
```

Invalid.

Circular references are not allowed.

---

# Interface First

Every major service should expose an interface.

Example

```text
LoggerInterface

↓

FileLogger
```

Application code should depend on:

LoggerInterface

Not:

FileLogger

---

# Service Resolution

Dependencies should always be resolved by the Service Container.

Never manually instantiate service dependencies.

Correct flow:

```text
Container

↓

Resolve Logger

↓

Resolve Database

↓

Create Field Registry
```

---

# Optional Dependencies

Optional services should be clearly marked.

Avoid nullable dependencies unless absolutely necessary.

Prefer feature-specific service providers instead.

---

# Object Lifetime

Dependencies should respect service lifetime.

Singletons should not depend on transient objects unless intentional.

Transient services may depend on singleton services.

---

# Testing

Dependency Injection makes testing easier.

Benefits:

- Mock services
- Fake implementations
- Isolated unit tests
- Faster testing

Example

```text
Production

↓

Real Database

Testing

↓

Fake Database
```

No application code changes are required.

---

# Error Handling

If a required dependency cannot be resolved:

- Throw a descriptive exception.
- Log the failure.
- Stop initialization safely.

Never silently ignore missing dependencies.

---

# Best Practices

Always:

✅ Constructor Injection

✅ Interface-based programming

✅ Keep dependencies explicit

✅ Small focused classes

✅ Single Responsibility

---

Avoid:

❌ Service Locator pattern inside business logic

❌ Static helper classes

❌ Global variables

❌ Hidden dependencies

❌ Circular references

---

# Future Improvements

Potential future enhancements:

- Attribute-based injection
- Auto-wiring
- Package auto-discovery
- Lazy dependency resolution
- Compile-time container optimization

These enhancements must remain backward compatible.

---

# Summary

Dependency Injection is one of the core architectural principles of OpenMeta.

All dependencies should be provided by the Service Container through constructor injection.

Following these rules improves maintainability, testing, scalability, and long-term project health.

No module should create its own dependencies directly.