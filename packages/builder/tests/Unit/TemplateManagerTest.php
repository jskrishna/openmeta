<?php

declare(strict_types=1);

namespace OpenMeta\Builder\Tests\Unit;

use OpenMeta\Builder\Events\TemplateImported;
use OpenMeta\Builder\Tests\BuilderTestCase;

final class TemplateManagerTest extends BuilderTestCase
{
    public function test_import_dispatches_event_and_registers_template(): void
    {
        $this->grant('manage_options');
        $seen = false;
        $this->events->listen(TemplateImported::class, static function () use (&$seen): void {
            $seen = true;
        });

        $this->builder->templates()->import([
            'id' => 'lead',
            'title' => 'Lead capture',
            'category' => 'forms',
            'nodes' => [
                ['id' => 'n1', 'type' => 'text', 'name' => 'email', 'settings' => ['label' => 'Email']],
            ],
        ]);

        $this->assertTrue($seen);
        $this->assertTrue($this->templates->has('lead'));
    }
}
