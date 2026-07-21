---
title: Fields
description: The Field Engine — registry, types, validation, storage, rendering.
package: fields
category: packages
version: next
status: stable
last_updated: 2026-07-21
tags: [fields, package]
---

# Fields

The Field Engine — the heart of content modeling: a field registry and factory,
base field contracts, built-in types, groups/conditions, validation bridge, and
storage/rendering contracts.

Authoritative reference: [`packages/fields/README.md`](../../packages/fields/README.md)
· [`SPEC.md`](../../packages/fields/SPEC.md).

## Example

```php
use OpenMeta\Fields\Registry\FieldRegistry;

/** @var FieldRegistry $fields */
$fields->register('star', App\Fields\Star::class);
```

Scaffold a field: `php bin/openmeta make:field Star` (see the
[example](../examples/create-a-field.md)).

## Extension points

Register field types through the documented registry — extensions never patch
internals.

## Related

- [validation package](./validation.md) · [database package](./database.md)
- [ADR-0005](../adr/ADR-0005-field-architecture.md)
