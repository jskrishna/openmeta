# Builder Pattern

---

## Purpose

The Builder Pattern separates the construction of complex objects from their final representation.

Instead of creating large objects with lengthy constructors or numerous setter methods, OpenMeta uses Builders to construct objects step by step in a clear, readable, and extensible manner.

This pattern is especially useful when an object contains many optional properties or requires validation before it can be created.

---

# Problem

As OpenMeta grows, many objects become increasingly complex.

Examples include:

- Field Definitions
- Field Groups
- Validation Rules
- Layout Configurations
- REST Responses
- Query Objects

Without a Builder Pattern, object creation often results in:

```php
new Field(
    $id,
    $name,
    $label,
    $type,
    $required,
    $defaultValue,
    $placeholder,
    $instructions,
    $wrapper,
    $validation,
    ...
);
```

Large constructors are difficult to read, maintain, and extend.

---

# Solution

Move object construction into dedicated Builder classes.

Instead of:

```text
Application

↓

Large Constructor

↓

Field Object
```

Use:

```text
Application

↓

FieldBuilder

↓

Field Object
```

The Builder manages the construction process.

---

# Why OpenMeta Uses It

Many OpenMeta objects contain:

- Optional configuration
- Nested objects
- Validation rules
- Conditional settings
- Layout metadata

Builders make these objects easier to create while keeping the final objects immutable whenever possible.

---

# Responsibilities

A Builder is responsible for:

- Constructing complex objects.
- Validating required configuration.
- Applying default values.
- Returning a fully initialized object.

A Builder should never:

- Save data.
- Query the database.
- Render UI.
- Execute business workflows.

---

# Builder Flow

```text
Application

↓

Builder

↓

Configure Properties

↓

Validate

↓

Build Object

↓

Ready Object
```

---

# Builder Types

Examples:

```text
FieldBuilder

FieldGroupBuilder

LayoutBuilder

ValidationBuilder

QueryBuilder

ApiResponseBuilder

SchemaBuilder
```

Each Builder creates one specific type of object.

---

# Construction Process

Typical build sequence:

```text
Create Builder

↓

Set Required Properties

↓

Set Optional Properties

↓

Validate Configuration

↓

Build Object

↓

Return Immutable Instance
```

---

# Fluent Interface

Builders should expose a fluent API.

Conceptually:

```text
Builder

↓

Set Label

↓

Set Type

↓

Set Required

↓

Set Default

↓

Build
```

Each step returns the Builder until construction is complete.

---

# Validation

Builders should validate configuration before creating the final object.

Examples:

- Required properties
- Invalid combinations
- Missing dependencies
- Duplicate configuration

If validation fails, the Builder should throw a descriptive exception.

---

# Default Values

Builders should apply sensible defaults.

Examples:

```text
Required = false

Readonly = false

Visible = true
```

Callers should only specify values that differ from defaults.

---

# Immutability

The object returned by the Builder should preferably be immutable.

Once created, its internal state should not change unexpectedly.

If modifications are required, a new Builder or object should be created.

---

# Dependency Injection

Builders receive dependencies through constructor injection.

Example:

```text
FieldBuilder

↓

Configuration

↓

Validation Service
```

Builders should never instantiate dependencies directly.

---

# Extensibility

Third-party packages should be able to extend Builders without modifying existing code.

Possible extensions:

```text
Custom Field Builder

Custom Schema Builder

Custom Export Builder
```

The Builder contract should remain stable.

---

# Error Handling

If the Builder cannot construct a valid object:

- Throw a descriptive exception.
- Explain which required property is missing.
- Never return a partially initialized object.

---

# Performance

Builders should:

- Minimize temporary object creation.
- Avoid unnecessary validation passes.
- Perform validation only when needed.
- Keep the build process lightweight.

---

# Testing

Each Builder should be tested independently.

Recommended tests:

- Valid construction
- Missing required values
- Default value application
- Invalid configuration
- Immutable object creation

---

# Advantages

- Cleaner object construction.
- Improved readability.
- Easier validation.
- Reduced constructor complexity.
- Better extensibility.
- Supports immutable objects.

---

# Trade-offs

- Additional classes.
- Slightly more implementation effort.
- May be unnecessary for simple objects.

For complex domain objects, the benefits outweigh the additional abstraction.

---

# Where to Use

Use the Builder Pattern for:

- Field Definitions
- Field Groups
- Query Objects
- Layout Configuration
- API Responses
- Schema Generation
- Export Definitions

---

# Where NOT to Use

Do not use Builders for:

- Simple value objects.
- Objects with only two or three required properties.
- Stateless helper classes.
- Utility functions.

In these cases, direct construction is usually simpler.

---

# Related Patterns

The Builder Pattern commonly works with:

- Factory Pattern (creates Builders)
- Repository Pattern (persists built objects)
- Strategy Pattern (configures build behavior)
- Composite Pattern (builds nested structures)

---

# Future Considerations

Possible future enhancements include:

- Nested Builders
- Builder presets
- AI-assisted schema generation
- JSON/YAML-based Builders
- Visual Builder integration

These enhancements should remain compatible with the existing Builder API.

---

# Summary

The Builder Pattern provides a structured and readable way to construct complex objects in OpenMeta.

By separating object construction from object representation, Builders improve maintainability, validation, extensibility, and developer experience while keeping domain objects clean and predictable.