# GitHub milestones & project boards

Every OpenMeta **release train** ships behind a **GitHub milestone**. Package coding order still applies inside each train.

Authoritative version plan: [docs/roadmap/release-milestones.md](../docs/roadmap/release-milestones.md) · Phase 13: [docs/roadmap/phase-13-releases.md](../docs/roadmap/phase-13-releases.md).

## Rule

| Release | GitHub milestone (suggested title) | Package focus |
| ------- | ---------------------------------- | ------------- |
| `v0.1.0-alpha` | `v0.1.0-alpha – Core` | `core` |
| `v0.2.0-alpha` | `v0.2.0-alpha – Support` | `support` |
| `v0.3.0-alpha` | `v0.3.0-alpha – Validation` | `validation` |
| `v0.4.0-alpha` | `v0.4.0-alpha – Security` | `security` |
| `v0.5.0-alpha` | `v0.5.0-alpha – Database` | `database` |
| `v0.6.0-alpha` | `v0.6.0-alpha – Field Engine` | `fields` |
| `v0.7.0-alpha` | `v0.7.0-alpha – REST API` | `api` |
| `v0.8.0-alpha` | `v0.8.0-alpha – Admin` | `ui`, `admin` |
| `v0.9.0-beta` | `v0.9.0-beta – Builder` | `builder`, `wordpress` |
| `v1.0.0` | `v1.0.0 – Stable` | Production freeze |

Optional: open **per-package** milestones under a train when the board gets noisy — still close them before the version train exits.

## Recommended issue set (template)

For each package slice inside a train:

1. Contracts / public API surface  
2. Primary subsystem implementation  
3. Configuration (if any)  
4. Service provider wiring into Core  
5. Phase 12 tests (Unit → Integration → WordPress → Performance → Security)  
6. Docs + CI green  

Close issues when merged; close the **version** milestone when the train’s exit criteria pass.

## Project board columns (suggested)

```text
Backlog → Ready → In progress → Review → Done
```

Filter the board by milestone so trains do not mix.

## Release train (summary)

```text
v0.1.0-alpha  Core ✅
      ↓
v0.2.0-alpha  Support ✅
      ↓
v0.3.0-alpha  Validation ✅
      ↓
v0.4.0-alpha  Security ✅
      ↓
v0.5.0-alpha  Database ✅
      ↓
v0.6.0-alpha  Field Engine ✅
      ↓
v0.7.0-alpha  REST API ✅
      ↓
v0.8.0-alpha  Admin ✅
      ↓
v0.9.0-beta   Builder ✅
      ↓
v1.0.0        Stable ⏳
```

## Live tracking

| Resource | URL |
| -------- | --- |
| Milestone (Core, closed) | https://github.com/jskrishna/openmeta/milestone/1 |
| Project board | https://github.com/users/jskrishna/projects/2 |

Rename or create milestones #2+ to match the Phase 13 titles above when cutting each train.
