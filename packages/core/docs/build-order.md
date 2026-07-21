# Coding order

**Actual implementation order.** One package at a time. Implement against that package’s `SPEC.md` only.

```text
Core
    ↓
Support
    ↓
Validation
    ↓
Security
    ↓
Database
    ↓
Fields
    ↓
API
    ↓
UI
    ↓
Admin
    ↓
Builder
    ↓
Wordpress
```

| # | Package | Status | SPEC |
| - | ------- | ------ | ---- |
| 1 | **Core** | ✅ `v0.1.0-alpha` | [SPEC.md](./SPEC.md) |
| 2 | **Support** | ✅ | [../support/SPEC.md](../support/SPEC.md) |
| 3 | **Validation** | ✅ | [../validation/SPEC.md](../validation/SPEC.md) |
| 4 | **Security** | ✅ | [../security/SPEC.md](../security/SPEC.md) |
| 5 | **Database** | ✅ | [../database/SPEC.md](../database/SPEC.md) |
| 6 | **Fields** | ✅ | [../fields/SPEC.md](../fields/SPEC.md) |
| 7 | **API** | ✅ | [../api/SPEC.md](../api/SPEC.md) |
| 8 | **UI** | ✅ | [../ui/SPEC.md](../ui/SPEC.md) |
| 9 | **Admin** | ✅ | [../admin/SPEC.md](../admin/SPEC.md) |
| 10 | **Builder** | ✅ | [../builder/SPEC.md](../builder/SPEC.md) |
| 11 | **Wordpress** | ✅ `v0.9.0-beta` | [../wordpress/SPEC.md](../wordpress/SPEC.md) |

## How to build the next package

```text
1. Open packages/<name>/SPEC.md
2. Implement only what SPEC allows
3. Respect Dependency Rules + Must not
4. Wire ServiceProvider into Core bootstrap
5. Phase 10 testing gate (see below)
6. composer ci green
```

Do **not** skip ahead (e.g. Fields before Database) unless SPEC and Dependency Rules explicitly allow parallel work.

## Phase 10 — Testing gate (after every package)

```text
Unit Tests
    ↓
Integration Tests
    ↓
WordPress Compatibility Tests
    ↓
Performance Tests
```

Contract: [packages/TESTING.md](../../TESTING.md). Package is not “done” until this gate passes (or SPEC marks a layer N/A).

## Related

- [milestone-v0.1.0-alpha.md](./milestone-v0.1.0-alpha.md)
- [packages/README.md](../../README.md)
- [packages/SPEC.TEMPLATE.md](../../SPEC.TEMPLATE.md)
- [`.github/MILESTONES.md`](../../../.github/MILESTONES.md)
