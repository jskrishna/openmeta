# Field Types

---

# Purpose

Field Types define how data is collected, validated, rendered, and stored.

Every Field belongs to exactly one Field Type.

---

# Architecture

```text
Field

↓

Field Type

├── Renderer
├── Validator
├── Serializer
├── Storage Mapper
└── Configuration
```

---

# Built-in Field Types

Primitive Fields

- Text
- Textarea
- Number
- Email
- URL
- Password
- Hidden

Selection Fields

- Select
- Radio
- Checkbox
- Toggle

Date & Time

- Date
- Time
- DateTime

Media

- Image
- Gallery
- File
- Video

Relationship

- Post
- User
- Term
- Relationship

Structured

- Repeater
- Group
- Flexible Layout
- JSON

Rich Content

- Rich Text
- Markdown
- Code Editor

Advanced

- Color Picker
- Icon Picker
- Map
- Slug
- UUID

---

# Responsibilities

Field Types define:

- Rendering
- Validation
- Storage
- Serialization
- Configuration

---

# Extensibility

Developers may create custom field types by implementing framework contracts.

---

# Best Practices

- Keep types focused.
- Separate UI from logic.
- Reuse validators.
- Avoid storage-specific code.

---

# Summary

Field Types provide reusable building blocks that define the behavior of every field in OpenMeta.