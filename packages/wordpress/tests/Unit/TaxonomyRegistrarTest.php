<?php

declare(strict_types=1);

namespace OpenMeta\Wordpress\Tests\Unit;

use OpenMeta\Wordpress\Taxonomies\TaxonomyRegistrar;
use OpenMeta\Wordpress\Tests\WordpressTestCase;

final class TaxonomyRegistrarTest extends WordpressTestCase
{
    public function test_records_taxonomy_on_array_runtime(): void
    {
        $registrar = new TaxonomyRegistrar($this->wp);

        $registrar->register([
            'openmeta_genre' => [
                'object_type' => ['openmeta_book'],
                'args' => [
                    'label' => 'Genres',
                    'hierarchical' => true,
                ],
            ],
        ]);

        $this->assertCount(1, $this->wp->taxonomies);
        $this->assertSame('openmeta_genre', $this->wp->taxonomies[0]['taxonomy']);
        $this->assertSame(['openmeta_book'], $this->wp->taxonomies[0]['object_type']);
        $this->assertTrue($this->wp->taxonomies[0]['args']['hierarchical']);
    }
}
