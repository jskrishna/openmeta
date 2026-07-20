# ADR-0003: WordPress First

---

# Status

Accepted

---

# Context

OpenMeta is designed specifically for the WordPress ecosystem.

---

# Problem

Attempting to support multiple CMS platforms would significantly increase complexity and reduce integration quality.

---

# Decision

OpenMeta will remain a WordPress-first framework.

Framework features will integrate naturally with:

- WordPress Core
- WordPress Roles
- REST API
- Gutenberg
- Plugin ecosystem
- Theme ecosystem

---

# Alternatives Considered

### Cross CMS Framework

Rejected because architectural compromises would increase.

### Headless-only Platform

Rejected because many users require native WordPress capabilities.

---

# Consequences

Positive

- Deep WordPress integration
- Better developer experience
- Lower maintenance

Negative

- Limited to WordPress ecosystem

Trade-offs

- Platform specialization instead of universal compatibility

---

# Architecture

```text
WordPress Core

↓

OpenMeta Framework

↓

Extensions

↓

Projects
```

---

# Impact

Guides all integration decisions throughout the framework.

---

# Related ADRs

- ADR-0002
- ADR-0007
- ADR-0009

---

# Summary

OpenMeta intentionally prioritizes the WordPress ecosystem to provide the highest quality developer and user experience.