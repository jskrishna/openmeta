# Troubleshooting

---

# Purpose

This guide helps diagnose and resolve common issues encountered while installing, configuring, or developing with OpenMeta.

Most issues can be resolved by verifying the application environment, configuration, and project structure.

---

# Before You Begin

Before investigating a problem, verify:

- PHP version is supported.
- WordPress version is supported.
- Composer dependencies are installed.
- OpenMeta is activated.
- Required PHP extensions are enabled.
- File permissions are correct.

Many issues are caused by environment misconfiguration rather than framework bugs.

---

# Installation Issues

## Plugin Does Not Appear

Possible causes:

- Plugin not activated
- Incorrect installation path
- Missing dependencies

Recommended actions:

- Verify plugin installation.
- Check Composer dependencies.
- Review PHP error logs.

---

## Composer Installation Fails

Possible causes:

- Unsupported Composer version
- PHP version mismatch
- Network interruption

Recommended actions:

- Update Composer.
- Verify PHP version.
- Clear Composer cache.
- Retry installation.

---

# Configuration Issues

## Configuration Changes Are Ignored

Possible causes:

- Cached configuration
- Incorrect file location
- Syntax errors

Recommended actions:

- Clear application cache.
- Validate configuration files.
- Restart the application if required.

---

# Schema Issues

## Schema Does Not Appear

Possible causes:

- Schema not registered
- Invalid configuration
- Registration failed

Recommended actions:

- Verify Schema registration.
- Check application logs.
- Validate Schema definition.

---

## Schema Validation Fails

Possible causes:

- Duplicate identifiers
- Invalid Field configuration
- Missing required properties

Recommended actions:

- Review validation errors.
- Check naming conventions.
- Validate all Field definitions.

---

# Field Issues

## Fields Are Missing

Possible causes:

- Field Group not registered
- Incorrect Location Rules
- Extension not loaded

Recommended actions:

- Verify Field registration.
- Review Location Rules.
- Confirm Extension loading.

---

## Validation Does Not Execute

Possible causes:

- Validation rules not defined
- Incorrect Field Type
- Custom validator not registered

Recommended actions:

- Review validation configuration.
- Confirm validator registration.
- Test using simple validation rules.

---

# Storage Issues

## Data Is Not Saved

Possible causes:

- Storage Driver misconfiguration
- Database permissions
- Validation failure

Recommended actions:

- Check Storage Driver.
- Verify database connectivity.
- Review validation results.

---

## Incorrect Storage Driver

Possible causes:

- Invalid configuration
- Missing driver
- Extension not installed

Recommended actions:

- Verify configuration.
- Confirm supported Storage Driver.
- Review startup logs.

---

# Extension Issues

## Extension Not Loaded

Possible causes:

- Incorrect installation
- Missing Service Provider
- Invalid manifest

Recommended actions:

- Verify Extension structure.
- Check registration.
- Review application logs.

---

# Performance Issues

Symptoms:

- Slow administration interface
- Long request times
- High memory usage

Recommended actions:

- Enable caching.
- Use OPcache.
- Optimize database indexes.
- Review custom Extensions.

---

# Debugging Tips

Useful steps:

- Enable Debug Mode.
- Check PHP error logs.
- Review OpenMeta logs.
- Disable third-party Extensions.
- Test using a clean environment.

---

# Getting Help

If the problem cannot be resolved:

- Review the documentation.
- Search existing issues.
- Prepare reproduction steps.
- Include environment information.
- Report the issue with relevant logs.

---

# Summary

Most OpenMeta issues are related to environment configuration, registration, or custom Extensions. Following a systematic troubleshooting process makes problems easier to diagnose and resolve.