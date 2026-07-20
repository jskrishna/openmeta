# Nonce System

---

# Purpose

The Nonce System protects sensitive operations by ensuring that requests originate from a trusted user and were intentionally initiated.

Within OpenMeta, nonces provide request verification but **do not replace authentication or authorization**.

---

# Goals

The Nonce System should:

- Prevent forged requests
- Verify request authenticity
- Integrate with WordPress nonces
- Protect administrative actions
- Support secure APIs

---

# Architecture

```text
User Action

↓

Generate Nonce

↓

Embed in Request

↓

Server Verification

↓

Valid / Invalid

↓

Execute or Reject
```

---

# Responsibilities

The Nonce System manages:

- Nonce generation
- Request verification
- Token expiration
- Action validation
- Secure request handling

---

# Nonce Lifecycle

```text
Create Nonce

↓

Attach to Request

↓

User Action

↓

Verify Nonce

↓

Accept / Reject

↓

Expire
```

---

# Protected Operations

Nonces should protect:

- Form submissions
- Settings updates
- Record deletion
- Bulk operations
- AJAX requests
- REST mutations
- Extension management

Read-only operations typically do not require nonce verification.

---

# WordPress Integration

The framework should leverage:

- WordPress nonce generation
- Nonce verification
- User session validation
- Built-in security helpers

Native WordPress mechanisms should be preferred over custom implementations.

---

# Failure Handling

Failed verification should:

- Reject the request
- Return a secure error response
- Avoid exposing internal details
- Log suspicious activity when appropriate

---

# Relationship to Other Security Layers

```text
Authentication

↓

Authorization

↓

Nonce Verification

↓

Validation

↓

Business Logic
```

Each layer provides independent protection.

---

# Extensibility

Developers may extend:

- Nonce providers
- Expiration policies
- Verification strategies
- Custom request handlers

Extensions must preserve framework security guarantees.

---

# Best Practices

- Verify every state-changing request.
- Never trust client-side tokens alone.
- Use short-lived nonces.
- Prefer WordPress nonce utilities.
- Log repeated verification failures.

---

# Summary

The OpenMeta Nonce System verifies the authenticity of sensitive requests by integrating with WordPress nonce mechanisms, providing an additional layer of protection against forged or unauthorized actions.