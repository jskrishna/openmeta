---
title: Support
description: Shared helpers and utilities — Arr, Str, Collections, Filesystem, and more.
package: support
category: packages
version: next
status: stable
last_updated: 2026-07-21
tags: [support, package, utilities]
---

# Support

Pure utilities used framework-wide: `Arr`, `Str`, `Collection`, `Path`,
`Filesystem`, `Env`, `Uuid`, `Reflector`, and helper traits. Zero business logic.

Authoritative reference: [`packages/support/README.md`](../../packages/support/README.md)
· [`SPEC.md`](../../packages/support/SPEC.md).

## Example

```php
use OpenMeta\Support\Str\Str;

Str::studly('user profile'); // "UserProfile"
Str::snake('UserProfile');   // "user_profile"
```

## Related

- [core package](./core.md) · [Packages](./README.md)
