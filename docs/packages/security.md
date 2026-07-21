---
title: Security
description: Permissions, capabilities, the gate, nonces, sanitization, escaping.
package: security
category: packages
version: next
status: stable
last_updated: 2026-07-21
tags: [security, package, authorization]
---

# Security

Permissions and capabilities, the authorization `Gate`, nonces/CSRF,
sanitization, escaping, and hashing/tokens. Independent of any UI.

Authoritative reference: [`packages/security/README.md`](../../packages/security/README.md)
· [`SPEC.md`](../../packages/security/SPEC.md).

## Example

```php
use OpenMeta\Security\Contracts\GateInterface;

/** @var GateInterface $gate */
if ($gate->can('manage_fields')) {
    // authorized
}
$gate->authorize('manage_fields'); // throws if denied
```

The [GraphQL](./graphql.md) layer and others reuse this gate rather than
re-implementing permission logic.

## Related

- [validation package](./validation.md)
- [ADR-0011](../adr/ADR-0011-permission-model.md) · [ADR-0016](../adr/ADR-0016-security-model.md)
