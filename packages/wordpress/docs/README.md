# WordPress adapter docs

The `@openmeta/wordpress` package is a **thin adapter** between WordPress APIs and OpenMeta framework packages.

- Full contract: [../SPEC.md](../SPEC.md)
- Package overview: [../README.md](../README.md)

## Architecture

```text
openmeta.php → Plugin::boot()
    → Providers/WordpressServiceProvider
    → Runtime (Native | Array for tests)
    → Bridges: hooks, meta, REST, caps, assets, lifecycle
```

Domain packages (Fields, Security, Rest, Api) stay host-independent. This package **mounts** them on WordPress — it does not reimplement them.

## Public hooks (stable)

| Hook / filter | When |
| ------------- | ---- |
| `openmeta_ready` | After provider boot |
| `openmeta_activate` / `openmeta_deactivate` | Plugin lifecycle |
| `openmeta_config` | Before container bootstrap |
| `openmeta_rest_namespace` | REST mount namespace |
| `openmeta_menu_capability` | Admin menu cap mapping |

## Tests

```bash
php composer.phar test:wordpress
```

Uses `ArrayWordPressRuntime` in CI — no WordPress install required.
