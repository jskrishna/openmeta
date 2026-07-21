# Search Strategy

Search is powered by a **generated index** — no server required. The
[docgen package](../packages/docgen/README.md) builds
`assets/search-index.json` from every page's title, headings, tags, and (where
present) frontmatter.

```bash
php bin/openmeta docs:build   # (re)generates assets/search-index.json
```

Related: [FRONTMATTER.md](./FRONTMATTER.md) · [STYLE_GUIDE.md](./STYLE_GUIDE.md).

---

## Index document schema

Each entry in `search-index.json` is:

```json
{
  "title": "Create a Field",
  "path": "docs/examples/create-a-field.md",
  "headings": ["Create a Field", "Scaffold", "Register it"],
  "tags": ["fields", "example"]
}
```

The front-end (Phase 17.7/17.8) enriches ranking with frontmatter facets.

## Facets

Filter and rank results by:

| Facet | Source | Example |
| ----- | ------ | ------- |
| **Package** | frontmatter `package` | `fields`, `graphql`, `framework` |
| **Version** | frontmatter `version` | `next`, `v0.x`, `v1.x` |
| **Category** | frontmatter `category` / folder | `tutorials`, `api`, `recipes` |
| **Tags** | frontmatter `tags` / `tags:` line | `fields`, `example` |
| **Difficulty** | frontmatter `difficulty` | `beginner`, `advanced` |
| **Keywords** | title + headings | free text |

## Authoring for search

- Give every page a clear, unique **title** and a one-sentence **description**.
- Add **tags** (lowercase, reused consistently — prefer existing tags).
- Use descriptive **headings**; they are indexed and become anchors.
- Set **package**, **category**, and **version** frontmatter so facets work.

## Quality gates

- `docs:validate` keeps the corpus healthy (no broken links, valid structure),
  which keeps the index trustworthy.
- Archived/deprecated pages are excluded from the current index (see
  [VERSIONING.md](./VERSIONING.md)).
