# REST package docs

`@openmeta/rest` is the **WordPress-independent** HTTP layer. It provides routing, middleware, and response envelopes consumed by `@openmeta/api` and bridges.

- **Router** — register routes, groups, middleware, permissions
- **RestKernel** — dispatch pipeline with events and exception mapping
- **Contracts** — swap authentication and transformers without coupling to WP

See [SPEC](../SPEC.md) for responsibilities and dependency rules.
