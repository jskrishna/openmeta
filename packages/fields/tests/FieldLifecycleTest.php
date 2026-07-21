<?php

declare(strict_types=1);

namespace OpenMeta\Fields\Tests;

use OpenMeta\Core\Contracts\EventDispatcherInterface;
use OpenMeta\Fields\Events\FieldLoaded;
use OpenMeta\Fields\Events\FieldSaved;
use OpenMeta\Fields\Events\FieldValidated;
use OpenMeta\Fields\Exceptions\FieldException;
use OpenMeta\Fields\Lifecycle\FieldLifecycle;

final class FieldLifecycleTest extends FieldsTestCase
{
    public function test_register_validate_save_load_render(): void
    {
        $field = $this->fields->make('text', 'headline', [
            'label' => 'Headline',
            'required' => true,
            'max' => 50,
        ]);

        self::assertFalse($this->fields->validate($field, '')->isEmpty());
        self::assertTrue($this->fields->validate($field, 'Hello OpenMeta')->isEmpty());

        $this->fields->save('post', 1, $field, 'Hello OpenMeta');
        self::assertSame('Hello OpenMeta', $this->fields->load('post', 1, $field));

        $html = $this->fields->render($field, 'Hello OpenMeta');
        self::assertStringContainsString('name=headline', $html);
        self::assertStringContainsString('label=Headline', $html);
        self::assertStringContainsString('value=Hello OpenMeta', $html);
        self::assertStringNotContainsString('<', $html);

        $display = $this->fields->display($field, '<b>Hi</b>');
        self::assertSame('&lt;b&gt;Hi&lt;/b&gt;', $display);
    }

    public function test_save_rejects_invalid_value(): void
    {
        $field = $this->fields->make('number', 'price', ['required' => true, 'min' => 10]);

        $this->expectException(FieldException::class);
        $this->fields->save('post', 2, $field, 2);
    }

    public function test_boolean_number_repeater_relationship(): void
    {
        $active = $this->fields->make('boolean', 'active');
        $this->fields->save('post', 3, $active, true);
        self::assertTrue($this->fields->load('post', 3, $active));

        $score = $this->fields->make('number', 'score', ['min' => 0]);
        $this->fields->save('post', 3, $score, 12.5);
        self::assertSame(12.5, $this->fields->load('post', 3, $score));

        $rows = $this->fields->make('repeater', 'items');
        $this->fields->save('post', 3, $rows, [['a' => 1], ['a' => 2]]);
        self::assertCount(2, $this->fields->load('post', 3, $rows));

        $related = $this->fields->make('relationship', 'author_id');
        $this->fields->save('post', 3, $related, 99);
        self::assertSame(99, $this->fields->load('post', 3, $related));

        $many = $this->fields->make('relationship', 'tag_ids', ['multiple' => true]);
        $this->fields->save('post', 3, $many, [1, 2, 3]);
        self::assertSame([1, 2, 3], $this->fields->load('post', 3, $many));
    }

    public function test_manager_dispatches_lifecycle_events(): void
    {
        /** @var EventDispatcherInterface $events */
        $events = $this->app->events();
        $log = [];
        $events->listen(FieldValidated::class, static function () use (&$log): void {
            $log[] = 'validated';
        });
        $events->listen(FieldSaved::class, static function () use (&$log): void {
            $log[] = 'saved';
        });
        $events->listen(FieldLoaded::class, static function () use (&$log): void {
            $log[] = 'loaded';
        });

        $field = $this->fields->make('text', 'body');
        $this->fields->manager()->save('post', 9, $field, 'ok');
        $this->fields->manager()->load('post', 9, $field);

        self::assertSame(['validated', 'saved', 'loaded'], $log);
        self::assertContains('validate_value', FieldLifecycle::phases());
    }

    public function test_delete_removes_value(): void
    {
        $field = $this->fields->make('text', 'temp');
        $this->fields->save('post', 4, $field, 'x');
        self::assertTrue($this->fields->delete('post', 4, $field));
        self::assertNull($this->fields->load('post', 4, $field));
    }
}
