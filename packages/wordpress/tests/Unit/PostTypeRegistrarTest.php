<?php

declare(strict_types=1);

namespace OpenMeta\Wordpress\Tests\Unit;

use OpenMeta\Wordpress\PostTypes\PostTypeRegistrar;
use OpenMeta\Wordpress\Tests\WordpressTestCase;

final class PostTypeRegistrarTest extends WordpressTestCase
{
    public function test_records_registration_on_array_runtime(): void
    {
        $registrar = new PostTypeRegistrar($this->wp);

        $registrar->register([
            'openmeta_book' => [
                'label' => 'Books',
                'public' => true,
            ],
        ]);

        $this->assertCount(1, $this->wp->postTypes);
        $this->assertSame('openmeta_book', $this->wp->postTypes[0]['post_type']);
        $this->assertSame('Books', $this->wp->postTypes[0]['args']['label']);
    }
}
