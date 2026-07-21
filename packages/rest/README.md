# `@openmeta/rest`

Framework-independent REST infrastructure for OpenMeta — router, middleware pipeline, immutable request/response, resources, and dispatch kernel.

**Status:** ✅ `v0.7.0-alpha` · [SPEC](./SPEC.md)

---

## Exit criteria

| Criterion | Status |
| --------- | ------ |
| Router: register, group, version, middleware | ✅ |
| Immutable Request / Response | ✅ |
| Validation → `@openmeta/validation` | ✅ `RequestValidator` / `ValidateRequest` |
| Authorization → `@openmeta/security` Gate | ✅ `GateAuthorizer` |
| Pagination → `@openmeta/database` | ✅ `LengthAwarePaginator` helpers |
| Event lifecycle | ✅ RequestReceived → … → ResponseGenerated / ExceptionThrown |
| No WordPress-specific code | ✅ |
| PHPUnit / PHPStan / PHPCS | ✅ |
| Dependency rules + docs | ✅ |

---

## Public API (high level)

| Type | Role |
| ---- | ---- |
| `RestKernel` | Entry — `handle(Request): Response` |
| `Router` / `RouteRegistry` | Register and match routes |
| `Request` | Immutable inbound HTTP message |
| `JsonResponse` / `ErrorResponse` | Outbound envelopes |
| `MiddlewareInterface` | Extend the pipeline |
| `AuthenticatorInterface` | Plug in identity resolution |
| `ResourceInterface` | Serializable controller payloads |

---

## Test

```bash
php composer.phar test:rest
```

---

## Docs

- [SPEC](./SPEC.md) — implementation contract
- [docs/](./docs/README.md) — spine notes
