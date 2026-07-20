# Domain Model

---

# Purpose

The Domain Model defines the core business concepts of OpenMeta and the relationships between them.

It is the foundation of the framework and represents **what OpenMeta is**, independent of WordPress, databases, APIs, or the user interface.

The Domain Model follows the principles of **Domain-Driven Design (DDD)** by separating business concepts from infrastructure concerns.

---

# Goals

The Domain Model should provide:

- A clear business vocabulary
- High cohesion
- Loose coupling
- Separation of business and infrastructure
- Predictable relationships
- Scalability
- Extensibility
- Testability

---

# Design Principles

The Domain Model follows these principles:

- Business logic belongs to the domain.
- Infrastructure should depend on the domain.
- The domain should never depend on WordPress.
- Entities model business concepts.
- Value Objects model immutable data.
- Aggregates maintain consistency.
- Services contain domain operations.
- Events represent completed business actions.

---

# Domain Layers

```text
Presentation

↓

Application

↓

Domain

↓

Infrastructure
```

The Domain Layer is the heart of the framework.

Everything else depends on it.

---

# Core Domain

OpenMeta revolves around a small number of core concepts.

```text
Field

↓

Field Group

↓

Layout

↓

Location Rule

↓

Validation Rule

↓

Storage Driver

↓

Schema
```

These concepts define how metadata is modeled, validated, stored, and rendered.

---

# Entities

Entities have identity and lifecycle.

Core Entities include:

## Field

Represents a single metadata field.

Examples:

- Text
- Number
- Email
- Image
- Gallery
- Select
- Repeater

Responsibilities:

- Identity
- Configuration
- Validation
- Storage behavior

---

## Field Group

Represents a collection of Fields.

Responsibilities:

- Organize related fields.
- Define layouts.
- Apply location rules.
- Manage field ordering.

---

## Layout

Defines how Fields are presented.

Examples:

- Single Column
- Two Column
- Tabs
- Accordion
- Flexible Layout

Layouts affect presentation only.

---

## Schema

Represents a complete metadata definition.

Contains:

- Field Groups
- Fields
- Validation Rules
- Location Rules

A Schema is the primary aggregate of the framework.

---

## Location Rule

Defines where a Schema should appear.

Examples:

- Post Type
- Page Template
- User Role
- Taxonomy
- WooCommerce Product
- Custom Condition

---

## Validation Rule

Defines business validation.

Examples:

- Required
- Email
- URL
- Minimum Length
- Maximum Value
- Regex

Validation Rules are reusable.

---

## Storage Driver

Represents the persistence strategy.

Examples:

- WordPress Meta
- Custom Tables
- External API
- JSON Storage

Storage Drivers abstract infrastructure.

---

# Value Objects

Value Objects describe attributes without identity.

They are immutable.

Examples include:

- Field Name
- Field Key
- Field Type
- Slug
- Label
- Description
- UUID
- Position
- Priority
- Version

Changing a Value Object creates a new instance.

---

# Aggregate Roots

Aggregate Roots maintain consistency.

Primary aggregates include:

## Schema

Owns:

- Field Groups
- Fields
- Validation Rules
- Location Rules

---

## Field Group

Owns:

- Child Fields
- Layouts

---

## Repeater Field

Owns:

- Nested Fields

External objects should interact through Aggregate Roots only.

---

# Domain Services

Some business operations do not belong to a single Entity.

Examples:

- Schema Builder
- Field Validator
- Import Service
- Export Service
- Field Factory
- Layout Resolver

Domain Services coordinate domain behavior without owning state.

---

# Repositories

Repositories abstract persistence.

Examples:

```text
SchemaRepository

FieldRepository

FieldGroupRepository

StorageRepository

ValidationRepository
```

Repositories expose domain objects.

They never expose database tables.

---

# Domain Events

Business actions produce immutable events.

Examples:

```text
SchemaCreated

FieldCreated

FieldUpdated

FieldDeleted

FieldValidated

SchemaImported

SchemaExported
```

Events describe completed actions.

---

# Commands

Commands represent requested actions.

Examples:

```text
CreateSchema

UpdateSchema

DeleteSchema

CreateField

DeleteField

ImportSchema

ExportSchema
```

Commands express intent.

They do not contain business logic.

---

# Relationships

The primary relationships are:

```text
Schema

├── Field Group

│     ├── Field

│     ├── Field

│     └── Field

│

├── Location Rules

│

└── Validation Rules
```

Nested structures are supported through Composite Aggregates.

---

# Domain Boundaries

The Domain Layer must never depend on:

- WordPress APIs
- Database implementation
- REST API
- GraphQL
- Admin UI
- JavaScript
- HTTP requests

Instead, infrastructure depends on the domain.

---

# Infrastructure Mapping

Infrastructure components implement domain contracts.

Examples:

```text
Domain

↓

Repository Interface

↓

WordPress Repository
```

```text
Domain

↓

Storage Driver Interface

↓

Meta Storage Driver
```

This keeps the business model independent.

---

# Lifecycle

Typical lifecycle:

```text
Command

↓

Aggregate

↓

Validation

↓

Repository

↓

Domain Event

↓

Application
```

The Domain Layer owns business decisions throughout this process.

---

# Extensibility

Third-party packages may introduce:

- New Field Types
- New Validation Rules
- New Storage Drivers
- New Location Rules
- New Layouts

Extensions should integrate through domain contracts without modifying existing entities.

---

# Best Practices

- Keep entities focused on business behavior.
- Use Value Objects for immutable concepts.
- Access aggregates through Aggregate Roots.
- Isolate persistence behind repositories.
- Publish Domain Events for completed actions.
- Avoid infrastructure dependencies inside the domain.
- Prefer composition over inheritance.

---

# Future Considerations

Potential future enhancements include:

- Multi-tenant schemas.
- Schema versioning.
- Field inheritance.
- Dynamic aggregate composition.
- Event sourcing.
- CQRS support.

These enhancements should preserve the existing Domain Model and public contracts.

---

# Summary

The Domain Model is the conceptual foundation of OpenMeta.

By organizing the framework around Entities, Value Objects, Aggregates, Domain Services, Repositories, Commands, and Domain Events, OpenMeta establishes a clean separation between business logic and infrastructure. This architecture enables a scalable, extensible, and maintainable framework that remains independent of WordPress while providing a stable foundation for future growth.