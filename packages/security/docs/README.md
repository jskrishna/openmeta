# Security package docs

What `@openmeta/security` **owns**. Deep contract: [../SPEC.md](../SPEC.md) · Overview: [../README.md](../README.md).

**Pure PHP** — no WordPress APIs here. WP capability/nonce bridges: `@openmeta/wordpress`.

## Spine

| Area | Responsibility |
| ---- | ---------------- |
| Permissions / Capabilities / Gate | “Can this identity do X?” — fail closed |
| Authorization | Gate façade + optional policies |
| Nonce / CSRF | Mutating-request tokens (HMAC) |
| Sanitization | Inbound cleanup |
| Escaping | Outbound HTML/attr/url/js/css/json |
| Hashing / Random / Tokens | password_*, CSPRNG, signed opaque tokens |
| Context | Immutable actor/capability snapshot |

## Non-goals

Login, sessions, OAuth, JWT, REST auth schemes, admin UI, field types, database schema, WordPress roles/nonces.
