# Cursor AI Workflow

> Agents must follow this cycle for **every phase**. Never start with direct code generation.

Authoritative Cursor rule: [`.cursor/rules/phase-workflow.mdc`](../../.cursor/rules/phase-workflow.mdc) (`alwaysApply: true`).

---

## Cycle

```text
1. Read relevant docs
        ↓
2. Review related ADRs
        ↓
3. Generate architecture
        ↓
4. Implement code
        ↓
5. Generate unit tests
        ↓
6. Run PHPStan
        ↓
7. Run PHPCS
        ↓
8. Run PHPUnit
        ↓
9. Fix issues
        ↓
10. Update docs if needed
        ↓
11. Commit (when the user asks)
```

---

## Doc entry points

| Step | Where |
| ---- | ----- |
| Specs | `packages/<name>/SPEC.md`, `packages/BLUEPRINTS.md` |
| Testing | `packages/TESTING.md` (Phase 12 five layers) |
| ADRs | `docs/adr/` |
| Roadmap / releases | `docs/roadmap/`, `ROADMAP.md` |
| CI | `composer ci` (= PHPStan → PHPCS → PHPUnit) |

---

## Example prompt to the agent

```text
Phase N — <package>
Follow the Cursor AI workflow rule: docs → ADRs → architecture → implement → tests → ci → docs → commit when I ask.
```
