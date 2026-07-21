# `@openmeta/wordpress`

> WordPress **adapter** — thin bridges for plugin lifecycle, meta, REST, hooks, capabilities, assets, and localization.

**Status:** ✅ `v0.8.0-alpha` · [SPEC](./SPEC.md) · [docs](./docs/README.md)

Entry file: repo-root [`openmeta.php`](../../openmeta.php). Domain logic stays in other packages; this package only bridges WP lifecycle.

```bash
php composer.phar test:wordpress
vendor/bin/phpstan analyse packages/wordpress --memory-limit=1G
vendor/bin/phpcs packages/wordpress
```

---

## Exit criteria (Phase 09)

| Criterion | Status |
| --------- | ------ |
| Plugin bootstrap + service registration | ✅ `Plugin`, `Providers/WordpressServiceProvider` |
| Hook / Filter managers + event bridges | ✅ `HookManager`, `FilterManager`, `ActionBridge`, `FilterBridge` |
| REST adapter (no duplicate routing) | ✅ `RestBridge`, `FrameworkRestBridge` |
| Meta storage → `FieldStorageInterface` | ✅ `WordPressFieldStorage` + meta adapters |
| CPT / Taxonomy / User / Settings adapters | ✅ |
| Capability + Nonce bridges | ✅ Security contracts only |
| Assets + Localization | ✅ |
| Lifecycle + Core events | ✅ activate/deactivate/upgrade + event dispatch |
| No business logic / no field engine | ✅ |
| PHPUnit / PHPStan / PHPCS | ✅ |
| Dependency rules + docs | ✅ |

---

## Principle

**WordPress is an adapter, not the framework.** Core packages never depend on this package.

```text
Bootstrap → Hooks/Filters → Meta → CPT/Taxonomy → Users/Settings
    → Assets/Localization → Lifecycle/Events → REST
```

## Must not

- Admin UI HTML, Builder canvas, field engine, business logic, raw `$wpdb`
