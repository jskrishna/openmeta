# Introduction

---

# Welcome to OpenMeta

Welcome to OpenMeta, a modern content modeling framework built specifically for WordPress.

OpenMeta enables developers to define structured content using schemas, field groups, validation rules, relationships, and storage strategies while keeping business logic independent from persistence, APIs, and user interfaces.

Whether you're building a small plugin or a large enterprise application, OpenMeta provides a consistent architectural foundation.

---

# What is OpenMeta?

OpenMeta is a developer-first framework for creating and managing structured content in WordPress.

Instead of treating metadata as isolated key-value pairs, OpenMeta introduces a complete domain model centered around schemas.

A Schema describes:

- Field Groups
- Fields
- Validation Rules
- Relationships
- Layouts
- Storage Configuration

This structured approach improves maintainability, scalability, and developer productivity.

---

# Why OpenMeta?

Modern WordPress applications often require more than simple custom fields.

Examples include:

- SaaS Applications
- Business Systems
- Enterprise Portals
- Headless CMS
- Learning Platforms
- Membership Platforms
- Internal Dashboards

OpenMeta provides the architectural foundation needed to support these applications.

---

# Core Features

OpenMeta includes:

- Schema-based Content Modeling
- Field Groups
- Rich Field Types
- Validation Engine
- Conditional Logic
- Repository Pattern
- Storage Drivers
- Dependency Injection
- Service Providers
- REST API Integration
- GraphQL Integration
- Extension System
- Package System
- Multisite Support

Every feature is designed around clean architecture and long-term maintainability.

---

# Architecture Overview

OpenMeta follows a layered architecture.

```text
Application

↓

Domain

↓

Repositories

↓

Storage Drivers

↓

Database
```

This separation ensures business logic remains independent of infrastructure.

---

# Core Concepts

OpenMeta revolves around a small set of core concepts.

```text
Schema

↓

Field Group

↓

Field

↓

Validation

↓

Storage
```

These concepts form the foundation of every OpenMeta application.

---

# Storage Independence

OpenMeta separates business logic from persistence.

Supported storage strategies include:

- WordPress Meta
- Custom Tables
- SQLite
- JSON
- External Services

Applications can change storage implementations without rewriting domain logic.

---

# Extensibility

OpenMeta is designed to be extended.

Developers can create:

- Custom Fields
- Packages
- Extensions
- Validation Rules
- Storage Drivers
- Service Providers
- Integrations

Core functionality remains stable while allowing extensive customization.

---

# Developer Experience

OpenMeta prioritizes developer productivity through:

- Predictable APIs
- Consistent architecture
- Comprehensive documentation
- Strong typing
- Dependency Injection
- Clear extension points

The goal is to reduce complexity while increasing flexibility.

---

# Documentation Structure

The documentation is organized into dedicated sections.

```text
Vision

↓

Getting Started

↓

Architecture

↓

Core

↓

Database

↓

Fields

↓

API

↓

UI

↓

Security

↓

Testing

↓

Development
```

Each section builds upon the previous one.

---

# Who Should Use OpenMeta?

OpenMeta is intended for:

- Plugin Developers
- Product Teams
- Agencies
- Enterprise Teams
- SaaS Companies
- Open Source Contributors

A basic understanding of WordPress and PHP is recommended.

---

# What You'll Learn

By following this documentation, you'll learn how to:

- Install OpenMeta
- Create Schemas
- Build Custom Fields
- Configure Storage
- Extend the Framework
- Integrate APIs
- Build scalable applications

---

# Next Steps

To begin using OpenMeta:

1. Review the system requirements.
2. Install the framework.
3. Complete the Quick Start guide.
4. Create your first Schema.
5. Explore the Field System.

---

# Summary

OpenMeta provides a modern, extensible, and storage-independent approach to structured content management in WordPress. By combining clean architecture with a developer-first experience, it enables applications that are easier to build, extend, and maintain.