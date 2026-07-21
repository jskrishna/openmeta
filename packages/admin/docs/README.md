# Admin UI framework docs

`@openmeta/admin` is the **reusable WordPress admin UI framework** for OpenMeta modules.

- Contract: [../SPEC.md](../SPEC.md)
- Overview: [../README.md](../README.md)

## Public API

| API | Role |
| --- | ---- |
| `AdminApplication` | Boot + registries |
| `PageManager` | Register admin pages |
| `NavigationManager` | Menus, groups, breadcrumbs |
| `LayoutManager` | Full-width, sidebar, split, dashboard, wizard |
| `FormBuilder` | Sections, groups, validation, Field Engine hooks |
| `TableBuilder` | Columns, search, sort, Database pagination |
| `ComponentRegistry` | Named UI descriptors |
| `AssetRegistry` | Declarative handles for WP Asset Manager bridge |

## Events

`PageLoaded`, `ComponentRendered`, `FormSubmitted`, `TableLoaded`, `ModalOpened`

## Tests

```bash
php composer.phar test:admin
```
