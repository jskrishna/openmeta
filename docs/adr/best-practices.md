# ADR Best Practices

---

# Purpose

This document defines the recommended practices for writing high-quality Architecture Decision Records within OpenMeta.

Well-written ADRs improve architectural understanding, contributor onboarding, and long-term project maintainability.

---

# Goals

ADR authors should strive to produce documentation that is:

- Clear
- Concise
- Objective
- Consistent
- Architecture-focused
- Future-proof

---

# Writing Principles

Every ADR should:

- Describe one architectural decision.
- Explain the problem before the solution.
- Document architectural reasoning.
- Record rejected alternatives.
- Describe trade-offs honestly.
- Remain implementation independent.

---

# Recommended Structure

```text
Context

↓

Problem

↓

Decision

↓

Alternatives

↓

Consequences

↓

Architecture

↓

Impact

↓

Summary
```

---

# Writing Guidelines

## Be Objective

Document facts rather than opinions.

---

## Explain Why

Architectural reasoning is more valuable than implementation details.

---

## Record Trade-offs

Every architectural decision introduces compromises.

Document both benefits and drawbacks.

---

## Keep Scope Focused

Each ADR should describe one major decision.

Large topics should be split into multiple ADRs.

---

## Preserve History

Never delete or rewrite accepted ADRs.

Instead:

- Create a new ADR
- Reference the previous decision
- Mark the old ADR as Superseded

---

## Avoid Implementation Details

ADRs describe architecture rather than code.

Implementation belongs in development documentation.

---

# Common Mistakes

Avoid:

- Mixing multiple decisions
- Writing implementation guides
- Omitting alternatives
- Ignoring consequences
- Using subjective language
- Rewriting historical ADRs

---

# Responsibilities

Everyone contributing architectural decisions should:

- Follow the ADR template
- Use consistent terminology
- Keep documentation current
- Respect historical decisions

---

# Summary

Following these best practices ensures that OpenMeta ADRs remain valuable architectural references throughout the lifetime of the project.