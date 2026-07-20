# Naming Conventions

> **Document:** Naming Conventions
> **Status:** Draft
> **Version:** Pre-Alpha

---

# Purpose

This document defines the official naming conventions used throughout the OpenMeta project.

Consistent naming improves readability, discoverability, maintainability, and onboarding for contributors.

Every new class, function, file, hook, API endpoint, and database object must follow these conventions.

---

# General Principles

Names should be:

- Clear
- Descriptive
- Predictable
- Consistent

Avoid abbreviations unless they are widely accepted.

---

# Repository

Repository Name

```text
openmeta
```

---

# PHP Namespace

Root namespace

```php
OpenMeta
```

Example

```php
OpenMeta\Core

OpenMeta\Fields

OpenMeta\Database

OpenMeta\Api

OpenMeta\Admin
```

---

# PHP Classes

Use:

PascalCase

Examples

```text
Application

FieldRegistry

FieldFactory

ValidationEngine

AssetManager

HookManager
```

Avoid

```text
fieldRegistry

field_registry

registry

manager

helper
```

---

# Interfaces

Suffix every interface with:

```text
Interface
```

Examples

```text
LoggerInterface

CacheInterface

StorageInterface

ValidatorInterface
```

---

# Traits

Suffix every trait with:

```text
Trait
```

Examples

```text
HasHooksTrait

HasAssetsTrait

HasValidationTrait
```

---

# Enums

Use singular PascalCase.

Examples

```text
FieldType

LocationType

ValidationRule
```

---

# Abstract Classes

Prefix with:

```text
Abstract
```

Examples

```text
AbstractField

AbstractValidator

AbstractRepository
```

---

# Methods

Use camelCase.

Examples

```php
registerField()

validateInput()

loadAssets()

saveMeta()

boot()

render()
```

Avoid

```php
RegisterField()

register_field()

DoStuff()
```

---

# Variables

Use camelCase.

Examples

```php
$fieldGroup

$fieldType

$currentUser

$validationRules
```

Avoid

```php
$field_group

$fieldgroup

$data1
```

---

# Constants

Use uppercase with underscores.

Examples

```php
OPENMETA_VERSION

OPENMETA_PATH

OPENMETA_URL

OPENMETA_PLUGIN_FILE
```

---

# Configuration Keys

Use dot notation.

Examples

```text
app.name

app.version

cache.enabled

database.prefix

fields.defaults
```

---

# Database Tables

Prefix every table.

Example

```text
wp_openmeta_fields

wp_openmeta_groups

wp_openmeta_relationships

wp_openmeta_logs
```

Never create generic table names.

---

# Database Columns

Use snake_case.

Examples

```text
field_id

group_id

created_at

updated_at

is_active
```

---

# WordPress Options

Prefix every option.

Examples

```text
openmeta_version

openmeta_settings

openmeta_license

openmeta_cache
```

---

# Transients

Examples

```text
openmeta_cache

openmeta_schema

openmeta_field_groups
```

---

# Hooks

Actions

Prefix with

```text
openmeta_
```

Examples

```php
openmeta_loaded

openmeta_booting

openmeta_saved

openmeta_deleted
```

---

# Filters

Examples

```php
openmeta_field_value

openmeta_validation_rules

openmeta_field_output
```

---

# REST API

Namespace

```text
openmeta/v1
```

Examples

```text
/openmeta/v1/fields

/openmeta/v1/groups

/openmeta/v1/settings
```

---

# JavaScript

Variables

camelCase

Functions

camelCase

Constants

UPPER_CASE

Files

kebab-case

Examples

```text
field-builder.ts

validation-engine.ts

field-registry.ts
```

---

# React Components

Use PascalCase.

Examples

```text
FieldBuilder

FieldEditor

SettingsPage

SidebarMenu

ValidationPanel
```

---

# React Hooks

Prefix every hook with

```text
use
```

Examples

```text
useFields()

useValidation()

useSettings()
```

---

# CSS

Tailwind utilities preferred.

Custom classes use:

```text
om-

Example

om-button

om-sidebar

om-dialog
```

---

# Icons

Component names

```text
FieldIcon

SettingsIcon

DatabaseIcon
```

---

# Events

Past tense.

Examples

```text
FieldCreated

FieldUpdated

FieldDeleted
```

---

# Exceptions

Suffix every exception.

```text
Exception
```

Examples

```text
ValidationException

StorageException

ConfigurationException
```

---

# Tests

Suffix every test.

Examples

```text
FieldRegistryTest

ValidationTest

DatabaseTest
```

---

# Documentation Files

Use kebab-case.

Examples

```text
plugin-bootstrap.md

field-architecture.md

dependency-injection.md

service-container.md
```

---

# Git Branches

Feature

```text
feature/field-builder
```

Bug

```text
fix/validation
```

Release

```text
release/v1.0.0
```

Hotfix

```text
hotfix/cache
```

---

# Commit Messages

Examples

```text
feat: add field registry

fix: resolve validation bug

docs: update architecture

refactor: simplify bootstrap

test: add field validation tests
```

Follow Conventional Commits.

---

# Summary

Consistent naming is essential for long-term maintainability.

Every contributor should follow these conventions to ensure the project remains clean, predictable, and easy to navigate.

These rules apply to all current and future OpenMeta packages.