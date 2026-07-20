# Schema

---

# Purpose

The Schema defines the structural blueprint of metadata within OpenMeta.

It describes how data is organized, validated, related, and persisted without depending on any specific database technology or WordPress implementation.

A Schema acts as the contract between the Domain Layer and the Storage Layer, ensuring that metadata remains consistent regardless of how it is stored.

---

# Goals

The Schema should provide:

- A predictable data model
- Clear entity relationships
- Storage independence
- Extensibility
- Data consistency
- Validation support
- Version compatibility
- High performance

---

# Design Principles

The Schema follows these principles:

- The Domain defines the schema.
- Storage should implement the schema.
- Schemas remain independent of databases.
- Every entity has a unique identity.
- Relationships should be explicit.
- Schemas should be versionable.
- Backward compatibility should be preserved.

---

# Schema Architecture

```text
Application

↓

Domain Model

↓

Schema

↓

Repository

↓

Storage Driver

↓

Database
```

The Schema sits between the Domain Model and persistence.

---

# Core Schema

The OpenMeta Schema is composed of several core entities.

```text
Schema

├── Field Groups

├── Fields

├── Layouts

├── Validation Rules

├── Location Rules

└── Storage Configuration
```

Each entity has a specific responsibility within the metadata model.

---

# Schema Entity

A Schema represents an entire metadata definition.

Responsibilities include:

- Defining metadata structure
- Organizing Field Groups
- Managing relationships
- Assigning Location Rules
- Maintaining version information

Example:

```text
Product Schema

↓

General Information

↓

SEO

↓

Pricing

↓

Inventory
```

---

# Field Groups

Field Groups organize related Fields into logical sections.

Examples:

```text
General

SEO

Pricing

Shipping

Gallery
```

Field Groups improve organization and usability.

---

# Fields

Fields represent individual pieces of data.

Examples:

```text
Title

Price

Description

Email

Image

Gallery

Select

Repeater

Boolean
```

Every Field belongs to exactly one Field Group.

---

# Nested Fields

Certain Field Types may contain child Fields.

Example:

```text
Repeater

├── Name

├── Email

└── Phone
```

Nested Fields allow complex data structures.

---

# Layouts

Layouts define presentation only.

Examples:

```text
Single Column

Two Columns

Tabs

Accordion

Flexible Layout
```

Layouts never affect stored data.

---

# Validation Rules

Validation Rules ensure data integrity.

Examples:

```text
Required

Minimum Length

Maximum Length

Email

URL

Regex

Unique

Numeric
```

Validation occurs before persistence.

---

# Location Rules

Location Rules determine where a Schema is applied.

Examples:

```text
Post Type

↓

Product
```

```text
User Role

↓

Administrator
```

```text
Taxonomy

↓

Category
```

Location Rules remain independent of storage.

---

# Storage Configuration

Every Schema defines how data should be stored.

Supported strategies include:

- WordPress Meta
- Custom Tables
- JSON Storage
- External Storage

Storage behavior should remain configurable.

---

# Relationships

Primary relationships are:

```text
Schema

↓

Field Group

↓

Field

↓

Validation Rules

↓

Storage Configuration
```

Relationships are defined in the Domain before persistence.

---

# Cardinality

Typical relationships include:

```text
One Schema

↓

Many Field Groups
```

```text
One Field Group

↓

Many Fields
```

```text
One Repeater Field

↓

Many Child Fields
```

These relationships should remain consistent across all Storage Drivers.

---

# Identifiers

Every entity should have a stable identifier.

Examples:

```text
UUID

Field Key

Slug

Internal ID
```

Identifiers should never depend on database primary keys.

---

# Naming Conventions

Schema names should be:

- Descriptive
- Stable
- Human readable
- Unique within the project

Example:

```text
product

customer

order

invoice
```

Avoid ambiguous names.

---

# Versioning

Schemas should support versioning.

Example:

```text
Schema

↓

Version 1

↓

Version 2

↓

Version 3
```

Version history enables safe upgrades and migrations.

---

# Schema Evolution

Schema changes may include:

- Add Field
- Remove Field
- Rename Field
- Move Field
- Change Validation
- Update Layout
- Modify Location Rules

Changes should preserve compatibility whenever possible.

---

# Serialization

Schemas should be serializable.

Supported formats may include:

```text
JSON

YAML

PHP Array
```

Serialization enables:

- Import
- Export
- Version Control
- Deployment
- Backups

---

# Import & Export

Schemas should support complete portability.

Example flow:

```text
Export Schema

↓

JSON

↓

Import

↓

Validation

↓

Application
```

Imported Schemas should be validated before registration.

---

# Schema Validation

Before a Schema becomes active, validation should verify:

- Unique identifiers
- Required properties
- Relationship integrity
- Supported Field Types
- Valid Layout definitions
- Valid Location Rules

Invalid Schemas should never be registered.

---

# Performance Considerations

The Schema should:

- Load lazily.
- Support caching.
- Minimize memory usage.
- Avoid duplicate definitions.
- Resolve relationships efficiently.

Schema parsing should occur only when necessary.

---

# Error Handling

If a Schema is invalid:

- Stop registration.
- Report validation errors.
- Log diagnostics.
- Prevent partial loading.

The framework should never operate with an inconsistent Schema.

---

# Testing

Recommended tests include:

- Schema validation.
- Relationship integrity.
- Nested Field support.
- Version compatibility.
- Import/Export.
- Serialization.
- Performance.

Every Schema should pass validation before deployment.

---

# Best Practices

- Keep Schemas modular.
- Organize Fields into logical groups.
- Prefer reusable Validation Rules.
- Avoid duplicate Field definitions.
- Version Schemas consistently.
- Keep Layout separate from business logic.
- Validate before persistence.

---

# Future Considerations

Potential future enhancements include:

- Schema inheritance.
- Dynamic Schema composition.
- Conditional Schemas.
- Visual Schema Builder.
- Remote Schema Registry.
- AI-assisted Schema generation.

These enhancements should preserve the existing Schema contract.

---

# Summary

The Schema is the structural foundation of OpenMeta's metadata system.

By defining Entities, Field Groups, Fields, Relationships, Validation Rules, Layouts, and Storage Configuration independently of any persistence technology, the Schema provides a stable, extensible, and storage-agnostic model that enables consistent metadata management across WordPress and future platforms.