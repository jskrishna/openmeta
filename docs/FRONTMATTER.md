# Frontmatter Specification

Every documentation page **may** begin with a YAML frontmatter block. It carries
machine-readable metadata for search, navigation, versioning, and governance.

This document defines the **specification only** — no tooling is implied. The
[search index](./SEARCH.md) and future website consume these fields.

---

## Format

A single YAML block delimited by `---`, at the very top of the file, followed by
the page's H1 title:

```text
---
title: Create a Field
description: Scaffold and register a custom field type.
package: fields
category: examples
version: v0.x
status: stable
author: OpenMeta Contributors
last_updated: 2026-07-21
tags: [fields, example]
difficulty: beginner
estimated_time: 5m
related_documents:
  - ../tutorials/getting-started.md
  - ../../packages/fields/README.md
---

# Create a Field
```

> **Note:** The frontmatter `title` and the body **H1 must match**. The H1 is the
> rendered title; the frontmatter `title` is the indexed title.

## Fields

| Field | Required | Type | Notes |
| ----- | -------- | ---- | ----- |
| `title` | ✅ | string | Matches the page H1 |
| `description` | ✅ | string | One sentence; used in search + cards |
| `package` | ◻ | string | Owning package (e.g. `fields`, `graphql`) or `framework` |
| `category` | ✅ | enum | `getting-started` · `concepts` · `architecture` · `packages` · `api` · `examples` · `tutorials` · `recipes` · `reference` · `release` |
| `version` | ✅ | string | `v0.x` · `v1.x` · `next` (see [VERSIONING.md](./VERSIONING.md)) |
| `status` | ✅ | enum | `draft` · `beta` · `stable` · `deprecated` |
| `author` | ◻ | string | Person or "OpenMeta Contributors" |
| `last_updated` | ✅ | date | `YYYY-MM-DD` (absolute, never "today") |
| `tags` | ◻ | list | Lowercase keywords for search/filtering |
| `difficulty` | ◻ | enum | `beginner` · `intermediate` · `advanced` (tutorials/examples) |
| `estimated_time` | ◻ | string | e.g. `5m`, `30m` (tutorials) |
| `related_documents` | ◻ | list | **Relative paths** to related pages |

## Rules

- Omit optional fields rather than leaving them blank.
- `related_documents` entries are validated like any relative link — they must
  resolve.
- `deprecated` pages must set `status: deprecated` and link the replacement in
  the body (see [GOVERNANCE.md](./GOVERNANCE.md)).
- Frontmatter is **additive**: a page without it is still valid, but generated
  pages (API reference) and website navigation rely on it where present.
