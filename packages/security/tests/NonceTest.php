<?php

declare(strict_types=1);

namespace OpenMeta\Security\Tests;

use OpenMeta\Security\Exceptions\InvalidNonceException;
use OpenMeta\Security\Nonce\HmacNonceHandler;
use OpenMeta\Security\Nonce\Nonce;

final class NonceTest extends SecurityTestCase
{
    public function test_create_and_verify(): void
    {
        $nonce = new Nonce(new HmacNonceHandler('test-secret-key'));
        $token = $nonce->create('save_field');

        self::assertNotSame('', $token);
        self::assertTrue($nonce->verify($token, 'save_field'));
        self::assertFalse($nonce->verify($token, 'other_action'));
        self::assertFalse($nonce->verify('', 'save_field'));
    }

    public function test_check_throws_on_invalid(): void
    {
        $nonce = new Nonce(new HmacNonceHandler('test-secret-key'));

        $this->expectException(InvalidNonceException::class);
        $nonce->check('bad', 'save_field');
    }

    public function test_field_renders_hidden_input(): void
    {
        $nonce = new Nonce(new HmacNonceHandler('test-secret-key'));
        $html = $nonce->field('save_field');

        self::assertStringContainsString('type="hidden"', $html);
        self::assertStringContainsString('name="_wpnonce"', $html);
        self::assertStringContainsString('value="', $html);
    }
}
