<?php

declare(strict_types=1);

namespace OpenMeta\Generator\Registry;

/**
 * The built-in generator catalogue.
 *
 * Each entry is pure data — new types are added here (or registered at runtime)
 * without touching any generator code.
 */
final class GeneratorDefinitions
{
    /**
     * @return list<GeneratorDefinition>
     */
    public static function all(): array
    {
        return [
            new GeneratorDefinition('field', 'A field type', 'field', 'Fields', 'Fields'),
            new GeneratorDefinition(
                'field-group',
                'A field group',
                'class',
                'Fields/Groups',
                'Fields\\Groups',
                'Group',
            ),
            new GeneratorDefinition(
                'repository',
                'A repository',
                'repository',
                'Repositories',
                'Repositories',
                'Repository',
            ),
            new GeneratorDefinition(
                'migration',
                'A database migration',
                'migration',
                'Database/Migrations',
                'Database\\Migrations',
                'Migration',
            ),
            new GeneratorDefinition(
                'provider',
                'A service provider',
                'service-provider',
                'Providers',
                'Providers',
                'ServiceProvider',
            ),
            new GeneratorDefinition('event', 'A domain event', 'event', 'Events', 'Events'),
            new GeneratorDefinition('listener', 'An event listener', 'listener', 'Listeners', 'Listeners'),
            new GeneratorDefinition('command', 'A console command', 'command', 'Commands', 'Commands', 'Command'),
            new GeneratorDefinition(
                'controller',
                'An HTTP controller',
                'class',
                'Http/Controllers',
                'Http\\Controllers',
                'Controller',
            ),
            new GeneratorDefinition(
                'middleware',
                'HTTP middleware',
                'class',
                'Http/Middleware',
                'Http\\Middleware',
                'Middleware',
            ),
            new GeneratorDefinition(
                'rule',
                'A validation rule',
                'validation-rule',
                'Validation/Rules',
                'Validation\\Rules',
                'Rule',
            ),
            new GeneratorDefinition(
                'graphql-type',
                'A GraphQL type',
                'graphql-type',
                'GraphQL/Types',
                'GraphQL\\Types',
                'Type',
            ),
            new GeneratorDefinition(
                'graphql-resolver',
                'A GraphQL resolver',
                'graphql-resolver',
                'GraphQL/Resolvers',
                'GraphQL\\Resolvers',
                'Resolver',
            ),
            new GeneratorDefinition(
                'rest-resource',
                'A REST resource',
                'class',
                'Http/Resources',
                'Http\\Resources',
                'Resource',
            ),
            new GeneratorDefinition('admin-page', 'An admin page', 'class', 'Admin/Pages', 'Admin\\Pages', 'Page'),
            new GeneratorDefinition(
                'builder-component',
                'A builder component',
                'class',
                'Builder/Components',
                'Builder\\Components',
                'Component',
            ),
            new GeneratorDefinition(
                'extension',
                'An OpenMeta extension',
                'extension',
                'Extensions',
                'Extensions',
                'Extension',
            ),
            new GeneratorDefinition('package', 'A package root class', 'class', '', '', 'Package'),
        ];
    }
}
