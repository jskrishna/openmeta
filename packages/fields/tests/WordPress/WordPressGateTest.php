<?php

declare(strict_types=1);

namespace OpenMeta\Fields\Tests\WordPress;

use OpenMeta\Fields\Tests\FieldsTestCase;

/** Storage uses DB connection; WP meta bridge smoke = memory path without WP. */
final class WordPressGateTest extends FieldsTestCase
{
    public function test_field_engine_works_without_wordpress(): void
    {
        $field = $this->fields->make('boolean', 'flag');
        $this->assertSame('boolean', $field->type());
    }
}
