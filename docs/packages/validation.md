---
title: Validation
description: The core validation service — rules, validator, results, messages.
package: validation
category: packages
version: next
status: stable
last_updated: 2026-07-21
tags: [validation, package]
---

# Validation

A **core shared service** (not owned by Fields or the API): a rule registry, a
validator, structured results (`ErrorBag` / `ValidationResult`), and messages.
Every downstream package reuses it — no parallel validators.

Authoritative reference: [`packages/validation/README.md`](../../packages/validation/README.md)
· [`SPEC.md`](../../packages/validation/SPEC.md).

## Example

```php
use OpenMeta\Validation\Validation;

$result = Validation::make(
    ['email' => 'a@b.com'],
    ['email' => ['required', 'email']],
)->result();

$result->passed();          // true
$result->errors()->all();   // []
```

## Extension points

Register custom rules with `Validation::extend()`.

## Related

- [security package](./security.md) · [fields package](./fields.md)
- [ADR-0012](../adr/ADR-0012-validation-strategy.md)
