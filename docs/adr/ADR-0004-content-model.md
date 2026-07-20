# ADR-0004: Content Model

---

# Status

Accepted

---

# Context

Structured content is the foundation of the OpenMeta framework.

---

# Problem

WordPress content often becomes tightly coupled to presentation, making structured data difficult to maintain and reuse.

---

# Decision

OpenMeta adopts a content-first architecture.

Primary entities include:

- Content Types
- Fields
- Field Groups
- Taxonomies
- Relationships
- Validation

Content structure remains independent of presentation.

---

# Alternatives Considered

### Theme-driven Content

Rejected because content should outlive presentation.

### Meta-box Driven Design

Rejected because it lacks a unified content model.

---

# Consequences

Positive

- Better API support
- Structured data
- Improved reuse
- Easier migrations

Negative

- More initial planning

Trade-offs

- Additional modeling effort
- Higher long-term flexibility

---

# Architecture

```text
Content Type

↓

Fields

↓

Relationships

↓

Storage

↓

API

↓

Presentation
```

---

# Impact

Defines the core data architecture of OpenMeta.

---

# Related ADRs

- ADR-0005
- ADR-0007
- ADR-0008

---

# Summary

OpenMeta separates content from presentation, enabling structured, reusable, and API-ready data models.