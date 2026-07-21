<?php

declare(strict_types=1);

namespace OpenMeta\Validation\Tests;

use OpenMeta\Validation\Messages\MessageBag;

final class MessageBagTest extends ValidationTestCase
{
    public function test_replaces_placeholders_and_custom_templates(): void
    {
        $bag = new MessageBag([
            'email.required' => 'Email missing.',
            'min' => ':attribute needs :min chars.',
        ]);

        self::assertSame(
            'Email missing.',
            $bag->make('email', 'required', 'The :attribute field is required.')
        );

        self::assertSame(
            'name needs 3 chars.',
            $bag->make('name', 'min', 'fallback', ['min' => 3])
        );
    }
}
