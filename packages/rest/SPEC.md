# SPEC — `@openmeta/rest`

> **Implementation contract.** Implement against this document. [README](./README.md) is the short overview.

**Status:** ✅ Complete — `v0.7.0-alpha`

**Consumers:** `@openmeta/api`, headless integrations, CLI tools. WordPress route mounting lives in `@openmeta/wordpress` / `@openmeta/api` — not here.

---

## Purpose

Framework-independent REST infrastructure: immutable request/response, router, middleware pipeline, authentication/authorization contracts, resources, transformers, pagination, and dispatch kernel. **No WordPress APIs.**

---

## Module map

```text
RestKernel
    ↓
Router / RouteRegistry
    ↓
Middleware (Pipeline, Authenticate, Authorize, ValidateRequest)
    ↓
Controllers / Resources / Transformers
    ↓
Authentication (contracts + NullAuthenticator)
    ↓
Authorization (GateAuthorizer → @openmeta/security Gate)
```

Supporting: **Exceptions**, **Events**, **Pagination**, **Service Provider**.

---

## RestKernel

### Responsibilities

- Dispatch lifecycle events (`RequestReceived` → `RouteMatched` → `ControllerExecuted` → `ResponseGenerated`)
- Match routes, build middleware stack, invoke controller/callable
- Map uncaught throwables via `ExceptionHandler`

### Must not

- Register WordPress REST routes
- Implement JWT/OAuth/session authentication
- Own field-value or admin business logic

---

## Router

### Responsibilities

- Register routes (method, path, action, name, middleware, auth, permissions)
- Group routes (prefix, middleware, name prefix, version)
- Match requests with path params; distinguish 404 vs 405

### Public contracts

- `RouterInterface`, `Route`, `RouteCollection`, `RouteGroup`, `RouteRegistry`

### Must not

- Bind to PHP superglobals or `$_SERVER`

---

## Middleware

### Responsibilities

- `Pipeline::send($request)->through([...])->then($destination)`
- Resolve class-string middleware via Core container when available
- `Authenticate` (via `AuthenticatorInterface`), `Authorize` (via `GateAuthorizer`)

### Must not

- Duplicate Gate permission logic — delegate to `@openmeta/security`

---

## Authentication

### Responsibilities

- `AuthenticatorInterface` contract only in this package
- `NullAuthenticator` default — fail closed when auth required

### Must not

- Implement JWT, OAuth, or WordPress session bridges (those belong in `@openmeta/api` / `@openmeta/wordpress`)

---

## Authorization

### Responsibilities

- `GateAuthorizer` wraps `OpenMeta\Security\Permissions\Gate`
- Empty permission list = allow; any matching permission = allow; else `AuthorizationException`

---

## Resources & Transformers

### Responsibilities

- `JsonResource`, `ResourceCollection`, `PaginatedResource`, `ErrorResource`
- `TransformerRegistry` for named transformers
- `PaginationMeta` / `PaginatedResponse` for `LengthAwarePaginator`

---

## Dependency rules

May depend on:

- `@openmeta/core`
- `@openmeta/support`
- `@openmeta/validation`
- `@openmeta/security`
- `@openmeta/database`
- `@openmeta/fields` (composer dep for downstream consumers; no direct field engine in kernel)

Must **not** depend on:

- `@openmeta/api`
- `@openmeta/admin`
- `@openmeta/builder`
- `@openmeta/wordpress`
- `@openmeta/ui`

---

## Testing

PHPUnit under `packages/rest/tests/`. Boot via `RestTestCase` with Validation + Security + Database + Rest providers.

```bash
php composer.phar test:rest
```
