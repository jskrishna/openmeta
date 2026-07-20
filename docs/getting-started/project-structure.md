# Project Structure

---

# Purpose

A well-organized project structure improves maintainability, discoverability, scalability, and collaboration.

OpenMeta recommends a modular directory layout where Schemas, Fields, Extensions, Configuration, and Infrastructure remain clearly separated.

Although projects may organize files differently, following the recommended structure leads to more maintainable applications.

---

# Recommended Structure

```text
project/

├── app/
│   ├── Schemas/
│   ├── Fields/
│   ├── Providers/
│   ├── Extensions/
│   ├── Services/
│   └── Support/
│
├── config/
│
├── storage/
│
├── resources/
│
├── routes/
│
├── tests/
│
├── vendor/
│
└── bootstrap/
```

---

# Directory Overview

## app/

Contains all application code.

```text
app/

├── Schemas/
├── Fields/
├── Providers/
├── Extensions/
├── Services/
└── Support/
```

---

## Schemas/

Contains Schema definitions.

Example:

```text
ProductSchema

CustomerSchema

OrderSchema
```

---

## Fields/

Contains reusable custom Field Types.

Examples:

```text
CurrencyField

RatingField

ColorPickerField
```

---

## Providers/

Contains Service Providers.

Responsibilities include:

- Register Services
- Configure Modules
- Register Extensions
- Boot Components

---

## Extensions/

Contains local OpenMeta extensions.

Examples:

- Import Tools
- Custom Validation
- Integrations

---

## Services/

Contains application-specific business services.

Business logic should remain outside controllers and UI components.

---

## Support/

Contains helper utilities and reusable infrastructure code.

Examples:

- Helpers
- Utilities
- Traits
- Value Objects

---

## config/

Stores configuration files.

Examples:

```text
app.php

database.php

cache.php

storage.php
```

---

## storage/

Contains runtime storage.

Examples:

- Cache
- Logs
- Temporary Files
- Exports

---

## resources/

Contains non-PHP assets.

Examples:

- Images
- Localization
- Templates
- UI Assets

---

## routes/

Defines application routes.

Examples:

- REST
- GraphQL
- CLI

---

## tests/

Contains automated tests.

```text
Unit/

Integration/

Contracts/

Performance/
```

---

## vendor/

Composer dependencies.

Never modify vendor files directly.

---

## bootstrap/

Application bootstrap process.

Responsible for initializing the framework.

---

# Design Principles

The structure should:

- Separate concerns
- Encourage modularity
- Improve discoverability
- Scale with project size
- Support testing

---

# Best Practices

- Keep business logic inside Services.
- Keep Schemas independent.
- Organize custom Fields separately.
- Avoid large utility folders.
- Follow consistent naming conventions.

---

# Summary

The recommended project structure keeps OpenMeta applications organized, scalable, and maintainable by separating business logic, schemas, extensions, configuration, and infrastructure into clearly defined modules.