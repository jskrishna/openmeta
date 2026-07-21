<?php

declare(strict_types=1);

namespace OpenMeta\Builder\Tests;

use OpenMeta\Builder\Application\BuilderApplication;
use OpenMeta\Builder\Canvas\CanvasNode;
use OpenMeta\Builder\Events\ComponentAdded;
use OpenMeta\Builder\Events\SchemaSaved;
use OpenMeta\Builder\Exceptions\BuilderException;
use OpenMeta\Security\Permissions\Permission;

final class BuilderTest extends BuilderTestCase
{
    public function test_canvas_selection_and_layout(): void
    {
        $node = new CanvasNode('a', 'text', 'title', ['label' => 'Title']);
        $this->canvas->add($node);
        $this->canvas->select('a');

        $this->assertSame('a', $this->canvas->selectedId());
        $this->assertSame(1, $this->canvas->count());
        $this->assertSame('title', $this->canvas->selected()?->name);
    }

    public function test_workspace_zoom_and_snap(): void
    {
        $workspace = $this->canvas->workspace()->withZoom(2.0);
        $this->canvas->setWorkspace($workspace);

        $this->assertSame(2.0, $this->canvas->workspace()->zoom());
        $this->assertSame(16, $this->canvas->workspace()->snap(15));
    }

    public function test_drag_drop_insert_and_keyboard_move(): void
    {
        $this->dragDrop->dropNew($this->canvas, 'text', 'first');
        $this->dragDrop->dropNew($this->canvas, 'number', 'second');
        $this->assertSame(2, $this->canvas->count());

        $first = $this->canvas->nodes()[0];
        $this->canvas->select($first->id);
        $this->dragDrop->moveSelected($this->canvas, 'down');

        $this->assertSame('second', $this->canvas->nodes()[0]->name);
        $this->assertSame('first', $this->canvas->nodes()[1]->name);
    }

    public function test_drag_drop_rejects_unknown_type(): void
    {
        $this->expectException(BuilderException::class);
        $this->dragDrop->dropNew($this->canvas, 'not-a-type', 'x');
    }

    public function test_templates_apply_and_preview(): void
    {
        $preview = $this->templates->preview('contact');
        $this->assertCount(3, $preview);

        $this->templates->apply('contact', $this->canvas);
        $this->assertSame(3, $this->canvas->count());
        $this->assertSame('email', $this->canvas->nodes()[1]->name);
    }

    public function test_conditions_use_field_engine(): void
    {
        $condition = $this->conditions->equals('status', 'open');
        $this->assertTrue($this->conditions->evaluate($condition, ['status' => 'open']));
        $this->assertFalse($this->conditions->evaluate($condition, ['status' => 'closed']));

        $nodes = [
            new CanvasNode('1', 'text', 'a'),
            new CanvasNode('2', 'text', 'b', [], $this->conditions->notEmpty('show')),
        ];
        $visible = $this->conditions->visibleNodes($nodes, ['show' => 'yes']);
        $this->assertCount(2, $visible);

        $hidden = $this->conditions->visibleNodes($nodes, ['show' => '']);
        $this->assertCount(1, $hidden);
    }

    public function test_session_save_and_discard_pipeline(): void
    {
        $this->grant('manage_options');

        $this->templates->apply('contact', $this->canvas);
        $session = $this->builder->sessionState();
        $this->assertSame(BuilderApplication::SCREEN_ID, $session['screen']);
        $this->assertArrayHasKey('schema', $session);

        $token = $this->nonce->create(BuilderApplication::SCREEN_ID);
        $saved = $this->builder->save($token);
        $this->assertSame('1.0.0', $saved['version']);
        $this->assertSame($saved, $this->builder->saved());
    }

    public function test_save_requires_permission(): void
    {
        $this->expectException(BuilderException::class);
        $this->builder->save($this->nonce->create(BuilderApplication::SCREEN_ID));
    }

    public function test_discard_restores_snapshot(): void
    {
        $this->grant('manage_options');
        $this->templates->apply('contact', $this->canvas);
        $token = $this->nonce->create(BuilderApplication::SCREEN_ID);
        $this->builder->save($token);

        $this->dragDrop->dropNew($this->canvas, 'boolean', 'extra');
        $this->assertSame(4, $this->canvas->count());

        $this->builder->discard();
        $this->assertSame(3, $this->builder->canvas()->count());
    }

    public function test_admin_slot_mount_smoke(): void
    {
        $this->assertTrue($this->screens->has(BuilderApplication::SCREEN_ID));
        $this->assertTrue($this->menus->has('openmeta-builder'));
        $this->assertSame(Permission::MANAGE_FIELDS, $this->menus->get('openmeta-builder')?->permission);
    }

    public function test_canvas_scale_budget(): void
    {
        $start = hrtime(true);
        for ($i = 0; $i < 200; $i++) {
            $this->dragDrop->dropNew($this->canvas, 'text', 'f' . $i);
        }
        $elapsedMs = (hrtime(true) - $start) / 1e6;

        $this->assertSame(200, $this->canvas->count());
        $this->assertLessThan(500.0, $elapsedMs, 'Canvas scale insert budget exceeded');
    }

    public function test_preview_respects_conditions(): void
    {
        $this->grant('manage_options');
        $this->canvas->add(new CanvasNode('1', 'text', 'email', ['label' => 'Email']));
        $this->canvas->add(new CanvasNode(
            '2',
            'text',
            'company',
            ['label' => 'Company'],
            $this->conditions->notEmpty('email'),
        ));

        $result = $this->builder->generatePreview(['email' => 'a@b.c']);
        $this->assertSame(2, $result->visibleNodes);

        $hidden = $this->builder->generatePreview(['email' => '']);
        $this->assertSame(1, $hidden->visibleNodes);
    }

    public function test_component_registry_discovery(): void
    {
        $this->assertTrue($this->registry->has('text'));
        $this->assertNotEmpty($this->registry->inCategory('fields'));
    }

    public function test_builder_facade_exposes_public_api(): void
    {
        $this->grant('manage_options');
        $this->assertSame($this->canvas, $this->facade->canvas());
        $this->assertSame($this->schema, $this->facade->schema());
        $this->assertArrayHasKey('schema', $this->facade->sessionState());
    }

    public function test_events_dispatch_on_add_and_save(): void
    {
        $this->grant('manage_options');
        $events = [];
        $this->events->listen(ComponentAdded::class, static function (ComponentAdded $event) use (&$events): void {
            $events[] = $event::class;
        });
        $this->events->listen(SchemaSaved::class, static function (SchemaSaved $event) use (&$events): void {
            $events[] = $event::class;
        });

        $this->builder->addComponent(new CanvasNode('x1', 'text', 'title'));
        $this->builder->save($this->nonce->create(BuilderApplication::SCREEN_ID));

        $this->assertContains(ComponentAdded::class, $events);
        $this->assertContains(SchemaSaved::class, $events);
    }
}
