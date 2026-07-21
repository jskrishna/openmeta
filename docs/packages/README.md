# Packages

Developer guides for every OpenMeta package. Each guide orients you and links to
the authoritative `README.md` + `SPEC.md` in the package itself. A generated
table (name/version/description) is produced at `reference/packages.md` by
`php bin/openmeta docs:build`.

Install everything with the [`framework`](./framework.md) meta package, or à la
carte.

## Foundation

- [core](./core.md) — bootstrap, container, kernel, events, contracts
- [support](./support.md) — helpers & utilities
- [validation](./validation.md) — rules, validator, results
- [security](./security.md) — permissions, gate, nonces
- [database](./database.md) — DAL: connections, schema, repositories
- [fields](./fields.md) — field registry, types, lifecycle

## API & UI

- [rest](./rest.md) — framework HTTP
- [api](./api.md) — application REST surface
- [graphql](./graphql.md) — GraphQL abstraction layer
- [ui](./ui.md) — UI component library
- [admin](./admin.md) — admin framework
- [builder](./builder.md) — visual builder
- [wordpress](./wordpress.md) — WordPress adapter

## Ecosystem & tooling

- [extensions](./extensions.md) — Extension SDK
- [cli](./cli.md) — console & developer tools
- [generator](./generator.md) — code scaffolding
- [docgen](./docgen.md) — documentation platform
- [framework](./framework.md) — batteries-included meta package

## Every package guide includes

Purpose · responsibilities · public API · an example · extension points ·
related docs — per the [documentation standards](../README.md).
