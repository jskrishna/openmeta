<?php

declare(strict_types=1);

namespace OpenMeta\Fields\Tests;

use OpenMeta\Fields\Exceptions\SerializationFailureException;
use OpenMeta\Fields\Hydration\FieldHydrator;
use OpenMeta\Fields\Serialization\ArraySerializer;
use OpenMeta\Fields\Serialization\JsonSerializer;
use OpenMeta\Fields\Serialization\ObjectSerializer;
use OpenMeta\Fields\Serialization\SerializerRegistry;
use stdClass;

final class FieldSerializationHydrationTest extends FieldsTestCase
{
    public function test_array_json_object_serializers(): void
    {
        $field = $this->fields->make('object', 'meta');
        $arrays = new ArraySerializer();
        $json = new JsonSerializer($arrays);
        $objects = new ObjectSerializer($arrays);

        $payload = ['a' => 1, 'b' => 'x'];
        self::assertSame($payload, $arrays->serialize($field, $payload));
        self::assertSame($payload, $arrays->deserialize($field, $payload));

        $encoded = $json->serialize($field, $payload);
        self::assertSame('{"a":1,"b":"x"}', $encoded);
        self::assertSame($payload, $json->deserialize($field, $encoded));

        $object = $objects->serialize($field, $payload);
        self::assertInstanceOf(stdClass::class, $object);
        self::assertSame($payload, $objects->deserialize($field, $object));
    }

    public function test_serializer_registry_and_json_failure(): void
    {
        $registry = $this->app->get(SerializerRegistry::class);
        self::assertTrue($registry->has('array'));
        self::assertTrue($registry->has('json'));

        $field = $this->fields->make('text', 't');
        $json = new JsonSerializer();
        $this->expectException(SerializationFailureException::class);
        $json->deserialize($field, '{broken');
    }

    public function test_hydrator_applies_default_and_cast(): void
    {
        $hydrator = new FieldHydrator();
        $field = $this->fields->make('number', 'score', ['default' => 10]);

        self::assertSame(10.0, $hydrator->hydrate($field, null));
        self::assertSame(3.5, $hydrator->hydrate($field, '3.5'));

        $title = $this->fields->make('text', 'title');
        $many = $hydrator->hydrateMany(['title' => $title], ['title' => 'Hi']);
        self::assertSame('Hi', $many['title']);
    }
}
