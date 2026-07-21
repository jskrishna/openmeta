<?php

declare(strict_types=1);

namespace OpenMeta\Builder\Tests\Unit;

use OpenMeta\Builder\Canvas\CanvasNode;
use OpenMeta\Builder\Tests\BuilderTestCase;

final class CanvasGateTest extends BuilderTestCase
{
    public function test_add_and_select(): void
    {
        $this->canvas->add(new CanvasNode('n1', 'text', 'title'));
        $this->canvas->select('n1');
        $this->assertSame('n1', $this->canvas->selectedId());
    }
}
