# Documentation Templates

Start every new page from a template so structure and frontmatter stay
consistent. Templates live in [`assets/templates/`](./assets/templates/).

Related: [STYLE_GUIDE.md](./STYLE_GUIDE.md) · [FRONTMATTER.md](./FRONTMATTER.md).

---

## Catalogue

| Template | Use for | File |
| -------- | ------- | ---- |
| Package documentation | A package's guide page | [package.md](./assets/templates/package.md) |
| Tutorial | Step-by-step learning | [tutorial.md](./assets/templates/tutorial.md) |
| Example | A focused, runnable snippet | [example.md](./assets/templates/example.md) |
| Recipe | A short how-to | [recipe.md](./assets/templates/recipe.md) |
| Reference page | Reference material | [reference-page.md](./assets/templates/reference-page.md) |
| API overview | A package's API landing page | [api-overview.md](./assets/templates/api-overview.md) |
| Architecture page | A design/architecture topic | [architecture-page.md](./assets/templates/architecture-page.md) |
| ADR | Architecture Decision Record | [adr.md](./assets/templates/adr.md) |
| Release notes | Per-version notes | [release-notes.md](./assets/templates/release-notes.md) |
| Migration guide | Breaking-change upgrade guide | [migration-guide.md](./assets/templates/migration-guide.md) |

## How to use

1. Copy the relevant template into the correct folder.
2. Fill in the frontmatter (see [FRONTMATTER.md](./FRONTMATTER.md)); the H1 must
   match `title`.
3. Replace every `{{ placeholder }}` — leave none behind.
4. Run `php bin/openmeta docs:validate` before opening a PR.

> **Note:** Templates use `{{ placeholder }}` markers so a copied page fails
> validation until every placeholder is replaced.
