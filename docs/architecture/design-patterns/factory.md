# Factory Pattern

---

## Purpose

The Factory Pattern centralizes object creation and removes the responsibility of instantiating objects from business logic.

Instead of classes creating dependencies directly using `new`, object creation is delegated to a dedicated Factory.

This improves maintainability, extensibility, and testability.

---

# Problem

Without a Factory Pattern, object creation becomes scattered across the codebase.

Example:

```php
$field = new TextField();
```

Later:

```php
$field = new ImageField();
```

Later:

```php
$field = new GalleryField();
```

Eventually hundreds of files contain object creation logic.

Adding a new field type requires modifying multiple locations.

This violates the Open/Closed Principle.

---

# Solution

Centralize object creation inside dedicated factories.

Instead of:

```text
Admin UI

↓

new TextField()

↓

Field
```

Use:

```text
Admin UI

↓

FieldFactory

↓

TextField
```

Only the factory knows which class should be created.

---

# Why OpenMeta Uses It

OpenMeta is expected to support:

- Native fields
- Premium fields
- Third-party fields
- Custom fields
- AI-generated fields
- Future extensions

A factory allows all of these to be created consistently without changing existing code.

---

# Responsibilities

A Factory is responsible only for:

- Creating objects
- Resolving implementations
- Returning valid instances

A Factory should never:

- Save data
- Query the database
- Validate input
- Render UI
- Execute business logic

---

# Factory Types

OpenMeta may contain multiple specialized factories.

Examples:

```text
FieldFactory

ValidatorFactory

LocationFactory

ConditionFactory

LayoutFactory

StorageFactory

ExporterFactory

ImporterFactory

AssetFactory
```

Each factory has one responsibility.

---

# FieldFactory

The FieldFactory creates field instances.

Example flow:

```text
Field Type

↓

FieldFactory

↓

TextField
```

```text
Field Type

↓

FieldFactory

↓

ImageField
```

```text
Field Type

↓

FieldFactory

↓

RepeaterField
```

The caller never needs to know the implementation class.

---

# Registration Flow

Factories should use a registry.

Example:

```text
Text

↓

Registry

↓

TextField Class
```

```text
Image

↓

Registry

↓

ImageField Class
```

The factory asks the registry which implementation should be created.

---

# Architecture

```text
Application

↓

Field Registry

↓

FieldFactory

↓

Concrete Field
```

The registry stores metadata.

The factory creates instances.

The field contains business logic.

---

# Supported Object Types

The Factory Pattern should be used for:

- Fields
- Validators
- Rules
- Conditions
- Layouts
- Storage Drivers
- Exporters
- Importers
- Integrations

---

# Dependency Injection

Factories receive dependencies through constructor injection.

Example:

```text
FieldFactory

↓

Container

↓

Field Registry

↓

Logger
```

Factories should never create their own dependencies.

---

# Extensibility

Third-party developers should be able to register new implementations without modifying the factory.

Example:

```text
Plugin

↓

Registers MarkdownField

↓

Registry

↓

FieldFactory can create it
```

The factory remains unchanged.

---

# Error Handling

If an unknown type is requested:

Example:

```text
video-slider-field
```

The factory should:

- Throw a descriptive exception.
- Include the requested type.
- Log the error.
- Never return an invalid object.

---

# Performance

Factories should:

- Avoid unnecessary reflection.
- Reuse metadata where appropriate.
- Instantiate objects only when required.
- Keep creation lightweight.

---

# Testing

Factories should be unit tested independently.

Tests should verify:

- Correct object creation.
- Invalid type handling.
- Registry integration.
- Dependency resolution.

---

# Advantages

- Centralized object creation.
- Easier extension.
- Better testing.
- Cleaner business logic.
- Open for extension.
- Reduced coupling.

---

# Trade-offs

- Adds one additional abstraction layer.
- Requires proper registration.
- Slightly more setup during development.

The benefits outweigh these costs in a modular architecture like OpenMeta.

---

# Where to Use

Use the Factory Pattern when:

- Multiple implementations exist.
- Object creation is complex.
- Future extensibility is expected.
- Third-party integrations are supported.

---

# Where NOT to Use

Do not use a Factory for:

- Simple value objects.
- Immutable DTOs.
- Tiny utility classes.
- Objects created only once with no variation.

Adding a factory in these cases introduces unnecessary complexity.

---

# Future Considerations

The Factory Pattern should remain stable across future versions.

Potential enhancements include:

- Lazy instantiation
- Service Container integration
- Package auto-registration
- Plugin extension discovery
- AI-generated field registration

These enhancements should not change the public factory API.

---

# Summary

The Factory Pattern is the primary object creation mechanism in OpenMeta.

It centralizes instantiation, improves extensibility, reduces coupling, and enables third-party developers to introduce new implementations without modifying existing code.

All complex, extensible object creation should be handled through dedicated factories rather than direct instantiation.