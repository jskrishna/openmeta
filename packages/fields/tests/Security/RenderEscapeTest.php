<?php

declare(strict_types=1);

namespace OpenMeta\Fields\Tests\Security;

use OpenMeta\Fields\Tests\FieldsTestCase;

final class RenderEscapeTest extends FieldsTestCase
{
    public function test_render_escapes_html_in_values_without_emitting_markup(): void
    {
        $field = $this->fields->make('text', 'bio', ['label' => 'Bio']);
        $rendered = $this->fields->render($field, '<script>x</script>', 'edit');

        $this->assertStringNotContainsString('<script>x</script>', $rendered);
        $this->assertStringNotContainsString('<input', $rendered);
        $this->assertStringNotContainsString('<label', $rendered);
        $this->assertStringContainsString('&lt;script&gt;x&lt;/script&gt;', $rendered);
    }
}
