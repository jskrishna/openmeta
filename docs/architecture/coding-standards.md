# Coding Standards

> **Document:** Coding Standards
> **Status:** Draft
> **Version:** Pre-Alpha

---

# Purpose

This document defines the official coding standards for the OpenMeta project.

Every contributor must follow these standards to ensure consistency, maintainability, readability, and long-term project health.

These standards apply to all source code, documentation, tests, and tooling.

---

# Core Principles

OpenMeta follows these engineering principles.

- Readability over cleverness.
- Consistency over personal preference.
- Simplicity over complexity.
- Explicit over implicit.
- Composition over inheritance.
- Small, focused classes.
- Documentation before implementation.

---

# PHP Standards

OpenMeta follows:

- PSR-1
- PSR-4
- PSR-12

Additional project-specific conventions are defined below.

---

# PHP Version

Minimum supported version:

```text
PHP 8.3+
```

Do not use language features that require a higher version unless officially approved.

---

# WordPress Compatibility

The plugin must remain compatible with the minimum supported WordPress version defined in `TECH_STACK.md`.

Avoid undocumented WordPress APIs.

---

# Class Design

Every class should have a single responsibility.

Avoid "God Classes".

Preferred:

```text
FieldRegistry

FieldFactory

Validator

CacheManager
```

Avoid:

```text
Helper

Manager

Utils

Common
```

---

# File Structure

One public class per file.

File name must match the class name.

Example:

```text
FieldRegistry.php
```

---

# Methods

Methods should:

- Have one responsibility.
- Be short and readable.
- Return predictable values.
- Use descriptive names.

Avoid methods that perform multiple unrelated tasks.

---

# Properties

Properties should be:

- Private by default.
- Protected only when inheritance requires it.
- Public only when justified.

Prefer immutability where practical.

---

# Type Declarations

Always use strict typing where possible.

Example:

- Parameter types
- Return types
- Property types

Avoid mixed types unless necessary.

---

# Interfaces

Depend on interfaces instead of concrete implementations.

Example:

```text
LoggerInterface

↓

FileLogger
```

Application code should use the interface.

---

# Exceptions

Use exceptions only for exceptional situations.

Do not use exceptions for normal application flow.

Provide meaningful exception messages.

---

# Comments

Write comments only when they add value.

Good comments explain **why**, not **what**.

Avoid obvious comments.

---

# Documentation

Every public class should include:

- Purpose
- Responsibility

Every public method should describe:

- Parameters
- Return value
- Exceptions (if applicable)

---

# JavaScript Standards

JavaScript should use:

- Modern ES Modules
- TypeScript
- Strict mode
- ESLint
- Prettier

Avoid legacy patterns.

---

# React Standards

React components should:

- Be functional components.
- Use hooks.
- Be reusable.
- Have a single responsibility.

Avoid large monolithic components.

---

# CSS Standards

Use:

- Tailwind CSS
- Utility-first approach
- Design tokens

Avoid inline styles unless necessary.

---

# Imports

Group imports logically.

Recommended order:

1. External packages
2. Internal packages
3. Relative imports

---

# Folder Organization

Folders should represent responsibilities, not technologies.

Example:

```text
fields/

validation/

storage/

admin/
```

Avoid:

```text
misc/

helpers/

temp/
```

---

# Error Handling

Handle errors explicitly.

Never silently ignore failures.

Log meaningful information for debugging.

---

# Logging

Logging should be:

- Consistent
- Structured
- Actionable

Do not log sensitive user information.

---

# Security

Always:

- Sanitize input.
- Validate data.
- Escape output.
- Check permissions.
- Verify nonces where required.

Never trust user input.

---

# Performance

Write code with performance in mind.

Avoid:

- Unnecessary database queries.
- Duplicate processing.
- Excessive object creation.
- Loading resources that are not needed.

---

# Testing

Every new feature should include appropriate tests.

Testing is part of development, not an afterthought.

---

# Documentation Rule

No feature should be merged without corresponding documentation updates.

Documentation is part of the implementation.

---

# Pull Requests

Every Pull Request should:

- Follow coding standards.
- Pass automated checks.
- Include tests where appropriate.
- Update documentation if required.

---

# Future Standards

As OpenMeta evolves, additional standards may be introduced for:

- Accessibility
- Internationalization
- Performance benchmarks
- Plugin extensions
- AI-generated code

---

# Summary

Consistency is more important than individual coding preferences.

All contributors are expected to follow these standards to keep OpenMeta clean, maintainable, and predictable.