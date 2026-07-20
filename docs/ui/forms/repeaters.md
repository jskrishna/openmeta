# Repeaters

---

# Purpose

The Repeater System allows users to dynamically create, edit, reorder, and remove multiple instances of a field group within a single form.

Repeaters enable flexible data structures while maintaining a consistent editing experience.

---

# Goals

The Repeater System should:

- Support dynamic collections
- Simplify complex forms
- Enable nested structures
- Maintain performance
- Preserve accessibility
- Support extensibility

---

# Architecture

```text
Repeater

↓

Rows

↓

Field Group

↓

Fields

↓

State Manager

↓

Validation

↓

API
```

---

# Responsibilities

The Repeater System manages:

- Row creation
- Row removal
- Reordering
- Duplication
- Nested repeaters
- Validation
- State synchronization

---

# Lifecycle

```text
Initialize

↓

Render Rows

↓

Add Row

↓

Edit Row

↓

Validate

↓

Reorder

↓

Delete Row

↓

Submit
```

---

# Features

Supported capabilities include:

- Unlimited Rows
- Minimum and Maximum Limits
- Nested Repeaters
- Drag & Drop Sorting
- Row Duplication
- Collapsible Rows
- Default Values
- Conditional Rows

---

# Row Structure

```text
Repeater

├── Row

│   ├── Field

│   ├── Field

│   └── Field

├── Row

└── Row
```

---

# State Management

Each row should maintain:

- Values
- Validation State
- Dirty State
- Expansion State
- Unique Identifier

Rows should remain independent from one another.

---

# Accessibility

Repeaters should:

- Support keyboard reordering
- Announce row changes
- Maintain logical focus
- Provide descriptive controls

---

# Performance

The Repeater System should:

- Virtualize large collections
- Re-render only affected rows
- Lazy render collapsed content
- Batch updates

---

# Extensibility

Developers may customize:

- Row templates
- Controls
- Sorting behavior
- Validation strategies
- Rendering layouts

---

# Best Practices

- Limit excessive nesting.
- Preserve row state.
- Use stable row identifiers.
- Confirm destructive actions.
- Optimize large repeaters.

---

# Summary

The OpenMeta Repeater System provides a scalable, accessible, and extensible solution for managing dynamic collections of structured data while integrating seamlessly with forms, validation, and state management.