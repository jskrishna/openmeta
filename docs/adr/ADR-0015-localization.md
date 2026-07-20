# ADR-0015: Localization

---

# Status

Accepted

---

# Context

OpenMeta is intended for a global developer community and should support multiple languages and regional preferences.

---

# Problem

Hardcoded language resources prevent international adoption and increase maintenance complexity.

---

# Decision

All user-facing text and regional formatting will be fully localized.

Localization resources remain separate from business logic and framework functionality.

---

# Alternatives Considered

### English-only Framework

Rejected because it limits adoption.

### Hardcoded Translations

Rejected because maintenance becomes difficult.

### Module-specific Localization

Rejected because consistency cannot be guaranteed.

---

# Consequences

Positive

- Global accessibility
- Better user experience
- Easier translations

Negative

- Translation maintenance

Trade-offs

- Additional localization work
- Much broader adoption

---

# Architecture

```text
Application

↓

Locale

↓

Translation Resources

↓

Localized Interface
```

---

# Impact

Influences:

- UI
- Documentation
- Validation
- APIs
- Extensions

---

# Related ADRs

- ADR-0013
- ADR-0021

---

# Summary

OpenMeta fully separates localization from application logic, enabling multilingual interfaces without affecting framework architecture.