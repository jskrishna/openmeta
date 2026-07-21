<?php

declare(strict_types=1);

namespace OpenMeta\Builder;

use OpenMeta\Admin\Menus\MenuItem;
use OpenMeta\Admin\Menus\MenuRegistry;
use OpenMeta\Admin\Screens\Screen;
use OpenMeta\Admin\Screens\ScreenRegistry;
use OpenMeta\Builder\App\VisualBuilder;
use OpenMeta\Builder\Application\BuilderApplication;
use OpenMeta\Builder\Canvas\Canvas;
use OpenMeta\Builder\Clipboard\ClipboardManager;
use OpenMeta\Builder\Conditions\ConditionBuilder;
use OpenMeta\Builder\DragDrop\DragDrop;
use OpenMeta\Builder\DragDrop\DropValidator;
use OpenMeta\Builder\History\HistoryManager;
use OpenMeta\Builder\Inspector\InspectorPanel;
use OpenMeta\Builder\Layouts\LayoutEngine;
use OpenMeta\Builder\Library\BlockLibrary;
use OpenMeta\Builder\Preview\PreviewEngine;
use OpenMeta\Builder\Properties\PropertyEditor;
use OpenMeta\Builder\Registry\ComponentDescriptor;
use OpenMeta\Builder\Registry\ComponentRegistry;
use OpenMeta\Builder\Schema\SchemaManager;
use OpenMeta\Builder\Serialization\SchemaImporter;
use OpenMeta\Builder\Serialization\SchemaMigrator;
use OpenMeta\Builder\Serialization\SchemaSerializer;
use OpenMeta\Builder\Templates\TemplateManager;
use OpenMeta\Builder\Templates\TemplateRegistry;
use OpenMeta\Core\Contracts\ContainerInterface;
use OpenMeta\Core\Contracts\EventDispatcherInterface;
use OpenMeta\Core\Providers\ServiceProvider;
use OpenMeta\Fields\Conditions\ConditionEvaluator as FieldsConditionEvaluator;
use OpenMeta\Fields\FieldEngine;
use OpenMeta\Fields\Registry\FieldRegistry;
use OpenMeta\Security\Nonce\Nonce;
use OpenMeta\Security\Permissions\Gate;
use OpenMeta\Security\Permissions\Permission;

/**
 * Binds builder services and mounts into admin screens/menus.
 */
