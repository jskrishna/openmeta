# Container/

Dependency injection container — framework heart. Implements `Contracts\ContainerInterface`.

## Supported

| Feature | API |
| ------- | --- |
| Bind | `bind($id, $concrete, $shared = false)` |
| Singleton | `singleton($id, $concrete)` |
| Instance | `instance($id, $object)` |
| Resolve | `resolve($id)` / `get($id)` |
| Aliases | `alias($abstract, $alias)` |

## Future (not implemented)

- Auto-resolution (constructor injection via reflection)
- Deferred services (lazy provider loading)

## Code

- `Container.php`
