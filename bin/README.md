# bin/

> Pre-alpha contract — implement against this document, not ad-hoc assumptions.

---

## Purpose

Provide thin executable entrypoints (CLI binaries) that call into OpenMeta package logic for local development and maintainer workflows.

---

## Responsibilities

- Expose user-facing commands from the repo root
- Parse argv and delegate to package services
- Exit codes and CLI help text

Must not contain business logic — logic lives in packages.

---

## Public APIs

- CLI commands under `bin/` (names TBD in Phase 01)
- Stable exit codes and help output conventions

---

## Dependencies

- `packages/core` (bootstrap)
- Relevant domain packages per command
- PHP CLI / Composer bin links (when introduced)

---

## Extension Points

- Custom commands registered via documented CLI command providers
- Package-contributed commands discovered through the container

---

## Folder Structure

```text
bin/
├── openmeta          # primary CLI entry (planned)
├── README.md
└── ...
```