final class BuilderServiceProvider extends ServiceProvider
{
    public function register(ContainerInterface $container): void
    {
        $container->singleton(Canvas::class, static fn (): Canvas => new Canvas());

        $container->singleton(LayoutEngine::class, static function (): LayoutEngine {
            $engine = new LayoutEngine();

            return $engine;
        });

        $container->singleton(ComponentRegistry::class, static function (ContainerInterface $c): ComponentRegistry {
            $registry = new ComponentRegistry();
            /** @var FieldRegistry $fields */
            $fields = $c->get(FieldRegistry::class);

            foreach ($fields->all() as $type) {
                $registry->register(new ComponentDescriptor(
                    $type,
                    ucfirst($type),
                    'fields',
                    '1.0.0',
                    [],
                    static fn (): array => ['type' => $type],
                ));
            }

            $registry->register(new ComponentDescriptor('section', 'Section', 'layout', '1.0.0'));

            return $registry;
        });

        $container->singleton(SchemaSerializer::class, static fn (): SchemaSerializer => new SchemaSerializer());
        $container->singleton(SchemaMigrator::class, static fn (): SchemaMigrator => new SchemaMigrator());
        $container->singleton(SchemaImporter::class, static function (ContainerInterface $c): SchemaImporter {
            return new SchemaImporter($c->get(SchemaSerializer::class));
        });

        $container->singleton(SchemaManager::class, static function (ContainerInterface $c): SchemaManager {
            return new SchemaManager(
                $c->get(Canvas::class),
                $c->get(LayoutEngine::class),
                $c->get(SchemaSerializer::class),
                $c->get(SchemaImporter::class),
                $c->get(SchemaMigrator::class),
            );
        });

        $container->singleton(HistoryManager::class, static function (ContainerInterface $c): HistoryManager {
            /** @var SchemaManager $schema */
            $schema = $c->get(SchemaManager::class);

            return new HistoryManager(
                static fn (): array => $schema->build(),
                static function (array $state) use ($schema): void {
                    $schema->load($state);
                },
            );
        });

        $container->singleton(ConditionBuilder::class, static function (ContainerInterface $c): ConditionBuilder {
            return new ConditionBuilder($c->get(FieldsConditionEvaluator::class));
        });

        $container->singleton(TemplateRegistry::class, static function (): TemplateRegistry {
            $registry = new TemplateRegistry();
            $registry->register('blank', 'Blank', [], 'starter');
            $registry->register('contact', 'Contact form', [
                [
                    'id' => 'n1',
                    'type' => 'text',
                    'name' => 'name',
                    'settings' => ['label' => 'Name', 'required' => true],
                ],
                [
                    'id' => 'n2',
                    'type' => 'text',
                    'name' => 'email',
                    'settings' => ['label' => 'Email', 'required' => true],
                ],
                [
                    'id' => 'n3',
                    'type' => 'text',
                    'name' => 'company',
                    'settings' => ['label' => 'Company'],
                    'condition' => ['field' => 'email', 'operator' => 'not_empty'],
                ],
            ], 'forms');

            return $registry;
        });

        $container->singleton(TemplateManager::class, static function (ContainerInterface $c): TemplateManager {
            return new TemplateManager(
                $c->get(TemplateRegistry::class),
                $c->get(EventDispatcherInterface::class),
            );
        });

        $container->singleton(DropValidator::class, static function (ContainerInterface $c): DropValidator {
            return new DropValidator($c->get(FieldRegistry::class));
        });

        $container->singleton(DragDrop::class, static function (ContainerInterface $c): DragDrop {
            return new DragDrop(
                $c->get(FieldRegistry::class),
                $c->get(DropValidator::class),
            );
        });

        $container->singleton(PreviewEngine::class, static function (ContainerInterface $c): PreviewEngine {
            return new PreviewEngine(
                $c->get(FieldEngine::class),
                $c->get(ConditionBuilder::class),
            );
        });

        $container->singleton(PropertyEditor::class, static fn (): PropertyEditor => new PropertyEditor());
        $container->singleton(InspectorPanel::class, static function (ContainerInterface $c): InspectorPanel {
            return new InspectorPanel($c->get(PropertyEditor::class));
        });

        $container->singleton(ClipboardManager::class, static fn (): ClipboardManager => new ClipboardManager());

        $container->singleton(BlockLibrary::class, static function (ContainerInterface $c): BlockLibrary {
            return new BlockLibrary(
                $c->get(ComponentRegistry::class),
                $c->get(LayoutEngine::class),
                $c->get(TemplateRegistry::class),
            );
        });

        $container->singleton(BuilderApplication::class, static function (ContainerInterface $c): BuilderApplication {
            return new VisualBuilder(
                $c->get(SchemaManager::class),
                $c->get(ComponentRegistry::class),
                $c->get(DragDrop::class),
                $c->get(TemplateManager::class),
                $c->get(ConditionBuilder::class),
                $c->get(PreviewEngine::class),
                $c->get(HistoryManager::class),
                $c->get(ClipboardManager::class),
                $c->get(BlockLibrary::class),
                $c->get(LayoutEngine::class),
                $c->get(PropertyEditor::class),
                $c->get(InspectorPanel::class),
                $c->get(FieldEngine::class),
                $c->get(Gate::class),
                $c->get(Nonce::class),
                $c->get(EventDispatcherInterface::class),
            );
        });

        $container->singleton(Builder::class, static function (ContainerInterface $c): Builder {
            return new Builder($c->get(BuilderApplication::class));
        });

        $container->alias(BuilderApplication::class, 'builder.app');
        $container->alias(Builder::class, 'builder');
        $container->alias(BuilderApplication::class, VisualBuilder::class);
    }

    public function boot(ContainerInterface $container): void
    {
        if (! $container->has(ScreenRegistry::class) || ! $container->has(MenuRegistry::class)) {
            return;
        }

        /** @var ScreenRegistry $screens */
        $screens = $container->get(ScreenRegistry::class);
        /** @var MenuRegistry $menus */
        $menus = $container->get(MenuRegistry::class);
        /** @var BuilderApplication $builder */
        $builder = $container->get(BuilderApplication::class);

        $menus->add(new MenuItem(
            'openmeta-builder',
            'Builder',
            BuilderApplication::SCREEN_ID,
            Permission::MANAGE_FIELDS,
            'openmeta',
            15
        ));

        $screens->register(new Screen(
            BuilderApplication::SCREEN_ID,
            'Visual Builder',
            Permission::MANAGE_FIELDS,
            static function () use ($builder): string {
                $state = $builder->sessionState();

                return (string) json_encode($state, JSON_THROW_ON_ERROR);
            }
        ));
    }
}
