<?php

declare(strict_types=1);

namespace OpenMeta\Security\Tests\Integration;

use OpenMeta\Security\Nonce\HmacNonceHandler;
use OpenMeta\Security\Nonce\Nonce;
use OpenMeta\Security\Tests\SecurityTestCase;

final class NonceGateIntegrationTest extends SecurityTestCase
{
    public function test_create_and_check_nonce(): void
    {
        $nonce = new Nonce(new HmacNonceHandler('phase12-secret'));
        $token = $nonce->create('phase12');
        $nonce->check($token, 'phase12');
        $this->assertNotSame('', $token);
    }
}
