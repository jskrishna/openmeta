# Responses

---

# Purpose

Responses represent data leaving the OpenMeta application.

They provide a consistent, predictable format regardless of whether the client is PHP, REST, GraphQL, CLI, or an Extension.

---

# Response Lifecycle

```text
Repository

↓

Domain Object

↓

Serializer

↓

Transformer

↓

Response

↓

Client
```

---

# Response Principles

Responses should be:

- Consistent
- Predictable
- Versioned
- Serializable
- Cache Friendly

---

# Response Types

OpenMeta supports multiple response types.

- Success
- Collection
- Error
- Validation
- Paginated
- Empty

---

# Success Response

A successful response contains:

- Data
- Metadata (optional)
- Links (optional)

---

# Collection Response

Collections may include:

- Items
- Total Count
- Pagination
- Links
- Metadata

---

# Error Response

Error responses should contain:

- Error Code
- Message
- Context
- Validation Details (if applicable)

Sensitive internal information should never be exposed.

---

# HTTP Status Codes

REST responses should use appropriate HTTP status codes.

Examples include:

- 200 OK
- 201 Created
- 204 No Content
- 400 Bad Request
- 401 Unauthorized
- 403 Forbidden
- 404 Not Found
- 422 Validation Error
- 500 Internal Server Error

---

# Response Metadata

Metadata may include:

- Version
- Execution Time
- Pagination
- Warnings
- Links

---

# Best Practices

- Keep responses consistent.
- Avoid exposing internal models.
- Return descriptive errors.
- Include metadata only when useful.
- Support future versioning.

---

# Summary

The Response layer provides a consistent representation of OpenMeta resources, ensuring predictable communication across all supported APIs.