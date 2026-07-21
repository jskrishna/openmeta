---
title: "{{ Package Name }}"
description: "{{ One-sentence summary of the package. }}"
package: "{{ package-slug }}"
category: packages
version: next
status: draft
last_updated: "{{ YYYY-MM-DD }}"
tags: ["{{ tag }}"]
related_documents:
  - "../../packages/{{ package-slug }}/README.md"
  - "../../packages/{{ package-slug }}/SPEC.md"
---

# {{ Package Name }}

> {{ One-line description. }}

Authoritative reference: `packages/{{ package-slug }}/README.md` and its `SPEC.md`.

## Overview

{{ What the package does and where it sits in the dependency graph. }}

## Installation

```bash
composer require openmeta/{{ package-slug }}
```

## Usage

```php
// {{ Minimal, runnable example. }}
```

## Public API

{{ Link to the generated API reference and list the key entry points. }}

## Related

- {{ Related package or tutorial. }}
