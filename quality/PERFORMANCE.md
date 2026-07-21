# OpenMeta — Performance & Benchmarking

A dependency-free benchmark suite ([benchmarks/run.php](./benchmarks/run.php))
measures the hot paths and writes a machine-readable report to
`quality/reports/benchmarks.json`.

```bash
composer bench
```

## What is measured

For each benchmark: **average time per iteration (ms)**, **memory delta (KB)**,
and **process peak memory (MB)**.

| Benchmark | Path exercised |
| --------- | -------------- |
| `framework.boot` | Full framework bootstrap (all 14 providers) |
| `container.resolve` | Service-container resolution |
| `fields.register_100` | Field Engine registration throughput |
| `validation.run` | Validation rule engine |
| `graphql.schema_build` | GraphQL schema assemble + validate |
| `graphql.execute` | GraphQL named-operation execution |
| `serialization.json` | Large-dataset JSON serialization |
| `support.str` | String helpers (studly/snake) |

Additional categories (REST route resolution, query-builder execution, Builder
schema) follow the **same harness** — add a `bench(...)` entry to
[benchmarks/run.php](./benchmarks/run.php); no framework code changes required.

## Interpreting results

- Benchmarks are **relative** — run on the same machine/PHP before and after a
  change to spot regressions. The CI `benchmarks` workflow uploads the JSON
  report as an artifact per run.
- Prefer comparing `avg_ms` and `peak_mb` across commits over absolute numbers.

## Performance principles (enforced by review)

- Lazy loading of heavy modules; avoid work at boot that a request doesn't need.
- No N+1 in field/relation loading (batch loaders in `packages/database`).
- Cache reads where safe; keep the container hot path allocation-light.
- Every package's SPEC declares a **Performance** section and, where relevant, a
  `tests/Performance` budget test.
