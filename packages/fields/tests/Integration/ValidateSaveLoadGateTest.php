<?php

declare(strict_types=1);

namespace OpenMeta\Fields\Tests\Integration;

use OpenMeta\Fields\Tests\FieldsTestCase;

final class ValidateSaveLoadGateTest extends FieldsTestCase
{
    public function test_validate_save_load_roundtrip(): void
    {
        $field = $this->fields->make('text', 'headline', ['required' => true]);
        $this->assertTrue($this->fields->validate($field, 'Hello')->isEmpty());
        $this->fields->save('post', 1, $field, 'Hello');
        $this->assertSame('Hello', $this->fields->load('post', 1, $field));
    }
}
