# Contributing to OpenMeta

Thank you for your interest in contributing to **OpenMeta**.

OpenMeta is an open-source, architecture-first framework for building structured content solutions on WordPress. Every contribution—whether code, documentation, design, testing, or discussion—helps improve the project.

This guide explains how to contribute effectively while maintaining the project's quality, consistency, and long-term vision.

---

# Purpose

The contribution process exists to:

- Maintain a high-quality codebase
- Preserve architectural consistency
- Encourage collaboration
- Simplify onboarding
- Ensure predictable development
- Keep documentation synchronized with implementation

---

# OpenMeta Philosophy

OpenMeta follows several core principles:

- Architecture First
- Documentation First
- API First
- Extensibility First
- Security by Default
- Performance by Design
- Community Driven Development

Every contribution should align with these principles.

---

# Ways to Contribute

Community members can contribute by:

- Reporting bugs
- Suggesting new features
- Improving documentation
- Fixing bugs
- Writing tests
- Improving accessibility
- Optimizing performance
- Reviewing Pull Requests
- Participating in discussions
- Creating examples and tutorials
- Improving developer experience

Every contribution is valuable.

---

# Before You Start

Before opening an Issue or Pull Request:

- Read the project documentation.
- Review the Architecture documentation.
- Read the ADRs.
- Search for existing Issues.
- Search for existing Pull Requests.
- Understand the project's coding standards.

---

# Repository Structure

```text
OpenMeta/

├── .github/
├── docs/
├── packages/
├── tests/
├── examples/
├── bin/
├── scripts/
├── tools/
├── website/
├── README.md
├── ARCHITECTURE.md
├── CONTRIBUTING.md
└── ...
```

---

# Development Workflow

Every change should follow this workflow:

```text
Idea

↓

Discussion

↓

Architecture Review

↓

Documentation

↓

Implementation

↓

Testing

↓

Code Review

↓

Merge
```

Implementation should not begin before the corresponding architecture and documentation have been reviewed when introducing significant changes.

---

# Contribution Workflow

```text
Fork Repository

↓

Create Branch

↓

Implement Changes

↓

Write Tests

↓

Update Documentation

↓

Run Quality Checks

↓

Open Pull Request

↓

Code Review

↓

Merge
```

---

# Reporting Bugs

When reporting a bug, include:

- Clear title
- Description
- Steps to reproduce
- Expected behavior
- Actual behavior
- Environment details
- Screenshots (if applicable)
- Relevant logs (if applicable)

Well-written bug reports help maintainers resolve issues more efficiently.

---

# Feature Requests

Feature requests should include:

- Problem statement
- Proposed solution
- Expected benefits
- Alternative approaches considered
- Potential architectural impact

Large features may require an Architecture Decision Record (ADR) before implementation.

---

# Documentation Contributions

Documentation is a core part of OpenMeta.

Documentation contributions include:

- New guides
- API improvements
- Architecture updates
- Examples
- Tutorials
- Corrections
- Typographical fixes

Documentation should remain consistent with the established project structure and terminology.

---

# Coding Standards

Contributors should:

- Follow project coding standards.
- Write readable code.
- Keep modules focused.
- Avoid unnecessary complexity.
- Prefer composition over duplication.
- Keep public APIs stable.
- Maintain backward compatibility whenever practical.

Refer to the development documentation for detailed coding guidelines.

---

# Testing Requirements

All contributions should include appropriate testing where applicable.

Testing may include:

- Unit Tests
- Integration Tests
- Functional Tests
- API Tests
- UI Tests
- Regression Tests

Contributions that introduce new functionality should include corresponding tests.

---

# Documentation Requirements

Documentation should be updated whenever changes affect:

- Architecture
- APIs
- User workflows
- Configuration
- Development processes
- Extension points
- Public interfaces

Implementation and documentation should remain synchronized.

---

# Pull Requests

A good Pull Request should:

- Address a single topic or feature
- Include a clear description
- Reference related Issues
- Include relevant documentation updates
- Include tests where appropriate
- Pass all quality checks

Avoid combining unrelated changes in a single Pull Request.

---

# Pull Request Checklist

Before submitting:

- [ ] Architecture reviewed (if applicable)
- [ ] Documentation updated
- [ ] Tests added or updated
- [ ] Existing tests pass
- [ ] Coding standards followed
- [ ] No unnecessary changes included
- [ ] Public APIs documented
- [ ] Changelog updated (if applicable)

---

# Code Review

Every Pull Request should undergo review.

Reviewers evaluate:

- Architecture
- Code quality
- Documentation
- Testing
- Performance
- Security
- Maintainability
- Backward compatibility

Review feedback should remain respectful and constructive.

---

# Commit Guidelines

Commits should:

- Be focused
- Be descriptive
- Represent one logical change
- Avoid unrelated modifications

Example commit types:

```text
feat:

fix:

docs:

refactor:

test:

perf:

build:

ci:

chore:
```

---

# Branching Strategy

Suggested branch naming:

```text
feature/

bugfix/

hotfix/

docs/

refactor/

release/
```

Examples:

```text
feature/field-builder

bugfix/api-validation

docs/security-guide

refactor/database-layer
```

---

# Security Contributions

If you discover a security vulnerability:

- Do not disclose it publicly.
- Follow the project's Security Policy.
- Report it privately to the maintainers.
- Allow time for investigation and remediation.

---

# Community Expectations

Contributors are expected to:

- Follow the Code of Conduct.
- Respect maintainers and contributors.
- Participate constructively.
- Keep discussions focused.
- Welcome newcomers.
- Help improve documentation.

---

# Recognition

All meaningful contributions are appreciated.

Contributions may include:

- Code
- Documentation
- Design
- Testing
- Bug reports
- Feature proposals
- Community support
- Reviews

OpenMeta values quality contributions regardless of size.

---

# License

By contributing to OpenMeta, you agree that your contributions will be licensed under the project's license.

---

# Getting Help

If you need assistance:

- Review the documentation.
- Search existing Issues.
- Read the Architecture documentation.
- Review the ADRs.
- Ask questions in project discussions.

The community is encouraged to help one another.

---

# Best Practices

- Read documentation before contributing.
- Keep changes small and focused.
- Write clear commit messages.
- Test thoroughly.
- Update documentation.
- Respect architectural decisions.
- Prefer reusable solutions.
- Ask questions when uncertain.
- Collaborate openly.
- Help improve the project beyond your own contribution.

---

# Summary

OpenMeta is built through collaborative, documentation-driven development. By following this contribution guide, community members help maintain a high-quality, maintainable, and extensible framework while preserving the project's long-term architectural vision.