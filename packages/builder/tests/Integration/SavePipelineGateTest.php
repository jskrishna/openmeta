<?php

declare(strict_types=1);

namespace OpenMeta\Builder\Tests\Integration;

use OpenMeta\Builder\Application\BuilderApplication;
use OpenMeta\Builder\Tests\BuilderTestCase;

final class SavePipelineGateTest extends BuilderTestCase
{
    public function test_save_with_nonce(): void
    {
        $this->grant('manage_options');
        $this->templates->apply('contact', $this->canvas);
        $saved = $this->builder->save($this->nonce->create(BuilderApplication::SCREEN_ID));
        $this->assertNotEmpty($saved);
    }
}
