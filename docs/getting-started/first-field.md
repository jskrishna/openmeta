# First Field

---

# Purpose

Fields are the building blocks of every Schema.

Each Field represents a single piece of information that can be validated, stored, and retrieved by OpenMeta.

This guide introduces Field fundamentals before exploring the complete Field System.

---

# What is a Field?

A Field represents one value within a Schema.

Examples:

- Name
- Price
- Email
- Image
- Date
- Boolean
- Relationship

Each Field has one clear responsibility.

---

# Field Hierarchy

```text
Schema

↓

Field Group

↓

Field
```

Every Field belongs to exactly one Field Group.

---

# Example

```text
Product

↓

General Information

↓

Product Name
```

---

# Field Types

OpenMeta supports many Field Types.

Examples include:

```text
Text

Textarea

Number

Email

URL

Date

Select

Checkbox

Radio

Boolean

Image

Gallery

Relationship

Repeater
```

Additional Field Types can be added through extensions.

---

# Field Properties

Every Field contains metadata describing its behavior.

Common properties include:

- Name
- Key
- Label
- Description
- Default Value
- Validation Rules
- Storage Settings

---

# Validation

Fields may define validation rules.

Examples:

- Required
- Minimum Length
- Maximum Length
- Numeric
- Email
- URL
- Pattern Matching

Validation occurs before persistence.

---

# Default Values

Fields may define optional default values.

Example:

```text
Status

↓

Draft
```

Default values improve consistency and reduce repetitive input.

---

# Storage

Fields do not know where they are stored.

The Storage Driver determines persistence.

```text
Field

↓

Repository

↓

Storage Driver

↓

Database
```

---

# Lifecycle

Every Field follows the same lifecycle.

```text
Create

↓

Configure

↓

Validate

↓

Store

↓

Retrieve

↓

Update
```

---

# Best Practices

- One Field represents one value.
- Use descriptive names.
- Apply validation where appropriate.
- Avoid duplicate Fields.
- Keep Field definitions reusable.

---

# Common Mistakes

Avoid:

- Using one Field for multiple purposes.
- Adding unnecessary validation.
- Depending on storage implementation.
- Mixing presentation with business logic.

---

# Next Steps

Once you're comfortable with basic Fields, continue with:

- Field Types
- Validation
- Field Groups
- Conditional Logic
- Layouts

---

# Summary

Fields are the fundamental units of structured content in OpenMeta. By defining a single piece of information along with its validation and configuration, Fields enable Schemas to model complex business data while remaining independent of storage and presentation.