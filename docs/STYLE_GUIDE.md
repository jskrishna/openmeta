# Documentation Style Guide

The rules every OpenMeta document follows. It exists so the docs read as one
voice, stay consistent, and validate cleanly (`php bin/openmeta docs:validate`).

Related: [FRONTMATTER.md](./FRONTMATTER.md) · [DIAGRAMS.md](./DIAGRAMS.md) ·
[TEMPLATES.md](./TEMPLATES.md) · [GOVERNANCE.md](./GOVERNANCE.md).

---

## Naming

- **Files:** `kebab-case.md` (e.g. `create-a-field.md`). Folder index pages are
  `README.md`.
- **Folders:** `kebab-case`, singular for concepts, plural for collections
  (`concepts/`, `examples/`, `tutorials/`).
- **Assets:** `kebab-case` with a descriptive prefix and, for diagrams, the
  subject (`fields-lifecycle.svg`, `container-resolution.mmd`).
- **Anchors:** rely on GitHub's auto-generated heading slugs; do not hand-author
  anchors.

## Voice

- Second person, present tense, active voice ("call `boot()`", not "the method
  should be called").
- Use they/them for a developer whose pronouns are unknown.
- Prefer short sentences. Define an acronym on first use.

## Headings

- Exactly **one H1** per page — the page title (mirrors the frontmatter `title`).
- Heading depth **max H4**. Do not skip levels (H2 → H4 is invalid).
- Sentence case ("Register a field", not "Register A Field").

## Line length

- Prose: wrap at **~100 characters** (soft limit; do not reflow mid-edit
  churn). Content inside code fences is exempt.

## Lists & tables

- Use `-` for unordered lists, `1.` for ordered.
- Tables must have a header row and alignment row; keep cells short — link out
  for detail.

## Code blocks

- Always fence with a **language identifier**: ` ```php `, ` ```bash `,
  ` ```json `, ` ```text `, ` ```mermaid `.
- **Never leave a fence empty** (the linter flags empty code blocks). Use a
  comment placeholder if illustrating structure.
- Code must be **complete and runnable**, follow the project coding standards
  (PSR-12, `declare(strict_types=1)`), and avoid pseudo-code. See
  [Code examples](#code-examples).

## Callouts

Use blockquote callouts (portable across renderers):

```text
> **Note:** context that helps but is not required.
> **Tip:** a shortcut or best practice.
> **Warning:** something that can cause data loss or breakage.
> **Deprecated:** scheduled for removal — link the replacement.
```

## Links

- **Internal links are relative** and point at real files/dirs
  (`../packages/README.md`, `./FRONTMATTER.md`). No absolute repo paths.
- Link to a folder via its `README.md` or the folder itself.
- External links use full `https://` URLs.
- Every public type mentioned should link to its
  [API reference](./reference/README.md) or package doc where practical.

## Code examples

Every example must:

1. **Compile / run** as shown (no `…` stand-ins for required code).
2. Be **complete** — imports included.
3. Have a one-line **explanation** of what it demonstrates.
4. **Reference** related docs (package, tutorial, or API page).
5. Follow the **coding standards** — never pseudo-code.

## Images

See [DIAGRAMS.md](./DIAGRAMS.md) for diagrams. For raster/vector assets:

- Prefer **SVG**; use **PNG** for screenshots.
- Store under `assets/images/` (or `assets/diagrams/`, `assets/logos/`,
  `assets/icons/`).
- Provide meaningful **alt text**; never rely on color alone (dark-mode safe).
- Keep dimensions reasonable (≤ 1600px wide) and compress before committing.

## Terminology

- "OpenMeta" (one word, capital M). "the framework", "a package", "an extension".
- "Extension SDK" = the `extensions` package. "the CLI" = the `cli` package.
- Use the exact class/interface names as they appear in code, in backticks.
