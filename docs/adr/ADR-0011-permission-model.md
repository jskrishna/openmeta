# ADR-0011: Permission Model

---

# Status

Accepted

---

# Context

OpenMeta manages administrative features, structured content, APIs, and extensions that require controlled access.

---

# Problem

Coarse-grained authorization limits flexibility and increases security risks.

---

# Decision

OpenMeta adopts a centralized, role-aware permission model based on the principle of least privilege.

Authorization should be enforced consistently across:

- UI
- APIs
- Content
- Configuration
- Extensions

Permissions should be evaluated independently of business logic.

---

# Alternatives Considered

### UI-only Authorization

Rejected because APIs would remain unprotected.

### Hardcoded Permission Checks

Rejected because they reduce maintainability.

### Extension-specific Authorization

Rejected because it creates inconsistent security behavior.

---

# Consequences

Positive

- Consistent authorization
- Improved security
- Easier auditing
- Better extensibility

Negative

- Additional permission management

Trade-offs

- Slight complexity
- Significantly stronger security model

---

# Architecture

```text
User

↓

Authentication

↓

Permission Evaluation

↓

Authorization

↓

Resource Access
```

---

# Impact

Affects:

- APIs
- Content Types
- Fields
- Administration
- Extensions
- Audit Logging

---

# Related ADRs

- ADR-0003
- ADR-0007
- ADR-0016

---

# Summary

OpenMeta adopts a centralized permission model that provides consistent, secure, and extensible authorization across every framework component.