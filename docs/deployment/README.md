---
title: Deployment
description: Deploying OpenMeta as a WordPress plugin or a headless application.
category: concepts
version: next
status: draft
last_updated: 2026-07-21
tags: [deployment]
---

# Deployment

OpenMeta deploys two ways: as a **WordPress plugin** or as a **headless PHP
application**.

## Requirements

- PHP **8.3+**, Composer 2.x. See [requirements](../getting-started/requirements.md).

## As a WordPress plugin

1. Install via Composer (or ship a built plugin package).
2. Activate the plugin; the [adapter](../packages/wordpress.md) bootstraps the
   framework, registers hooks/capabilities, and mounts REST.
3. Configure the app key and any connections.

## Headless

```php
use OpenMeta\Framework\Framework;

$app = Framework::boot($config);
```

The framework boots without WordPress — every adapter guards host calls with
`function_exists()`.

## Before you ship

Run the release gate (see the [release checklist](../development/release-checklist.md)):

```bash
composer release:validate
```

## Related

- [WordPress integration](../wordpress/README.md) · [Configuration](../concepts/configuration.md)
- Release automation & publishing land in **Phase 18** ([release/](../release/README.md)).
