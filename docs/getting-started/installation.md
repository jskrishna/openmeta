# Installation

---

# Purpose

This guide explains how to install OpenMeta for development and production environments.

By the end of this guide, you will have a working OpenMeta installation ready for creating schemas and structured content.

---

# Prerequisites

Before installing OpenMeta, ensure your environment satisfies the minimum system requirements described in the Requirements guide.

You should have:

- WordPress installed
- Composer available
- PHP configured
- Database access

---

# Installation Methods

OpenMeta supports multiple installation methods.

- WordPress Plugin
- Composer
- Development Repository

Choose the method that best fits your workflow.

---

# Plugin Installation

Install OpenMeta like any standard WordPress plugin.

Steps:

1. Download the latest release.
2. Upload the plugin.
3. Activate it from the WordPress Admin.
4. Verify the installation.

OpenMeta should now be available within WordPress.

---

# Composer Installation

For Composer-based projects:

```bash
composer require openmeta/openmeta
```

Composer automatically installs framework dependencies.

---

# Development Installation

Clone the repository:

```bash
git clone https://github.com/openmeta/openmeta.git
```

Install dependencies:

```bash
composer install
```

If `composer` is not on your `PATH` (common on Windows), use the local PHAR:

```bash
php composer.phar install
```

Verify the workspace (PHPStan → PHPCS → PHPUnit):

```bash
composer validate --no-check-publish --strict
composer ci
```

Install frontend dependencies (if required):

```bash
npm install
```

Build assets:

```bash
npm run build
```

---

# Configuration

After installation, configure:

- Storage Driver
- Cache
- Debug Mode
- Packages
- Extensions

Default configuration is suitable for most development environments.

---

# Verify Installation

A successful installation should provide:

- Active plugin
- Registered services
- Loaded configuration
- Available Schema Manager
- Healthy application startup

No errors should appear during initialization.

---

# Updating

To update OpenMeta:

Plugin installation:

- Replace the plugin with the latest release.

Composer installation:

```bash
composer update openmeta/openmeta
```

After updating:

- Run pending migrations.
- Clear cache.
- Verify configuration.
- Review upgrade notes.

---

# Uninstallation

If OpenMeta is removed:

- Plugin files are deleted.
- Optional data cleanup may be performed.
- Custom tables may remain depending on configuration.

Applications should back up important data before uninstalling.

---

# Troubleshooting

Common installation issues include:

- Unsupported PHP version
- Missing Composer dependencies
- Incorrect file permissions
- Database connection problems
- Plugin conflicts

Refer to the Troubleshooting guide for detailed solutions.

---

# Next Steps

After installation:

1. Review the core concepts.
2. Create your first Schema.
3. Register your first Field Group.
4. Explore the Field System.

---

# Summary

OpenMeta supports both traditional WordPress plugin installation and modern Composer-based workflows. Once installed and configured, the framework is ready to model structured content through schemas, fields, validation, and storage abstractions.