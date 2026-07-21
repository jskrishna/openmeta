# scripts/

> Pre-alpha contract — implement against this document, not ad-hoc assumptions.

---

## Purpose

Hold maintainer automation scripts for day-to-day repository tasks (checks, bumps, one-offs) that are not product CLI features.

---

## Responsibilities

- Release helpers and version bumps
- Documentation / link checks
- Local maintenance one-offs

Distinct from `tools/` (structured generators and release tooling packages) and `bin/` (product CLI entrypoints).

---

## Public APIs

- Script entry filenames under `scripts/` (documented per script header)
- Expected inputs via env vars / argv, documented in each script

---

## Dependencies

- Shell / PHP / Node as declared per script
- May call into `packages/*` or `tools/*` when needed

Must not become a second application layer.

---

## Extension Points

- Generally none — add new scripts via PR with a short header comment
- Prefer `tools/` if the utility needs a reusable package structure

---

## Folder Structure

```text
scripts/
├── README.md
└── ...   # individual automation scripts
```
