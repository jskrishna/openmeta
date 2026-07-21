# Phase 01.5 — Cursor Rules

> Project-wide Cursor rules so agents cannot quietly break architecture.

**Layout & purpose:** [`.cursor/README.md`](../../.cursor/README.md)

```text
.cursor/rules/*.mdc     → alwaysApply constraints
.cursor/context/*.md    → project + roadmap orientation
.ai/prompts/phase-*.md  → per-phase agent prompts
```

Do this **before** generating PHP for a new slice. Prompts alone are not enough.
