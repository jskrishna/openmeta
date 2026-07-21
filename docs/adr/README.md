# Architecture Decision Records (ADR)

---

# Purpose

The Architecture Decision Records (ADR) document the significant architectural decisions made during the design and evolution of the OpenMeta framework.

Rather than documenting implementation details, ADRs explain **why** important decisions were made, what alternatives were considered, and the long-term impact of each decision.

---

# Goals

The ADR system should:

- Preserve architectural knowledge
- Explain design decisions
- Improve contributor onboarding
- Reduce repeated discussions
- Support long-term maintainability

---

# What is an ADR?

An Architecture Decision Record captures a single important architectural decision.

Each ADR answers:

- What problem existed?
- Why was a decision required?
- Which solution was selected?
- What alternatives were rejected?
- What are the long-term consequences?

---

# ADR Lifecycle

```text
Architectural Problem

↓

Discussion

↓

Decision

↓

ADR Created

↓

Accepted

↓

Referenced During Development

↓

Superseded (Optional)
```

---

# ADR Status

Each ADR should contain one of the following statuses:

- Proposed
- Accepted
- Superseded
- Deprecated
- Rejected

---

# Repository Structure

```text
adr/

├── README.md
├── ADR-0001...
├── ADR-0002...
│
└── template.md
```

---

# Responsibilities

ADRs document:

- Major architectural decisions
- Technology selection
- Design philosophy
- Framework evolution
- Long-term trade-offs

---

# Integration

ADRs integrate with:

- Architecture
- Development
- Roadmap
- Documentation
- Release Process

---

# Best Practices

- One decision per ADR.
- Record decisions immediately.
- Never rewrite history.
- Create a new ADR instead of modifying an accepted one.
- Explain the reasoning, not just the outcome.

---

# Summary

Architecture Decision Records preserve the reasoning behind OpenMeta's architecture, ensuring future contributors understand not only how the framework works, but why it was designed that way.

## Recent ADRs

| ADR | Title |
| --- | ----- |
| [ADR-0023](./ADR-0023-database-dal-no-active-record.md) | Database DAL (no Active Record) |
| [ADR-0024](./ADR-0024-post-rest-phase-order.md) | Post-REST phase order (framework-first) |
| [ADR-0025](./ADR-0025-extended-roadmap-to-v1.md) | Extended roadmap — SDK, doc generator, v1.0 (superseded tail by ADR-0026) |
| [ADR-0026](./ADR-0026-complete-framework-ecosystem.md) | Complete framework ecosystem — SDK, codegen, docgen, v1.0 at Phase 18 |