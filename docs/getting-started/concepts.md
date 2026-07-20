# Core Concepts

---

# Purpose

Understanding the core concepts of OpenMeta is essential before building applications.

These concepts form the vocabulary used throughout the documentation and represent the fundamental building blocks of the framework.

---

# Architecture Overview

```text
Schema

↓

Field Group

↓

Field

↓

Validation

↓

Repository

↓

Storage Driver

↓

Database
```

---

# Schema

A Schema represents a business entity.

Examples:

- Product
- Customer
- Course
- Employee

The Schema is the Aggregate Root of the content model.

---

# Field Group

Field Groups organize Fields into logical sections.

Examples:

- General
- Pricing
- SEO
- Inventory

Field Groups improve organization but do not affect storage.

---

# Field

A Field stores one piece of information.

Examples:

- Title
- Price
- Image
- Email

Fields define:

- Type
- Validation
- Default Value
- Storage Behavior

---

# Validation

Validation ensures data integrity.

Common rules include:

- Required
- Numeric
- Email
- URL
- Length
- Pattern Matching

Validation occurs before persistence.

---

# Repository

Repositories provide access to Domain Objects.

Responsibilities include:

- Load
- Save
- Delete
- Query

Repositories isolate business logic from storage.

---

# Storage Driver

Storage Drivers persist data.

Examples:

- WordPress Meta
- Custom Tables
- SQLite
- JSON

Storage Drivers implement a common contract.

---

# Service Provider

Service Providers register framework services during application startup.

Responsibilities include:

- Dependency Registration
- Event Registration
- Configuration
- Module Bootstrapping

---

# Extension

Extensions add functionality without modifying the framework.

Examples:

- Custom Fields
- Storage Drivers
- Validation Rules
- Integrations

---

# Package

Packages distribute reusable functionality.

Packages may contain:

- Services
- Fields
- Extensions
- Resources

---

# Event

Events notify interested components that something has happened.

Examples:

- Schema Created
- Field Saved
- Validation Failed

Events reduce coupling between modules.

---

# Hook

Hooks provide extension points throughout the framework.

Developers may customize behavior without editing core code.

---

# Dependency Injection

Dependencies are supplied externally rather than created internally.

This improves:

- Testability
- Flexibility
- Maintainability

---

# Summary

These concepts form the foundation of OpenMeta. Understanding them makes the remaining documentation easier to follow and provides a shared language for developers building structured WordPress applications.