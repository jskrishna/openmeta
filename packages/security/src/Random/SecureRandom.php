<?php

declare(strict_types=1);

namespace OpenMeta\Security\Random;

use OpenMeta\Security\Contracts\SecureRandomInterface;
use OpenMeta\Security\Exceptions\SecurityConfigurationException;
use Random\RandomException;

/**
 * CSPRNG-backed random generator.
 */
final class SecureRandom implements SecureRandomInterface
{
    public function bytes(int $length): string
    {
        if ($length < 1) {
            throw new SecurityConfigurationException('Random byte length must be >= 1.');
        }

        try {
            return random_bytes($length);
        } catch (RandomException $e) {
            throw new SecurityConfigurationException('CSPRNG unavailable.', 0, $e);
        }
    }

    public function hex(int $length): string
    {
        return bin2hex($this->bytes($length));
    }

    public function token(int $length = 32): string
    {
        return rtrim(strtr(base64_encode($this->bytes($length)), '+/', '-_'), '=');
    }
}
