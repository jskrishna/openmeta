# SPEC — `@openmeta/api`

> **Implementation contract.** Implement against this document. [README](./README.md) is the short overview.

**Status:** ✅ Complete — Phase 8 / `v0.7.0-alpha`

**Consumers:** External clients, headless WordPress, integrations. Field shapes come from `@openmeta/fields` REST/GraphQL Support; this package **serves** them over HTTP.

---

## Purpose

Public API layer for OpenMeta — WordPress REST first (GraphQL as parallel mount) — with Controllers, Resources, Authentication, and Authorization sharing one stack.

---

## Module map

```text
REST
    ↓
Controllers
    ↓
Resources
    ↓
Authentication
    ↓
Authorization
```

```text
REST
    Responsibilities
        ↓
Controllers
    Responsibilities
        ↓
Resources
    Responsibilities
        ↓
Authentication
    Responsibilities
        ↓
Authorization
    Responsibilities
```

Supporting: **Routes** (inside REST), **GraphQL** mount, **Serialization**, **Errors**, **Service Provider**.

---

## REST

### Responsibilities

- Own the public REST surface: namespaces, versioning, conventions
- Register routes (method + path → Controller) under versioned namespaces
- Shared JSON rules, status codes, pagination/filter/sort conventions
- Integrate with WP REST API without dumping domain logic into WP core

### Public contracts

- Versioned REST namespace(s) (e.g. `openmeta/v1` — finalized at implementation)
- Route registration / route table (part of REST)
- REST conventions doc

### Must not

- Implement field storage or admin UI
- Skip Authentication / Authorization on mutating or sensitive reads

---

## Controllers

### Responsibilities

- Handle matched REST requests: parse input, call `fields` / `database` / domain services
- Enforce Authentication then Authorization before side effects
- Validate via `@openmeta/validation` / field validation bridges
- Return Resources or error envelopes; stay thin

### Public contracts

- Controller base / resource controller contracts

### Must not

- Run raw SQL
- Render HTML admin screens
- Embed large business rules (push to domain packages)

---

## Resources

### Responsibilities

- Transform domain entities into public API payloads
- Apply `@openmeta/fields` REST Support serializers where applicable
- Shape collections (pagination meta, links)
- Keep public DTOs stable across minor versions

### Public contracts

- Resource / serializer interfaces
- Collection + pagination envelope

### Must not

- Authorize access (Authorization)
- Mutate persistence directly

---

## Authentication

### Responsibilities

- Establish **who** is calling (WP user, application passwords, future tokens — as documented)
- Reject unauthenticated access where required
- Attach identity context for Controllers and Authorization
- Align with WordPress authentication APIs

### Public contracts

- Authenticator / guard interfaces
- Documented auth methods per endpoint class

### Must not

- Decide resource permissions (Authorization)
- Store credentials in logs or error bodies

---

## Authorization

### Responsibilities

- Decide **what** the authenticated identity may do
- Use `@openmeta/security` Permissions → Capabilities (+ Nonce when required)
- Fail closed on missing permissions
- Attach authz requirements to REST routes / Controllers metadata

### Public contracts

- Authz middleware / policy hooks for REST
- Capability / permission map for API resources

### Must not

- Reimplement the security engine (depend on `security`)
- Rely on “hidden URLs” as access control

---

## Public Contracts (package index)

| API | Class |
| --- | ----- |
| REST / Routes | `Router`, `Route`, `RouteRegistrar` (`openmeta/v1`) |
| Kernel | `OpenMeta\Api\Rest\RestKernel` |
| Controllers | `Controller`, `FieldValueController` |
| Resources | `JsonResource`, `ResourceCollection`, `FieldValueResource` |
| Authentication | `AuthenticatorInterface`, `TokenAuthenticator`, `WordPressAuthenticator` |
| Authorization | `OpenMeta\Api\Authz\Authorizer` (via `security` Gate) |
| Errors | `ApiException`, `AuthenticationException`, `AuthorizationException` |
| Provider | `OpenMeta\Api\ApiServiceProvider` |

---

## Internal Components

| Component | Location |
| --------- | -------- |
| REST (+ Routes) | `src/Rest/` |
| Controllers | `src/Rest/Controllers/` or `src/Controllers/` |
| Resources | `src/Rest/Resources/` or `src/Resources/` |
| Authentication | `src/Auth/` |
| Authorization | `src/Authz/` |
| GraphQL / Errors / Serialization | `src/GraphQL/`, `src/Errors/`, `src/Serialization/` |

---

## Folder Structure

```text
packages/api/
├── src/
│   ├── Rest/
│   │   ├── Routes/
│   │   ├── Controllers/
│   │   └── Resources/
│   ├── Auth/
│   ├── Authz/
│   ├── GraphQL/
│   ├── Serialization/
│   ├── Errors/
│   └── Contracts/
├── tests/
├── README.md
└── SPEC.md
```

---

## Dependency Rules

| Direction | Rule |
| --------- | ---- |
| Required | `core`, `fields`, `database`, `validation`, `security`, WP REST API |
| Optional | WPGraphQL runtime, `support` |
| Forbidden | Owning field types, DB schema, or admin/builder UI |
| Consumers | External clients, headless WP, integrations |

---

## Lifecycle

```text
ApiServiceProvider::register
    ↓
bind Resources, Authentication, Authorization, error factory
    ↓
ApiServiceProvider::boot
    ↓
REST: register routes → Controllers
```

Per request:

```text
REST (match route)
    ↓
Authentication (who)
    ↓
Authorization (may)
    ↓
Controllers (validate → domain)
    ↓
Resources (serialize)
    ↓
response / error envelope
```

---

## Extension Points

- Custom REST routes + Controllers
- Custom Resources / serializers
- Auth guards and Authorization policies
- GraphQL type registration (fields contracts + api mount)
- Error transformers

---

## Performance

- Paginate list Resources by default
- Avoid N+1; batch via `fields` / `database`
- Keep Controllers thin
- No stack traces in production errors

---

## Security

- Mutating routes: Authentication + Authorization (+ Nonce/token as documented)
- Validate before persistence
- Never leak internal paths, SQL, or secrets in errors

---

## Testing Strategy

| Layer | Covers |
| ----- | ------ |
| **Unit** | Resources + error envelopes; Auth failure / Authorization deny |
| **Integration** | REST Controller + Resource happy + deny paths; GraphQL resolvers when enabled |
| **WordPress compatibility** | WP REST registration / response smoke |
| **Performance** | List pagination budget |

See [packages/TESTING.md](../../TESTING.md) (Phase 10 gate).

---

## Future Scope

- OpenAPI export
- Webhooks
- API keys docs
- Never: field-type implementations or admin UI inside API
