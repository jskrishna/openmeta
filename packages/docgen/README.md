# `@openmeta/docgen`

> **Documentation platform.** Generates API + package docs, a search index, a sitemap, and a changelog, and **validates** the docs tree (broken links, markdown, empty code blocks) — with `docs:*` CLI commands.

**Status:** ✅ Phase 17 · **v0.15.0-beta**
**Blueprint:** [SPEC.md](./SPEC.md)

Depends only on **Core, Support, CLI** (an outer dev tool). Mounts `docs:*` into the console, composing with the CLI. **No runtime package depends on it.**

```bash
composer test:docgen
composer ci

# via the repo console (full framework):
php bin/openmeta docs:build       # API + packages + search index + sitemap + changelog
php bin/openmeta docs:api         # just the API reference
php bin/openmeta docs:validate    # links + markdown + code blocks
```

## Public API

```text
DocumentationManager (façade)  build() / validate() / generateApi() / generateSearchIndex() / generateSitemap()
ApiScanner + ApiDocRenderer    reflect public types → Markdown
DocValidator                   LinkValidator + MarkdownLinter
```

## Spine

```text
Markdown parsing → discovery → validation (links, lint)
  → API scan/render → package index → search index → sitemap → changelog → manager → docs:* CLI
```

## What it produces

- **API reference** — one Markdown page per package from reflected public types (`docs/reference/api/`).
- **Package index** — from `packages/*/composer.json`.
- **Search index** — `docs/assets/search-index.json` (title/path/headings/tags; filterable).
- **Sitemap** — `docs/assets/sitemap.xml`.
- **Changelog page** — from the root `CHANGELOG.md`.

## Validation

`docs:validate` fails on broken relative links, missing titles, and empty code
blocks. The Markdown scanner is **fence-length aware**, so 4-backtick demo files
(with inner 3-backtick blocks) are handled correctly.

## Extensibility

The scanner, renderers, and validators are separate, injectable units; add stub
paths, formatters, or generators without modifying framework code.

## Docs

See [docs/README.md](./docs/README.md).
