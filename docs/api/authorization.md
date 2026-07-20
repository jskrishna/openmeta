# Authorization

---

# Purpose

Authorization determines whether an authenticated identity is permitted to perform a specific action.

Authorization answers the question:

> Is this user allowed to perform this operation?

Authorization occurs only after successful authentication.

---

# Authorization Flow

```text
Authenticated User

↓

Permission Check

↓

Policy

↓

Decision

↓

Application
```

---

# Authorization Principles

Authorization should be:

- Explicit
- Predictable
- Least Privilege
- Policy Based
- Extensible

---

# Authorization Layers

OpenMeta authorization may include:

- Roles
- Capabilities
- Policies
- Resource Ownership
- Custom Rules

Multiple layers may participate in a single authorization decision.

---

# Policies

Policies encapsulate authorization logic.

Examples include:

- View Schema
- Edit Schema
- Delete Schema
- Manage Extensions
- Configure Storage

Policies centralize permission decisions.

---

# Resource Ownership

Some resources may only be accessible by their owner.

Ownership rules should be evaluated before repository operations.

---

# Authorization Failures

When authorization fails:

- Stop processing.
- Return standardized errors.
- Avoid exposing protected resources.
- Record security events when appropriate.

---

# Extensibility

Extensions may:

- Register new permissions
- Add custom policies
- Extend capability checks
- Customize authorization providers

---

# Best Practices

- Deny by default.
- Keep policies centralized.
- Avoid authorization inside repositories.
- Validate permissions before business logic.
- Keep authorization independent from authentication.

---

# Summary

Authorization protects OpenMeta resources by ensuring authenticated users perform only the operations they are explicitly permitted to execute.