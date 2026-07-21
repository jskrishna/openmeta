# `@openmeta/security`

> Framework security primitives. **Pure PHP. No WordPress code. No UI.**

**Status:** ✅ Complete (Phase 5) · **v0.4.0-alpha**  
**Blueprint:** [SPEC.md](./SPEC.md) · Docs: [docs/README.md](./docs/README.md)

---

## Purpose

Interface-driven Gate/Authorizer, HMAC Nonce + CSRF, sanitize/escape, password hashing, CSPRNG, signed tokens.

WordPress capability/nonce adapters: `@openmeta/wordpress` (bind Security contracts).

---

## Exit criteria

- ✅ Framework-independent (Composer: core + support only)
- ✅ No WordPress-specific code in this package
- ✅ Services interface-driven
- ✅ CSRF & Nonce (HMAC) work
- ✅ Sanitization / escaping extensible via contracts
- ✅ Authorization storage-independent (`ArrayCapabilityChecker`)
- ✅ PHPUnit / PHPStan / PHPCS green

---

## Verify

```bash
composer test:security
composer ci
```
