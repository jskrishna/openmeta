---
title: Configuration
description: Loading and reading configuration through the config repository.
category: concepts
version: next
status: stable
last_updated: 2026-07-21
tags: [configuration, core]
---

# Configuration

Configuration is loaded once at boot into a **config repository**
(`OpenMeta\Core\Contracts\ConfigRepositoryInterface`) and read by dot path.

## Providing config at boot

```php
use OpenMeta\Core\Bootstrap\Bootstrap;

$app = Bootstrap::run([
    'app' => ['key' => 'your-secret'],
    'database' => [
        'default' => 'memory',
        'connections' => ['memory' => ['driver' => 'memory', 'prefix' => 'om_']],
    ],
], $providers);
```

Runtime values are merged over the default config files under
`packages/core/config` (`app`, `database`, `api`).

## Reading config

```php
$config = $app->config();
$driver = $config->get('database.default', 'memory');
$hasKey = $config->has('app.key');
```

## Guidelines

- Read configuration through the repository — never hard-code environment
  values in domain classes.
- Keep secrets out of committed config; supply them at boot.
- The [`framework`](../packages/framework.md) meta package supplies sensible
  headless defaults so the whole stack boots out of the box.

## Related

- [Lifecycle](./lifecycle.md) · [core package](../packages/core.md)

## Next steps

- [Lifecycle](./lifecycle.md)
