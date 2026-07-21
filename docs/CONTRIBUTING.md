# Contributing to the Documentation

Thank you for improving OpenMeta's docs. This page covers **documentation**
contributions; for code, see the root [CONTRIBUTING.md](../CONTRIBUTING.md).

---

## Before you start

Read the standards — they are short and they keep the docs consistent:

- [Style Guide](./STYLE_GUIDE.md) — voice, headings, code, links
- [Frontmatter](./FRONTMATTER.md) — page metadata
- [Diagrams](./DIAGRAMS.md) — Mermaid standards
- [Templates](./TEMPLATES.md) — start from a template
- [Governance](./GOVERNANCE.md) — ownership, review, deprecation

## Workflow

1. Pick the right folder (see the [docs map](./README.md)).
2. Copy a [template](./TEMPLATES.md) and fill in the frontmatter + body.
3. Write runnable examples that follow the coding standards.
4. Validate locally:

```bash
php bin/openmeta docs:validate
```

5. If you touched public behavior in code, update the matching page in the
   **same** pull request (Documentation First).
6. Open a PR; CI runs `docs:build` + `docs:validate`; an area owner reviews.

## Do

- One topic per page; link instead of duplicating.
- Use relative links to real files; run the validator.
- Keep `last_updated` current (absolute date).

## Don't

- Don't hand-edit generated files (`reference/api/`, `assets/search-index.json`,
  the sitemap, the changelog) — regenerate with `php bin/openmeta docs:build`.
- Don't commit screenshots of diagrams — use [Mermaid](./DIAGRAMS.md).
- Don't leave `{{ placeholders }}` or empty code fences.
