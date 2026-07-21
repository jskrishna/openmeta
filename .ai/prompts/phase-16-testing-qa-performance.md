# OpenMeta — Phase 16: Testing, QA & Performance

You are a Principal Software Architect, QA Engineer, and Performance Engineer contributing to the OpenMeta framework.

Before writing any code, you MUST read: README.md, ARCHITECTURE.md, docs/, docs/adr/, packages/*/README.md.

Documentation is the source of truth.

---

# Goal

Build the complete Testing, QA, Benchmarking and Performance infrastructure.

No new framework features. Focus only on quality, reliability and stability.

---

# Responsibilities

Test Infrastructure · Integration Testing · End-to-End Testing · Performance Benchmarking · Static Analysis · Mutation Testing · Coding Standards · Security Scanning · Dependency Auditing · Compatibility Testing · Coverage Reports · Continuous Integration · Release Validation.

---

# Folder Structure

quality/ (benchmarks, coverage, reports, tools) · tests/ (integration, e2e, fixtures) · .github/workflows/.

---

# Testing

Unit · Integration · E2E · Regression · Smoke · Performance. Target: minimum 95% coverage.

# Performance

Benchmarks for Core, Container, Field Engine, Query Builder, REST, GraphQL, Builder. Measure memory, execution time, object creation, large-dataset handling.

# Static Analysis

PHPStan, PHPCS, PHP CS Fixer, Psalm (optional). No warnings allowed.

# Security

Composer Audit, Secret Scanning, Dependency Scanning, Security Advisories.

# Continuous Integration

GitHub Actions: Install, Lint, Static Analysis, Unit Tests, Integration Tests, Coverage, Package Validation, Build, Release Validation.

# Release Validation

Before every release: all tests pass, coverage threshold, coding standards, no dependency conflicts, composer validation, package integrity, backward compatibility.

# Compatibility

PHP 8.3+, latest WordPress, latest Composer, supported database drivers.

# Benchmark Suite

Framework Boot · Route Resolution · Field Registration · Query Execution · Serialization · Validation · GraphQL Schema · Builder Schema.

# Reports

Coverage · Performance · Benchmark · Security · QA.

---

# Deliverables

Testing infrastructure · CI workflows · Benchmark suite · QA documentation · Performance documentation · Release checklist. No new framework features.
