# `@openmeta/framework`

> **The batteries-included meta package.** Aggregates every stable OpenMeta component behind a single install target — the `laravel/framework` / Symfony-meta-package pattern.

**Status:** ✅ **v0.13.0-beta**
**Blueprint:** [SPEC.md](./SPEC.md)

```bash
# Everything, one target:
composer require openmeta/framework

# …or à la carte (advanced users):
composer require openmeta/fields openmeta/database
```

## One-call bootstrap

```php
use OpenMeta\Framework\Framework;

$app = Framework::boot();                 // all providers, sensible defaults
$graphql = $app->get(\OpenMeta\GraphQL\Contracts\GraphQLManagerInterface::class);
$console = $app->get(\OpenMeta\Cli\Contracts\ConsoleApplicationInterface::class);
```

`Framework::boot()` registers all 14 package service providers on the Core
container in dependency order. Every bundled provider guards host-specific
behaviour, so it boots cleanly **headless** (CLI/tests) and **inside WordPress**.

```php
Framework::providers();                   // the ordered provider list
Framework::boot($config, $extraProviders); // override config, append providers
```

## What it aggregates

```text
core · support · validation · security · database · fields · rest · api
  · ui · admin · builder · wordpress · extensions · graphql · cli
```

## Why a meta package

- **Most developers** want the whole framework with one require and one boot.
- **Advanced users** keep the freedom to install only the components they need.
- Mirrors proven ecosystems: `laravel/framework`, Symfony meta packages.

No package depends on `framework` — it is the outermost aggregation layer.
