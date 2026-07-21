# Config/

Configuration repository + file loading.

## Files

| Path | Role |
| ---- | ---- |
| `ConfigRepository.php` | Dot-notation get / set / has / all / merge |
| `ConfigLoader.php` | Load `*.php` files from a directory |
| `Repository.php` | Deprecated alias of `ConfigRepository` |

## Default config (`packages/core/config/`)

| File | Key prefix | Notes |
| ---- | ---------- | ----- |
| `app.php` | `app.*` | name, env, version, debug |
| `database.php` | `database.*` | Placeholder — no DB in Core Bootstrap |
| `api.php` | `api.*` | Placeholder — no API in Core Bootstrap |

Runtime overrides merge over these defaults via `ConfigRepository::load($path, $overrides)` / `Bootstrapper::boot($overrides)`.
