# First Schema

---

# Purpose

A Schema is the foundation of every OpenMeta application.

It defines the structure of your content by organizing Field Groups, Fields, Validation Rules, Relationships, and Storage Configuration into a single logical model.

This guide introduces the lifecycle and design of a Schema.

---

# What is a Schema?

A Schema describes a type of structured content.

Examples include:

- Product
- Event
- Employee
- Course
- Property
- Vehicle

Each Schema represents one business concept.

---

# Schema Structure

```text
Schema

├── Field Groups

├── Fields

├── Validation

├── Location Rules

└── Storage Configuration
```

---

# Example

Product Schema

```text
Product

├── General

│   ├── Name
│   ├── Description

├── Pricing

│   ├── Price
│   ├── Sale Price

├── Inventory

│   ├── SKU
│   ├── Stock
```

---

# Schema Lifecycle

```text
Create

↓

Register

↓

Validate

↓

Store

↓

Load

↓

Update
```

Schemas remain active throughout the application's lifecycle.

---

# Field Groups

A Schema organizes information into logical sections.

Example:

```text
General

Pricing

Inventory

SEO
```

Grouping Fields improves readability and maintenance.

---

# Location Rules

A Schema becomes active only when assigned to one or more locations.

Examples:

```text
Product Post Type
```

```text
User Profile
```

```text
Category
```

---

# Validation

Schemas enforce validation before persistence.

Typical rules include:

- Required
- Numeric
- Email
- URL
- Range
- Pattern Matching

---

# Storage

Storage is configured independently of the Schema.

Possible implementations include:

- WordPress Meta
- Custom Tables
- SQLite
- JSON

The Schema remains unchanged regardless of storage.

---

# Best Practices

- One business concept per Schema.
- Keep Field Groups focused.
- Use meaningful names.
- Separate presentation from data.
- Reuse validation rules.
- Keep Schemas modular.

---

# Common Mistakes

Avoid:

- Extremely large Schemas.
- Duplicate Fields.
- Mixing unrelated business concepts.
- Storage-specific assumptions.

Schemas should model the business domain, not the database.

---

# Next Steps

After creating a Schema, continue by adding Fields and Field Groups.

---

# Summary

A Schema is the highest-level representation of structured content within OpenMeta. By organizing related Fields, validation, and storage configuration into a single model, Schemas provide the foundation for scalable and maintainable applications.