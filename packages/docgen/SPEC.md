# SPEC — `@openmeta/docgen`

> **Implementation contract.** Implement against this document. [README](./README.md) is the short overview.

**Status:** ✅ Phase 17 · `v0.15.0-beta` (Documentation Platform — [ADR-0028](../../docs/adr/ADR-0028-final-release-tail.md))

---

## Purpose

Make the documentation the single source of truth for developers, contributors, maintainers, and extension authors. Generate API + package docs, a search index, a sitemap, and a changelog from the codebase; validate the docs tree (links, markdown, code blocks); and expose it all as `docs:*` CLI commands. Composes with the CLI; holds no business logic.

Outer dev tool: depends only on **Core, Support, CLI**. **No runtime package may depend on it.**

## Component map

```text
Support (markdown, paths, discovery) → Validation → Api → Packages → Search → Sitemap → Changelog → Manager → CLI
```

## Support
`MarkdownDocument` (fence-length-aware parse → title/headings/tags/links/code), `PathNormalizer` (resolve/normalize relative paths), `DocDiscovery` (scan `*.md`).

## Validation
`LinkValidator` (broken relative links; external/anchor skipped), `MarkdownLinter` (missing title, empty code block), `DocValidator` (`DocValidatorInterface`) orchestrates. Produces `ValidationReport` of `ValidationIssue`/`IssueType`.

## Api
`ApiScanner` (reflect a package `src/` into `TypeDoc`/`MethodDoc`, summaries from PHPDoc), `ApiDocRenderer` (Markdown pages + index), `ApiDocGenerator` (write per-package pages + index to `apiPath`).

## Packages / Search / Sitemap / Changelog
`PackageDocGenerator` (index from `composer.json`), `SearchIndexGenerator` (JSON index), `SitemapGenerator` (XML), `ChangelogGenerator` (from `CHANGELOG.md`).

## Manager
`DocumentationManager` — public façade: `pages()`, `validate()`, `generateApi()`, `generatePackages()`, `generateSearchIndex()`, `generateSitemap()`, `generateChangelog()`, `build()`.

## CLI
`DocsValidateCommand` (`docs:validate`), `DocsApiCommand` (`docs:api`), `DocsBuildCommand` (`docs:build`); `DocsCommandProvider`; `DocgenServiceProvider` mounts them into the CLI registry when present.

## Public Contracts (package index)

`DocumentationManager` · `DocValidatorInterface` · `ApiScanner` / `ApiDocRenderer` · the `Model\*` value objects.

## Folder Structure

```text
packages/docgen/src/
  Api/            ApiScanner, ApiDocRenderer, ApiDocGenerator
  Changelog/      ChangelogGenerator
  Cli/            Docs{Validate,Api,Build}Command, DocsCommandProvider
  Configuration/  DocgenConfiguration
  Contracts/      DocValidatorInterface
  Exceptions/     DocgenException
  Manager/        DocumentationManager
  Model/          DocPage, TypeDoc, MethodDoc, ValidationIssue/Report, IssueType
  Packages/       PackageDocGenerator
  Search/         SearchIndexGenerator
  Sitemap/        SitemapGenerator
  Support/        MarkdownDocument, PathNormalizer, DocDiscovery
  Validation/     LinkValidator, MarkdownLinter, DocValidator
  DocgenServiceProvider.php
```

## Dependency Rules

May depend on **Core, Support, CLI** only. **No runtime package may depend on docgen.** Generated output (Markdown) may reference any package — it is text, not a code dependency.

## Extension Points

Custom validators, renderers, stub/format processors, and generators via the injectable units and the CLI `CommandProviderInterface`.

## Testing Strategy

> Phase 16 gate:

| Layer | What to cover |
| ----- | ------------- |
| Unit | Markdown parse (incl. nested fences), path normalizer, link validator, linter, validator, API renderer/scanner, search/sitemap/changelog, package render |
| Integration | Boot CLI + docgen; `docs:*` mount; manager wired (`tests/Integration`) |
| WordPress Compatibility | **N/A** — host-agnostic dev tool |
| Performance | Linear over pages/types; no dedicated budget |

Full matrix: [TESTING.md](../TESTING.md).

## Future Scope

Architecture-diagram generation and a static website front-end (search UI, version switcher) are additive and can build on the generated search index / sitemap.
