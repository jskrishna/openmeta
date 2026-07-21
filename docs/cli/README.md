# CLI

The `openmeta` console is a framework-aware, platform-independent CLI over the
Core container. Full reference: [`packages/cli`](../../packages/cli/README.md).

```bash
php bin/openmeta list          # all commands
php bin/openmeta doctor        # environment diagnostics
php bin/openmeta make:field Star
php bin/openmeta docs:build
```

- Command registry, discovery, input parsing, output formatting, prompts, tasks.
- Third parties add commands via `CommandProviderInterface` (see the
  [generator](../../packages/generator/README.md) and
  [docgen](../../packages/docgen/README.md) packages, which mount `make:*` and
  `docs:*`).

See also: [Tutorial — Using the CLI](../tutorials/getting-started.md).
