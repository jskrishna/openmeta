# GitHub milestones & project boards

Every OpenMeta **release train** ships behind a **GitHub milestone**. Package coding order still applies inside each train.

Authoritative version plan: [docs/roadmap/release-milestones.md](../docs/roadmap/release-milestones.md) · Phase 15: [docs/roadmap/phase-15-releases.md](../docs/roadmap/phase-15-releases.md) · [ADR-0024](../docs/adr/ADR-0024-post-rest-phase-order.md).

## Rule

| Release | GitHub milestone (suggested title) | Package focus |
| ------- | ---------------------------------- | ------------- |
| `v0.1.0-alpha` | `v0.1.0-alpha – Core` | `core` |
| `v0.2.0-alpha` | `v0.2.0-alpha – Support` | `support` |
| `v0.3.0-alpha` | `v0.3.0-alpha – Validation` | `validation` |
| `v0.4.0-alpha` | `v0.4.0-alpha – Security` | `security` |
| `v0.5.0-alpha` | `v0.5.0-alpha – Database` | `database` |
| `v0.6.0-alpha` | `v0.6.0-alpha – Field Engine` | `fields` |
| `v0.7.0-alpha` | `v0.7.0-alpha – REST` | `rest` (+ `api`) |
| `v0.8.0-alpha` | `v0.8.0-alpha – WordPress Adapter` | `wordpress` |
| `v0.9.0-alpha` | `v0.9.0-alpha – Admin` | `ui`, `admin` |
| `v0.10.0-beta` | `v0.10.0-beta – Builder` | `builder` |
| `v0.11.0-beta` | `v0.11.0-beta – GraphQL` | GraphQL / API mount |
| `v0.12.0-beta` | `v0.12.0-beta – CLI` | CLI package |
| `v1.0.0` | `v1.0.0 – Stable` | Production freeze |

Optional: open **per-package** milestones under a train when the board gets noisy — still close them before the version train exits.

## Recommended issue set (template)

For each package slice inside a train:

1. Contracts / public API surface  
2. Primary subsystem implementation  
3. Configuration (if any)  
4. Service provider wiring into Core  
5. Testing gate (Unit → Integration → WordPress → Performance → Security)  
6. Docs + CI green  

Close issues when merged; close the **version** milestone when the train’s exit criteria pass.

## Project board columns (suggested)

```text
Backlog → Ready → In progress → Review → Done
```
