<?php

declare(strict_types=1);

namespace OpenMeta\Builder\Tests\Security;

use OpenMeta\Builder\Application\BuilderApplication;
use OpenMeta\Builder\Exceptions\BuilderException;
use OpenMeta\Builder\Tests\BuilderTestCase;

final class SaveAuthzTest extends BuilderTestCase
{
    public function test_save_denied_without_permission(): void
    {
        $this->expectException(BuilderException::class);
        $this->builder->save($this->nonce->create(BuilderApplication::SCREEN_ID));
    }
}
