# `@openmeta/core`

> Milestone **v0.1.0-alpha** — core spine only.

---

## Spine

```text
Application
 ↓
Container
 ↓
Service Provider
 ↓
Kernel
 ↓
Bootstrap
```

**In scope:** Application, Container, Service Provider, Kernel, Bootstrap  
**Out of scope:** Database, Fields, REST, GraphQL, Admin, Builder

---

## Boot

```php
use OpenMeta\Core\Bootstrap\Bootstrap;

$app = Bootstrap::init([
    MyServiceProvider::class,
]);

$app->isBooted();      // true
$app->container();     // Container
$app->get(Some::class);
```

`Bootstrap::init()` builds the container, creates the kernel + application, registers/boots providers, returns `Application`.

---

## Public APIs

| API | Location |
| --- | -------- |
| `Bootstrap::init(array $providers): Application` | `Bootstrap\Bootstrap` |
| `Bootstrap::VERSION` | `0.1.0-alpha` |
| `Application` | `Application\Application` |
| `Container::bind / singleton / instance / get / has` | `Container\Container` |
| `Kernel::boot / isBooted / container / addProvider` | `Kernel\Kernel` |
| `ServiceProviderInterface::register / boot` | `ServiceProvider\ServiceProviderInterface` |

Namespace: `OpenMeta\Core\`

---

## Dependencies

- PHP 8.3+
- No other OpenMeta packages
- WordPress not required for this spine

---

## Smoke test

```bash
composer install
composer test:core
```
