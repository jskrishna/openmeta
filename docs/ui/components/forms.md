# Forms

---

# Purpose

The Form System is responsible for rendering, managing, validating, and submitting user input throughout the OpenMeta administration interface.

Forms are the primary interface for creating and editing Schemas, Field Groups, Fields, Extensions, and System Configuration.

The Form System is built on top of the Field Engine while remaining independent of storage and business logic.

---

# Goals

The Form System should:

- Render complex forms
- Support dynamic fields
- Handle validation
- Manage form state
- Enable extensibility
- Provide excellent accessibility
- Scale to large forms

---

# Architecture

```text
Form

↓

Layout Engine

↓

Field Renderer

↓

UI Components

↓

Validation

↓

State Manager

↓

API

↓

Domain Layer
```

---

# Responsibilities

The Form System is responsible for:

- Rendering fields
- Managing values
- Validation feedback
- Submission
- Resetting state
- Autosave
- Dirty state tracking
- Error presentation

---

# Form Structure

```text
Form

├── Sections
├── Groups
├── Fields
├── Actions
└── Validation
```

---

# Supported Features

Forms support:

- Nested Fields
- Repeaters
- Conditional Logic
- Tabs
- Accordions
- Wizards
- Drag & Drop
- Autosave
- Async Validation

---

# Form States

Typical states include:

- Initial
- Dirty
- Valid
- Invalid
- Submitting
- Submitted
- Failed

---

# Validation

Validation should support:

- Client-side rules
- Server-side rules
- Async validation
- Real-time feedback
- Cross-field validation

---

# Accessibility

Forms should:

- Support keyboard navigation
- Associate labels correctly
- Display accessible errors
- Preserve focus
- Follow WCAG guidelines

---

# Extensibility

Developers may extend:

- Field Types
- Validators
- Layouts
- Submission handlers
- Form actions
- Custom controls

---

# Best Practices

- Keep forms focused.
- Validate early.
- Preserve user input.
- Display actionable errors.
- Avoid unnecessary complexity.

---

# Summary

The OpenMeta Form System provides a flexible, extensible, and accessible foundation for managing structured user input while integrating seamlessly with the Field Engine and Domain Layer.