---
title: "Migration Guide — {{ from }} → {{ to }}"
description: "Upgrade from {{ from }} to {{ to }}."
category: release
version: "{{ to }}"
status: draft
last_updated: "{{ YYYY-MM-DD }}"
tags: ["migration", "release"]
---

# Migration Guide — {{ from }} → {{ to }}

> Scope: breaking changes and the steps to adopt them.

## Breaking changes

- {{ what changed and why. }}

## Before / after

```php
// Before ({{ from }})
// {{ old code }}

// After ({{ to }})
// {{ new code }}
```

## Steps

1. {{ step }}

## Verification

```bash
composer bc:check
```
