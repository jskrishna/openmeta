# Documentation Governance

How documentation is owned, reviewed, deprecated, and archived — so the docs
stay accurate as the framework evolves.

Related: [CONTRIBUTING.md](./CONTRIBUTING.md) · [STYLE_GUIDE.md](./STYLE_GUIDE.md)
· [VERSIONING.md](./VERSIONING.md) · [../CONTRIBUTING.md](../CONTRIBUTING.md).

---

## Ownership

- **Package docs** (`packages/*/README.md`, `SPEC.md`, `docs/`) are owned by the
  package maintainers (see [`.github/CODEOWNERS`](../.github/CODEOWNERS)).
- **Cross-cutting docs** (`getting-started`, `concepts`, `architecture`,
  `tutorials`, standards files) are owned by the docs maintainers.
- **Generated docs** (`reference/api`, `assets/search-index.json`, sitemap,
  changelog) are owned by no one — never hand-edit; regenerate.

## Review & approval

1. Every docs change goes through a pull request.
2. CI runs `docs:build` + `docs:validate` (see
   [`.github/workflows/docs.yml`](../.github/workflows/docs.yml)); broken links,
   missing titles, and empty code blocks block merge once the gate is required.
3. At least one owner of the affected area approves.
4. Code + docs ship **together** — a change to public behavior updates the
   relevant page in the same PR (Documentation First, [ADR-0021](./adr/ADR-0021-documentation-first.md)).

## Contribution rules

- Follow the [Style Guide](./STYLE_GUIDE.md) and
  [Frontmatter spec](./FRONTMATTER.md).
- Use a [template](./TEMPLATES.md) for new pages.
- Code examples must compile and follow project standards.
- Keep one topic per page; link rather than duplicate.

## Deprecation policy

- Mark the page `status: deprecated` in frontmatter and add a
  `> **Deprecated:**` callout linking the replacement.
- Keep a deprecated page for **one minor line**, then archive it.
- Never silently delete a linked page — redirect or archive so links do not rot.

## Archiving policy

- On a stable release, snapshot the docs to a versioned path (see
  [VERSIONING.md](./VERSIONING.md)); archived pages leave the current search
  index but remain reachable under their version.
- Remove archived pages from current indexes and navigation.

## Quality cadence

- `docs:validate` runs on every PR.
- A periodic audit checks for outdated `last_updated`, deprecated content past
  its window, and orphan pages (not linked from any index).
