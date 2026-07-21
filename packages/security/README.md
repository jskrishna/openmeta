# `@openmeta/security`

> Pre-alpha contract — implement against this document, not ad-hoc assumptions.

---

## Purpose

Centralize capabilities, nonces, authorization helpers, and secure-by-default utilities used across admin, API, and field access paths.

---

## Responsibilities

- Capability and role helpers
- Nonce create/verify utilities
- Authorization gates for mutations and sensitive reads
- Sanitization/escaping helpers at package boundaries (where shared)

Must not own business workflows or UI screens.

---

## Public APIs

- Capability check APIs
- Nonce APIs
- Authorization gate contracts
- Shared sanitization/escaping helpers documented for package use

---

## Dependencies

- `packages/core`
- WordPress Roles / Capabilities / Nonces APIs

Must not depend on `admin`, `api`, `fields`, or `builder`.

---

## Extension Points

- Custom capability maps
- Authorization policy registration
- Security event hooks (denied access, nonce failure)

---

## Folder Structure

```text
packages/security/
├── src/
│   ├── Capabilities/
│   ├── Nonces/
│   ├── Authz/
│   └── Sanitization/
├── tests/
└── README.md
```
