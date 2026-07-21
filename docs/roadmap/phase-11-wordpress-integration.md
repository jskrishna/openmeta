# Phase 09 — WordPress Adapter (canonical number)

> Historical filename: `phase-11-wordpress-integration.md`. **Canonical phase = 09** ([ADR-0024](../adr/ADR-0024-post-rest-phase-order.md)).  
> Train: **`v0.8.0-alpha`**. Scope: **`packages/wordpress`** + root `openmeta.php`.

---

## Deliverables

| Component | Status |
| --------- | ------ |
| Plugin Bootstrap | ✅ Requirements + Plugin::boot / activate |
| Hooks | ✅ ActionBridge (`openmeta_*`) |
| Filters | ✅ FilterBridge (config / menu / REST ns) |
| Admin Pages | ✅ MenuRegistry → add_menu_page |
| Capabilities | ✅ Permission seed on activate |
| Gutenberg | ✅ `openmeta/field` + `openmeta/schema` blocks |
| REST mount | ⏳ Align to `@openmeta/rest` kernel (Phase 09 hardening) |

---

## Phase 09 focus (next)

- Mount framework `@openmeta/rest` onto `rest_api_init` (adapter only)
- Fail-closed bridges when WP APIs missing
- Keep domain engines out of `wordpress` / `openmeta.php`

## Verify

```bash
composer test:wordpress
composer ci
```
