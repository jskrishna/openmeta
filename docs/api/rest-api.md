# REST API

---

# Purpose

The REST API exposes OpenMeta resources over HTTP using predictable, resource-oriented endpoints.

It enables integrations with frontend applications, mobile clients, third-party services, and headless architectures.

---

# REST Principles

The OpenMeta REST API follows RESTful design principles.

- Resource Oriented
- Stateless
- Cache Friendly
- Versioned
- Predictable

---

# Architecture

```text
HTTP Request

↓

Router

↓

Controller

↓

Repository

↓

Storage Driver

↓

JSON Response
```

---

# Resources

The REST API exposes resources such as:

- Schemas
- Fields
- Field Groups
- Extensions
- Packages

---

# Request Lifecycle

```text
Request

↓

Authentication

↓

Authorization

↓

Validation

↓

Controller

↓

Repository

↓

Response
```

---

# Response Format

Responses should include:

- Data
- Metadata
- Pagination (when applicable)
- Errors (when applicable)

All responses should use JSON.

---

# Authentication

Supported authentication methods may include:

- WordPress Authentication
- Application Passwords
- OAuth
- JWT (Extension)

---

# Versioning

REST APIs should support versioning to preserve backward compatibility.

Breaking changes should only occur in major API versions.

---

# Best Practices

- Use HTTP status codes correctly.
- Keep endpoints resource-oriented.
- Validate every request.
- Return consistent responses.
- Avoid exposing internal models.

---

# Summary

The REST API provides a standardized HTTP interface for accessing OpenMeta resources while maintaining consistency with the framework's domain model.