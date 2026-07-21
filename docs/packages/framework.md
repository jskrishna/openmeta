---
title: Framework (meta)
description: The batteries-included meta package aggregating every component.
package: framework
category: packages
version: next
status: stable
last_updated: 2026-07-21
tags: [framework, package, meta]
---

# Framework (meta)

The batteries-included meta package: one `composer require openmeta/framework`
pulls the whole stack, and `Framework::boot()` registers every package's service
provider in dependency order. Advanced users may still install packages à la
carte.

Authoritative reference: [`packages/framework/README.md`](../../packages/framework/README.md)
· [`SPEC.md`](../../packages/framework/SPEC.md).

## Example

```php
use OpenMeta\Framework\Framework;

$app = Framework::boot();
```

## Related

- [Getting Started](../getting-started/README.md) · [Lifecycle](../concepts/lifecycle.md)
