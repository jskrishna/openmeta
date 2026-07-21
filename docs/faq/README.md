---
title: FAQ
description: Frequently asked questions about OpenMeta.
category: concepts
version: next
status: stable
last_updated: 2026-07-21
tags: [faq]
---

# FAQ

## Why OpenMeta?

It is a modern, **architecture-first** content-modeling framework for PHP with a
WordPress-first adapter — SOLID, DI, PSR, package-based, and testable. See the
[philosophy](../concepts/philosophy.md).

## How is it different from a plugin?

The domain packages are **host-independent** and boot headless; WordPress is an
adapter at the edge, not the foundation. See the
[architecture](../concepts/architecture.md).

## When should I use it?

When you need structured content modeling with clean boundaries, an extension
ecosystem, GraphQL/REST surfaces, and long-term maintainability.

## Can I use it outside WordPress?

Yes. `Framework::boot()` runs the full stack headless (CLI, tests, custom apps);
the [WordPress adapter](../packages/wordpress.md) is optional.

## How do I create an extension?

Follow [Create an Extension](../tutorials/create-an-extension.md) and the
[Extension SDK](../packages/extensions.md). Scaffold with
`php bin/openmeta make:extension Name`.

## How do I upgrade?

Follow SemVer; check the [changelog](../reference/changelog.md) and any
[migration guide](../release/README.md). Verify with `composer bc:check`.

## How do I contribute?

See the root [CONTRIBUTING.md](../../CONTRIBUTING.md) for code and the
[docs contributing guide](../CONTRIBUTING.md) for documentation.

## Related

- [Troubleshooting](../troubleshooting/README.md) · [Concepts](../concepts/README.md)
