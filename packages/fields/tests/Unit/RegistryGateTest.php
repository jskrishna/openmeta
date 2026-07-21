<?php

declare(strict_types=1);

namespace OpenMeta\Fields\Tests\Unit;

use OpenMeta\Fields\Tests\FieldsTestCase;

final class RegistryGateTest extends FieldsTestCase
{
    public function test_text_type_registered(): void
    {
        $field = $this->fields->make('text', 'title', ['label' => 'Title']);
        $this->assertSame('text', $field->type());
    }
}
