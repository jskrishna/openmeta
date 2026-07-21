# Phase 01.5 — Cursor Rules (sabse important)

> **Iske bina Cursor kabhi kabhi architecture tod dega.**  
> Rules apply project-wide (`alwaysApply: true`). Prompts se zyada powerful.

## Layout

```text
.cursor/
├── rules/
│   ├── architecture.mdc
│   ├── coding-standards.mdc
│   ├── dependency-rules.mdc
│   ├── documentation.mdc
│   ├── testing.mdc
│   ├── wordpress.mdc
│   └── phase-workflow.mdc    # 11-step cycle (also always on)
└── context/
    ├── project.md
    └── roadmap.md
```

## Rule files

| File | Prevents |
| ---- | -------- |
| `architecture.mdc` | Layer / SPEC drift |
| `coding-standards.mdc` | PSR / DI / safety drift |
| `dependency-rules.mdc` | Upside-down package imports |
| `documentation.mdc` | Guessing without SPEC/ADR |
| `testing.mdc` | Shipping without 5-layer gate |
| `wordpress.mdc` | Multi-CMS / fat bootstrap |

## Context

| File | Purpose |
| ---- | ------- |
| `project.md` | Tiny north star (Architecture First, WP, SOLID, DI, PSR) |
| `roadmap.md` | Coding order + release train + prompt index |

## Before any PHP

1. Rules loaded (automatic)  
2. Skim `context/project.md` + `context/roadmap.md`  
3. Open matching `.ai/prompts/phase-*.md`  
4. Then: docs → ADRs → architecture → code → tests → CI  

Human doc: `docs/development/cursor-workflow.md`.
