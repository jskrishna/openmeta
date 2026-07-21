# OpenMeta Code Generator — developer guide

`@openmeta/generator` scaffolds production-ready boilerplate from stubs. See
[../SPEC.md](../SPEC.md) for the contract.

## Through the console

With the CLI + generator providers booted, every generator is a `make:<key>`:

```bash
openmeta make:field Star
openmeta make:repository Post
openmeta make:command Greet
openmeta make:graphql-type Product
openmeta make:extension Seo

openmeta make:field Star --dry-run   # preview, write nothing
openmeta make:field Star --force     # overwrite an existing file
```

## Programmatically

```php
use OpenMeta\Generator\Contracts\GeneratorManagerInterface;
use OpenMeta\Generator\Manager\GenerationOptions;
use OpenMeta\Generator\Manager\GenerationRequest;

/** @var GeneratorManagerInterface $generator */
$generator = $app->get(GeneratorManagerInterface::class);

$result = $generator->run(new GenerationRequest('field', 'Star'));
foreach ($result->written() as $file) {
    echo "created {$file->path}\n";
}

// Preview without touching disk:
$preview = $generator->run(new GenerationRequest('repository', 'Post', [], new GenerationOptions(dryRun: true)));
echo $preview->files[0]->contents;
```

## Never-overwrite guarantee

- An **existing file** is skipped unless you pass `force`.
- **Reserved namespaces** (`OpenMeta\…`) and **naming collisions** (a class that
  already exists) always skip — `force` does not override them.

## Add a generator type (no code changes)

Register a definition + drop a stub:

```php
use OpenMeta\Generator\Registry\GeneratorDefinition;

$registry->register($factory->fromDefinition(new GeneratorDefinition(
    key: 'policy',
    description: 'An authorization policy',
    stub: 'policy',              // resources/stubs/policy.stub (or a custom path)
    subdirectory: 'Policies',
    namespaceSuffix: 'Policies',
    classSuffix: 'Policy',
)));
```

## Stub syntax

```text
namespace {{ namespace }};
{{#if baseImport}}use {{ baseImport }};{{/if}}

final class {{ class }}{{#if extends}} extends {{ extends }}{{/if}}
{
}
```

Available variables: `name`, `class`, `snake`, `kebab`, `plural`, `namespace`,
`author`, `license`, `year`, plus any extras a definition or request supplies.

## Extending

- Custom generators: implement `GeneratorInterface`.
- Extra stub directories: `StubLoader::addPath()` (later paths win).
- Post-processing: implement `FileProcessorInterface` and
  `GeneratorManager::addProcessor()`.

## Not included

No AI generation, cloud templates, marketplace, or IDE plugins.
