# SPEC — `@openmeta/security`

> **Implementation contract.** Implement against this document. [README](./README.md) is the short overview.

**Status:** ✅ Complete — Phase 5 / `v0.4.0-alpha`

**Role:** Framework-level security primitives — **pure PHP**, no WordPress APIs in this package. WP adapters live in `@openmeta/wordpress`.

**Consumers:** `admin`, `api`, `fields`, `builder`, `wordpress`

---

## Purpose

Centralize access control and input/output safety: permissions, capabilities, nonces/CSRF, hashing, tokens, sanitization, escaping — so domain packages do not invent their own security helpers.

---

## Public Contracts (package index)

| API | Contract / Class |
| --- | ---------------- |
| Gate | `GateInterface` → `Permissions\Gate` |
| Authorizer | `AuthorizerInterface` → `Authorization\Authorizer` + `PolicyInterface` |
| Capabilities | `CapabilityCheckerInterface` → `ArrayCapabilityChecker` |
| Nonce | `NonceInterface` / `NonceHandlerInterface` → `Nonce`, `HmacNonceHandler` |
| CSRF | `CsrfTokenManagerInterface` → `CsrfTokenManager` |
| Sanitization | `SanitizerInterface` → `DefaultSanitizer` (+ `Sanitizer` static API) |
| Escaping | `EscaperInterface` → `DefaultEscaper` (+ `Escaper` static API) |
| Password | `PasswordHasherInterface` → `PasswordHasher` |
| Random | `SecureRandomInterface` → `SecureRandom` |
| Tokens | `TokenGeneratorInterface` → `TokenGenerator` |
| Context | `SecurityContext` |
| Support | `Support\ConstantTime\Comparator` |
| Provider | `SecurityServiceProvider` |
| Exceptions | Authorization, PermissionDenied, InvalidNonce, InvalidToken, Csrf, SecurityConfiguration |

---

## Folder Structure

```text
packages/security/
├── src/
│   ├── Authorization/
│   ├── Capabilities/
│   ├── Context/
│   ├── Contracts/
│   ├── CSRF/
│   ├── Escaping/
│   ├── Exceptions/
│   ├── Hashing/
│   ├── Nonce/
│   ├── Permissions/
│   ├── Random/
│   ├── Sanitization/
│   ├── Support/
│   └── Tokens/
├── tests/
├── docs/
├── README.md
└── SPEC.md
```

---

## Dependency Rules

| Direction | Rule |
| --------- | ---- |
| Required | `core`, `support` |
| Forbidden | `database`, `fields`, `api`, `admin`, `builder`, `wordpress`, WordPress PHP APIs |
| Consumers | `admin`, `api`, `fields`, `builder`, `wordpress` (WP bridges bind Security contracts) |

---

## Must not

- WordPress-specific classes or `function_exists('wp_*'|'current_user_can'|sanitize_*|esc_*)` in this package
- Login / sessions / OAuth / JWT / REST auth schemes
- Soft-fail authorization or nonce/CSRF checks

---

## Testing Strategy

See [packages/TESTING.md](../../TESTING.md) (Phase 12). WordPress layer asserts this package stays WP-agnostic.

---

## Future Scope

- Object-level policies catalog, audit stream, rate limits
- Never: admin screens, REST routing, or field-type logic inside Security
