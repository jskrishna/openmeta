# Bootstrap/

Canonical framework bootstrap sequence (no WordPress logic).

## Sequence

```text
Load Config
    ↓
Create Container
    ↓
Register Core Services
    ↓
Register Providers
    ↓
Boot Providers
    ↓
Application Ready
```

## Code

| File | Role |
| ---- | ---- |
| `Bootstrap.php` | `Bootstrap::run()` — executes the sequence |
| `Bootstrapper.php` | Alias → `Bootstrap::run()` |

```php
use OpenMeta\Core\Bootstrap\Bootstrap;

$app = Bootstrap::run($config, [$providers]);
// or Application::boot(...) / Bootstrapper::boot(...)
```
