# Providers/

Service provider system — how every OpenMeta package plugs into Core.

## Lifecycle

```text
Register
   ↓
 Boot
```

| Phase | Method | Rule |
| ----- | ------ | ---- |
| **Register** | `register(Container)` | Bind services only. Do not resolve other providers’ bindings. |
| **Boot** | `boot(Container)` | Runs after **all** `register()` calls. Safe to resolve and wire. |

The **Kernel** enforces this order for the full provider list.

## Code

| File | Role |
| ---- | ---- |
| `ServiceProvider.php` | Abstract base — extend in future packages |
| `ServiceProviderInterface.php` | Deprecated alias → `Contracts\ServiceProviderInterface` |

## How future packages register

```php
use OpenMeta\Core\Providers\ServiceProvider;
use OpenMeta\Core\Contracts\ContainerInterface;

final class DatabaseServiceProvider extends ServiceProvider
{
    public function register(ContainerInterface $container): void
    {
        // bind database services
    }

    public function boot(ContainerInterface $container): void
    {
        // start / hook after all packages registered
    }
}

// Bootstrapper::boot($config, [
//     DatabaseServiceProvider::class,
//     FieldsServiceProvider::class,
//     ApiServiceProvider::class,
// ]);
```

Canonical contract: `OpenMeta\Core\Contracts\ServiceProviderInterface`.
