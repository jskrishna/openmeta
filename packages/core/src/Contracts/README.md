# Contracts/

Core runtime interfaces. Define contracts here first; implementations live in sibling namespaces.

## Interfaces

| File | Role |
| ---- | ---- |
| `ApplicationInterface.php` | Booted application façade |
| `ContainerInterface.php` | DI: bind / singleton / instance / get / has |
| `ServiceProviderInterface.php` | `register()` then `boot()` |
| `KernelInterface.php` | Provider registration + boot orchestration |
| `EventDispatcherInterface.php` | Sync listen / dispatch |
| `ConfigRepositoryInterface.php` | Nested config get / set / has / all |
| `BootstrapperInterface.php` | Entrypoint `boot()` → `ApplicationInterface` |

## Backward-compatible aliases

- `Providers\ServiceProviderInterface` → extends `Contracts\ServiceProviderInterface`
- `Events\EventDispatcherInterface` → extends `Contracts\EventDispatcherInterface`
- `ServiceProvider\ServiceProviderInterface` → extends Providers (deprecated)

New code should typehint `OpenMeta\Core\Contracts\*`.
