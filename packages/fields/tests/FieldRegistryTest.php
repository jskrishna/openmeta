<?php

declare(strict_types=1);

namespace OpenMeta\Fields\Tests;

use OpenMeta\Core\Contracts\EventDispatcherInterface;
use OpenMeta\Fields\Definitions\FieldDefinition;
use OpenMeta\Fields\Events\FieldCreated;
use OpenMeta\Fields\Events\FieldRegistered;
use OpenMeta\Fields\Exceptions\InvalidDefinitionException;
use OpenMeta\Fields\Exceptions\UnknownFieldTypeException;
use OpenMeta\Fields\Support\BuiltInTypes;
use OpenMeta\Fields\Types\TextField;

final class FieldRegistryTest extends FieldsTestCase
{
    public function test_register_and_resolve_built_ins(): void
    {
        self::assertTrue($this->fields->registry()->has('text'));
        self::assertTrue($this->fields->registry()->has('number'));
        self::assertTrue($this->fields->registry()->has('boolean'));
        self::assertTrue($this->fields->registry()->has('repeater'));
        self::assertTrue($this->fields->registry()->has('relationship'));
        self::assertTrue($this->fields->registry()->has('email'));
        self::assertTrue($this->fields->registry()->has('multiselect'));

        $field = $this->fields->make('text', 'title', ['label' => 'Title', 'required' => true]);
        self::assertInstanceOf(TextField::class, $field);
        self::assertSame('title', $field->name());
        self::assertSame('Title', $field->label());
    }

    public function test_discovers_all_builtin_types(): void
    {
        foreach (array_keys(BuiltInTypes::map()) as $type) {
            self::assertTrue($this->fields->registry()->has($type), $type);
        }
    }

    public function test_aliases_and_remove(): void
    {
        self::assertTrue($this->fields->registry()->has('bool'));
        $field = $this->fields->make('bool', 'flag');
        self::assertSame('boolean', $field->type());

        $this->fields->registry()->alias('plain', 'text');
        self::assertSame('text', $this->fields->make('plain', 'x')->type());

        $this->fields->register('tmp', TextField::class);
        self::assertTrue($this->fields->registry()->has('tmp'));
        $this->fields->registry()->remove('tmp');
        self::assertFalse($this->fields->registry()->has('tmp'));
    }

    public function test_versioning_support(): void
    {
        $this->fields->register('widget', TextField::class, '1.0');
        $this->fields->registry()->register(
            'widget',
            static fn (string $name, array $settings): TextField => new TextField($name, $settings + ['v' => 2]),
            '2.0'
        );

        self::assertContains('1.0', $this->fields->registry()->versions('widget'));
        self::assertContains('2.0', $this->fields->registry()->versions('widget'));

        $v2 = $this->fields->registry()->make('widget', 'w', [], '2.0');
        self::assertSame(2, $v2->setting('v'));
    }

    public function test_custom_type_registration(): void
    {
        $this->fields->register('slug', TextField::class);
        $field = $this->fields->make('slug', 'permalink');
        self::assertSame('text', $field->type());
    }

    public function test_unknown_type_throws(): void
    {
        $this->expectException(UnknownFieldTypeException::class);
        $this->fields->make('nope', 'x');
    }

    public function test_factory_and_definition(): void
    {
        $definition = FieldDefinition::fromArray([
            'name' => 'headline',
            'type' => 'text',
            'label' => 'Headline',
            'required' => true,
            'description' => 'Main title',
        ]);

        $field = $this->fields->factory()->makeFromDefinition($definition);
        self::assertSame('headline', $field->name());
        self::assertSame('Headline', $field->label());
        self::assertTrue($field->isRequired());

        $immutable = $definition->withRequired(false);
        self::assertTrue($definition->isRequired());
        self::assertFalse($immutable->isRequired());
    }

    public function test_invalid_definition_throws(): void
    {
        $this->expectException(InvalidDefinitionException::class);
        FieldDefinition::fromArray(['name' => '', 'type' => 'text']);
    }

    public function test_registration_dispatches_events(): void
    {
        /** @var EventDispatcherInterface $events */
        $events = $this->app->events();
        $seen = [];
        $events->listen(FieldRegistered::class, static function (FieldRegistered $e) use (&$seen): void {
            $seen[] = $e->type;
        });
        $events->listen(FieldCreated::class, static function (FieldCreated $e) use (&$seen): void {
            $seen[] = 'created:' . $e->field->name();
        });

        $this->fields->register('evt', TextField::class);
        $this->fields->make('evt', 'demo');

        self::assertContains('evt', $seen);
        self::assertContains('created:demo', $seen);
    }
}
