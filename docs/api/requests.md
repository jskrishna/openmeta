# Requests

---

# Purpose

Requests represent incoming data entering the OpenMeta application through PHP, REST, GraphQL, CLI, or Extensions.

The Request layer is responsible for receiving input, validating structure, sanitizing values, authorizing operations, and transforming raw data into objects that the Domain Layer can safely consume.

Requests should never contain business logic.

---

# Request Lifecycle

```text
Incoming Request

↓

Authentication

↓

Authorization

↓

Validation

↓

Sanitization

↓

Transformation

↓

Application Service

↓

Repository
```

---

# Sources of Requests

OpenMeta accepts requests from multiple interfaces.

- PHP API
- REST API
- GraphQL
- WP Admin
- CLI
- Extensions

All request sources eventually follow the same processing pipeline.

---

# Responsibilities

The Request layer is responsible for:

- Accept input
- Validate structure
- Sanitize values
- Verify permissions
- Transform payloads
- Reject invalid data

---

# Validation

Every request should be validated before reaching the Domain Layer.

Validation may include:

- Required Fields
- Type Checking
- Length Constraints
- Format Validation
- Business Rules
- Cross-field Validation

---

# Sanitization

Before validation, request data should be sanitized.

Examples include:

- Remove HTML
- Trim whitespace
- Normalize dates
- Normalize numbers
- Convert boolean values

---

# Authorization

Requests should verify permissions before executing business operations.

Authorization occurs after authentication but before repository access.

---

# Request Objects

Requests should be represented as immutable objects.

Benefits include:

- Predictability
- Testability
- Easier debugging
- Clear contracts

---

# Error Handling

Invalid requests should fail early with descriptive validation errors.

Processing should stop immediately after a fatal validation failure.

---

# Best Practices

- Keep requests immutable.
- Validate before processing.
- Sanitize all external input.
- Never place business logic inside requests.
- Return meaningful validation errors.

---

# Summary

The Request layer provides a secure and predictable entry point into OpenMeta by validating, sanitizing, and transforming incoming data before it reaches the application's business logic.