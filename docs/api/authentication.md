# Authentication

---

# Purpose

Authentication verifies the identity of a client before allowing access to OpenMeta resources.

Authentication answers one question:

> Who is making this request?

Authentication should occur before authorization and before any business operation is executed.

---

# Authentication Flow

```text
Client

↓

Credentials

↓

Authentication Provider

↓

Identity

↓

Authorization

↓

Application
```

---

# Authentication Principles

Authentication should be:

- Secure
- Stateless where possible
- Extensible
- Transport independent
- Compatible with WordPress

---

# Supported Authentication Methods

OpenMeta supports multiple authentication mechanisms.

Examples include:

- WordPress Authentication
- Application Passwords
- OAuth 2.0
- JWT (Extension)
- API Keys
- Session Authentication

Applications may support one or more methods depending on their requirements.

---

# Identity Resolution

After successful authentication, the framework resolves:

- User
- Roles
- Capabilities
- Context

The authenticated identity becomes available throughout the request lifecycle.

---

# Authentication Providers

Authentication Providers encapsulate authentication logic.

Responsibilities include:

- Verify credentials
- Resolve identities
- Reject invalid requests
- Support multiple authentication methods

---

# Failed Authentication

Authentication failures should:

- Return standardized errors
- Avoid leaking sensitive information
- Log security events where appropriate
- Stop request processing immediately

---

# Security Considerations

Authentication systems should:

- Use HTTPS
- Protect credentials
- Support token expiration
- Prevent replay attacks
- Rate limit login attempts

---

# Best Practices

- Authenticate every protected request.
- Never trust client input.
- Support multiple providers through contracts.
- Separate authentication from authorization.
- Store credentials securely.

---

# Summary

Authentication establishes the identity of clients accessing OpenMeta. By supporting multiple authentication providers and following secure design principles, the framework enables flexible yet secure access to protected resources.