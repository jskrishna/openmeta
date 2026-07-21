# Tutorial — Getting Started

tags: getting-started, tutorial

## Install

```bash
composer require openmeta/framework
```

## Boot the framework

```php
use OpenMeta\Framework\Framework;

$app = Framework::boot();
```

`Framework::boot()` registers every package's service provider in dependency
order. Resolve any service from the container:

```php
$graphql = $app->get(\OpenMeta\GraphQL\Contracts\GraphQLManagerInterface::class);
$console = $app->get(\OpenMeta\Cli\Contracts\ConsoleApplicationInterface::class);
```

## Use the console

```bash
php bin/openmeta list
php bin/openmeta doctor
php bin/openmeta make:field Star
php bin/openmeta docs:build
```

## Next

- [Create a Field](../examples/create-a-field.md)
- [Create an Extension](./create-an-extension.md)
- [CLI reference](../cli/README.md)
