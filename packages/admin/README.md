# `@openmeta/admin`

> Reusable **Admin UI framework** — pages, navigation, layouts, forms, tables, dashboard widgets, and UI events.

**Status:** ✅ Phase 10 · **v0.9.0-alpha**  
**Blueprint:** [SPEC.md](./SPEC.md) · **Docs:** [docs/README.md](./docs/README.md)

Uses `@openmeta/ui` primitives + `@openmeta/security` (Gate / Nonce). No WordPress APIs, field storage, or business logic.

```bash
php composer.phar test:admin
php composer.phar ci
```

---

## Exit criteria (Phase 10)

| Criterion | Status |
| --------- | ------ |
| Admin application boot + registries | ✅ `AdminApplication` |
| Pages + navigation + layouts | ✅ `PageManager`, `NavigationManager`, `LayoutManager` |
| Components + forms + tables | ✅ `ComponentRegistry`, `FormBuilder`, `TableBuilder` |
| Dashboard / notices / modals / toolbar | ✅ |
| Theme + asset declarations | ✅ `ThemeManager`, `AssetRegistry` |
| Security integration (Gate) | ✅ |
| UI events via Core dispatcher | ✅ |
| No WP / Builder / business logic | ✅ |
| PHPUnit / PHPStan / PHPCS | ✅ |
| Docs updated | ✅ |

---

## Spine

```text
AdminApplication → Pages / Navigation / Layouts
    → Forms / Tables / Components / Dashboard
    → Notices / Modals / Toolbar / Themes / Assets
```

WordPress mounts admin via `@openmeta/wordpress` (`AdminPages` bridge) — this package never calls WP directly.
