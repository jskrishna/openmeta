# Requirements

---

# Purpose

This document outlines the minimum and recommended requirements for running OpenMeta.

Meeting these requirements ensures reliable operation, optimal performance, and compatibility with future releases.

---

# Minimum Requirements

OpenMeta requires the following minimum environment.

| Component | Minimum Version |
|-----------|-----------------|
| PHP | 8.3+ |
| WordPress | 6.8+ |
| MySQL | 8.0+ |
| MariaDB | 10.6+ |
| Composer | 2.x |

These versions represent the minimum supported environment.

---

# Recommended Environment

For the best development experience, the following versions are recommended.

| Component | Recommended |
|-----------|-------------|
| PHP | 8.3+ (latest stable 8.x preferred) |
| WordPress | Latest stable |
| MySQL | Latest stable |
| Composer | Latest stable |
| Node.js | Latest LTS |

Using current versions provides improved security and performance.

---

# PHP Extensions

The following PHP extensions should be available:

- JSON
- mbstring
- OpenSSL
- PDO
- PDO MySQL
- cURL
- FileInfo
- XML

Additional extensions may improve performance but are not required.

---

# Database

Supported databases include:

- MySQL
- MariaDB

Future Storage Drivers may introduce support for additional database engines.

---

# Web Server

OpenMeta works with standard web servers, including:

- Apache
- Nginx
- LiteSpeed

The framework is web server agnostic.

---

# Composer

Composer is recommended for dependency management.

Benefits include:

- Automatic dependency resolution
- Easier upgrades
- Development tooling
- Package management

---

# Node.js

Node.js is required only when building frontend assets during development.

Production installations using pre-built assets do not require Node.js.

---

# Browser Support

The administration interface targets modern browsers.

Supported browsers include:

- Chrome
- Edge
- Firefox
- Safari

Legacy browsers are not officially supported.

---

# Development Tools

Recommended tools include:

- Composer
- Git
- Node.js
- npm
- Visual Studio Code
- PHPStan
- PHPUnit

These tools are not required for production use but improve the development experience.

---

# Hosting Environment

OpenMeta is compatible with:

- Local Development
- Shared Hosting
- VPS
- Dedicated Servers
- Cloud Platforms
- Containerized Environments

The framework does not depend on a specific hosting provider.

---

# Performance Recommendations

For production environments:

- Enable OPcache.
- Use object caching.
- Enable persistent caching where available.
- Use HTTPS.
- Keep PHP and WordPress up to date.

These recommendations improve reliability and scalability.

---

# Compatibility Policy

OpenMeta supports actively maintained software versions.

Support for deprecated PHP or WordPress versions may be removed in future major releases.

Refer to release notes for compatibility updates.

---

# Summary

OpenMeta is built on modern PHP and WordPress standards. By meeting the recommended requirements and using current development tools, developers can take full advantage of the framework's performance, security, and long-term maintainability.