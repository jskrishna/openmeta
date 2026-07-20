# Quick Start

---

# Purpose

This guide provides a quick introduction to OpenMeta by walking through the complete workflow of creating your first structured content model.

By the end of this guide you will understand the core workflow and have a basic OpenMeta project running.

Estimated time: **10–15 minutes**

---

# What You'll Build

During this guide you will:

- Create a Schema
- Create a Field Group
- Add Fields
- Configure Validation
- Store Data
- Retrieve Data

---

# OpenMeta Workflow

Every OpenMeta project follows the same workflow.

```text
Create Schema

↓

Create Field Groups

↓

Add Fields

↓

Configure Validation

↓

Assign Location Rules

↓

Store Content

↓

Retrieve Content
```

---

# Step 1 — Create a Schema

A Schema represents a structured content model.

Example:

```text
Product
```

The Schema acts as the root container for all metadata.

---

# Step 2 — Add a Field Group

Field Groups organize related Fields.

Example:

```text
General Information
```

```text
Pricing
```

```text
SEO
```

---

# Step 3 — Add Fields

Fields describe individual pieces of information.

Example:

```text
Product Name

Price

Description

SKU

Image
```

---

# Step 4 — Configure Validation

Each Field may define validation rules.

Examples:

- Required
- Minimum Length
- Maximum Length
- Numeric
- Email
- URL

Validation ensures consistent data.

---

# Step 5 — Configure Storage

Choose how OpenMeta stores data.

Supported options include:

- WordPress Meta
- Custom Tables
- SQLite
- JSON

Changing storage should not require changing your Schema.

---

# Step 6 — Apply Location Rules

Location Rules determine where the Schema appears.

Example:

```text
Post Type

↓

Product
```

The same Schema may also target Users, Taxonomies, or other entities.

---

# Step 7 — Save Data

Once configured, editors can enter content through the OpenMeta interface.

OpenMeta validates the input before persisting it.

---

# Step 8 — Retrieve Data

Applications retrieve structured data through the Repository Layer.

```text
Application

↓

Repository

↓

Storage Driver

↓

Database
```

Business logic never communicates directly with the database.

---

# What You Built

You now have:

```text
Product Schema

↓

General Information

↓

Fields

↓

Validation

↓

Storage
```

This represents the complete OpenMeta workflow.

---

# Next Steps

Continue with:

- First Schema
- First Field
- Concepts
- Field System

---

# Summary

You have completed your first OpenMeta workflow by creating a Schema, organizing Fields, applying validation, configuring storage, and understanding how structured content flows through the framework.