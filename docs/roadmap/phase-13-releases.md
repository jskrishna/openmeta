# Phase 13 — Releases

> Authoritative product version train from Core → Stable.

Contract: [release-milestones.md](./release-milestones.md) · Process: [release-process.md](../development/release-process.md) · Changelog: [CHANGELOG.md](../../CHANGELOG.md)

---

## Train

```text
v0.1.0-alpha   Core
      ↓
v0.2.0-alpha   Support
      ↓
v0.3.0-alpha   Validation
      ↓
v0.4.0-alpha   Security
      ↓
v0.5.0-alpha   Database
      ↓
v0.6.0-alpha   Field Engine
      ↓
v0.7.0-alpha   REST API
      ↓
v0.8.0-alpha   Admin
      ↓
v0.9.0-beta    Builder
      ↓
v1.0.0         Stable
```

---

## Status

| Version | Theme | Status |
| ------- | ----- | ------ |
| v0.1.0-alpha | Core | ✅ |
| v0.2.0-alpha | Support | ✅ |
| v0.3.0-alpha | Validation | ✅ |
| v0.4.0-alpha | Security | ✅ |
| v0.5.0-alpha | Database | ✅ |
| v0.6.0-alpha | Field Engine | ✅ |
| v0.7.0-alpha | REST API | ✅ |
| v0.8.0-alpha | Admin (+ UI) | ✅ |
| v0.9.0-beta | Builder (+ WordPress) | ✅ |
| v1.0.0 | Stable | ⏳ next |

---

## Deliverables (this phase)

- [x] Release train documented (`release-milestones.md`)
- [x] Package `composer.json` versions aligned to train
- [x] Root `CHANGELOG.md` entries per train
- [x] GitHub milestone titles mapped (`.github/MILESTONES.md`)
- [x] Plugin version tip = `v0.9.0-beta` until Stable
- [ ] Tag / publish **v1.0.0** when Stable exit criteria pass

---

## Verify before tagging

```bash
composer ci
composer test:phase12
```

Then: CHANGELOG section present → GitHub milestone closed → annotated git tag → GitHub Release.
