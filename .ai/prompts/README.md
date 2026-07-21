# `.ai/prompts`

Cursor agent prompts for each OpenMeta phase. **Never start with direct code generation** — follow the cycle inside each file and [`.cursor/rules/`](../../.cursor/rules/) (`alwaysApply` project rules).

Global context: [`.cursor/context/project.md`](../../.cursor/context/project.md) · [roadmap.md](../../.cursor/context/roadmap.md).

```text
.ai/
└── prompts/
    ├── phase-02-core-bootstrap.md
    ├── phase-03-support.md
    ├── phase-04-validation.md
    ├── phase-05-security.md
    ├── phase-06-database.md
    ├── phase-07-fields.md
    ├── phase-08-rest-api.md
    ├── phase-09-wordpress.md
    ├── phase-10-admin.md
    ├── phase-11-builder.md
    └── phase-12-testing.md
```

Usage: open / `@` the prompt file for that phase, then run the agent.
