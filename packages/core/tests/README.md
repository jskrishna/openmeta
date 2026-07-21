# Core tests

PHPUnit suite for `@openmeta/core` bootstrap components.

```bash
composer test:core
# or
vendor/bin/phpunit --configuration phpunit.xml
```

## Tests

| File | Covers |
| ---- | ------ |
| `ApplicationTest.php` | Application boot + step methods |
| `ContainerTest.php` | Bind / singleton / resolve / aliases |
| `ProviderTest.php` | Service provider Register → Boot |
| `ConfigTest.php` | ConfigLoader + ConfigRepository |
| `KernelTest.php` | Bootstrap → Initialize → Ready |
| `BootstrapTest.php` | Full bootstrap sequence |
| `EventDispatcherTest.php` | Listen / dispatch / FrameworkBooted |
| `ExceptionsTest.php` | OpenMetaException + BindingResolutionException |
| `smoke.php` | End-to-end spine smoke (non-PHPUnit) |

## Layout

```text
packages/core/tests/
├── ApplicationTest.php
├── ContainerTest.php
├── ProviderTest.php
├── ConfigTest.php
├── KernelTest.php
├── BootstrapTest.php
├── EventDispatcherTest.php
├── ExceptionsTest.php
├── CoreTestCase.php
├── Fixtures/
├── smoke.php
└── Unit/          # legacy assert scripts (optional)
```
