# OpenMeta — Phase 14: CLI & Developer Tools

You are a Principal Framework Architect and DX (Developer Experience) Engineer contributing to the OpenMeta framework.

Before writing any code, you MUST read:

- README.md
- ARCHITECTURE.md
- docs/
- docs/adr/
- packages/*/README.md
- packages/cli/README.md
- packages/cli/SPEC.md

Documentation is the source of truth.

Never violate documented architecture.

---

# Goal

Build ONLY the CLI & Developer Tools package.

The CLI is the primary interface for developers working with OpenMeta.

It should simplify development, scaffolding, testing, debugging, maintenance, and package management.

The CLI must remain framework-aware but platform-independent.

---

# Responsibilities

Implement:

- Console Application
- Command Registry
- Command Discovery
- Input Parser
- Output Formatter
- Interactive Prompts
- Configuration Loader
- Environment Inspector
- Package Manager Integration
- Task Runner
- Logger
- Progress Indicators
- Error Handler
- Event Dispatcher Integration

---

# Folder Structure

packages/cli/

src/

Application/
Commands/
Registry/
Discovery/
Input/
Output/
Prompts/
Configuration/
Environment/
Logging/
Tasks/
Events/
Contracts/
Exceptions/
Support/

tests/
docs/

Respect the package structure.

---

# Development Rules

Follow:

- PSR-12
- SOLID
- DRY
- KISS
- Interfaces First
- Dependency Injection

---

# Dependency Rules

CLI may depend on:

- Core
- Support
- Validation
- Security
- Database
- Fields
- REST
- WordPress
- Admin
- Builder
- Extension SDK
- GraphQL

No package should depend on CLI.

CLI is an outer developer tool.

---

# Console Application

Implement:

- Boot Process
- Command Registration
- Command Discovery
- Lazy Loading
- Exit Codes

---

# Commands

Provide infrastructure for commands.

Support:

- Arguments
- Options
- Interactive Prompts
- Validation
- Confirmation

Do not hardcode project-specific commands.

---

# Built-in Commands

Provide architecture for commands such as:

- project:init
- package:create
- package:list
- package:install
- package:remove

- make:field
- make:component
- make:migration
- make:provider
- make:repository
- make:command
- make:event

- migrate
- migrate:rollback
- migrate:status

- cache:clear
- config:cache
- route:list

- doctor
- info
- version

Implement infrastructure and representative commands. Avoid excessive business-specific commands.

---

# Output

Support:

- Tables
- Progress Bars
- Success Messages
- Warning Messages
- Error Messages
- JSON Output

---

# Environment

Provide utilities for:

- PHP Version
- Extensions
- File Permissions
- Configuration
- Package Status

---

# Logging

Implement:

- Console Logger
- Debug Output
- Verbosity Levels

---

# Task Runner

Support reusable tasks for:

- Build
- Test
- Lint
- Documentation
- Release

---

# Events

Dispatch:

- Command Started
- Command Finished
- Command Failed

Reuse the Core Event Dispatcher.

---

# Extensibility

Allow third-party packages to register:

- Commands
- Tasks
- Output Formatters

without modifying framework code.

---

# Public API

Expose only:

- Console Application
- Command Registry
- Task Runner

Hide implementation details.

---

# Tests

Generate PHPUnit tests for:

- Console Application
- Command Registry
- Input Parsing
- Output Formatting
- Task Runner
- Events

Target high coverage.

---

# Quality

Run:

- PHPUnit
- PHPStan
- PHPCS

Resolve all issues.

---

# Constraints

Do NOT implement:

- GUI
- Marketplace
- Cloud Features
- IDE Plugins

Those belong to future packages.

---

# Deliverables

Produce:

- Production-ready code
- PHPUnit tests
- PHPDoc
- Updated documentation
- Suggested commit message

Implement ONLY the CLI package.
