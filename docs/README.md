# Documentation

The single source of truth for OpenMeta developers, contributors, maintainers,
and extension authors. Documentation is **modular, versioned, and validated**
(`php bin/openmeta docs:validate`).

## Standards (read before contributing)

| Document | Defines |
| -------- | ------- |
| [STYLE_GUIDE.md](./STYLE_GUIDE.md) | Voice, naming, headings, code, images, links |
| [FRONTMATTER.md](./FRONTMATTER.md) | Page metadata specification |
| [DIAGRAMS.md](./DIAGRAMS.md) | Mermaid diagram standards |
| [VERSIONING.md](./VERSIONING.md) | Doc versioning (dev/alpha/beta/stable/LTS) |
| [SEARCH.md](./SEARCH.md) | Searchable metadata & facets |
| [TEMPLATES.md](./TEMPLATES.md) | Reusable page templates |
| [GOVERNANCE.md](./GOVERNANCE.md) | Ownership, review, deprecation, archiving |
| [CONTRIBUTING.md](./CONTRIBUTING.md) | How to contribute documentation |

## Sections

| Section | Purpose |
| ------- | ------- |
| [getting-started/](./getting-started/) | Install, quick start, first project |
| [concepts/](./concepts/) | Core ideas — the "why" |
| [architecture/](./architecture/) | Structure, bootstrap, DI, patterns |
| [packages/](./packages/) | Every package at a glance |
| [api/](./api/) | API surfaces & conventions |
| [reference/](./reference/) | Generated API reference, changelog, policies |
| [examples/](./examples/) | Runnable snippets |
| [recipes/](./recipes/) | Short how-tos |
| [tutorials/](./tutorials/) | Step-by-step learning |
| [release/](./release/) | Release notes & migration guides |
| [adr/](./adr/) | Architecture Decision Records |
| [assets/](./assets/) | Images, diagrams, logos, icons, templates |

### Domain sections

| Section | Purpose |
| ------- | ------- |
| [core/](./core/) · [database/](./database/) · [fields/](./fields/) | Domain model, storage, fields |
| [rest/](./rest/) · [graphql/](./graphql/) · [ui/](./ui/) · [builder/](./builder/) | API & UI |
| [cli/](./cli/) · [extensions/](./extensions/) | Tooling & ecosystem |
| [wordpress/](./wordpress/) | WordPress integration |
| [security/](./security/) · [testing/](./testing/) · [development/](./development/) | Quality & process |
| [vision/](./vision/) · [guides/](./guides/) · [roadmap/](./roadmap/) | Direction & how-tos |

## Generated artefacts

Produced by `php bin/openmeta docs:build` — never hand-edit:

- API reference → `reference/api/`
- Package index → `packages/README.md`
- Search index → `assets/search-index.json`
- Sitemap → `assets/sitemap.xml`
- Changelog → `reference/changelog.md`

## Root documents

- [ARCHITECTURE.md](../ARCHITECTURE.md) · [TECH_STACK.md](../TECH_STACK.md) · [FEATURES.md](../FEATURES.md)
- [ROADMAP.md](../ROADMAP.md) · [CONTRIBUTING.md](../CONTRIBUTING.md) · [SECURITY.md](../SECURITY.md)
