<?php

declare(strict_types=1);

namespace OpenMeta\Api\Auth;

use OpenMeta\Api\Exceptions\AuthenticationException;
use OpenMeta\Api\Rest\Request;

/**
 * Bearer token map for CI / non-WP runtimes. Fail closed when required and missing.
 */
final class TokenAuthenticator implements AuthenticatorInterface
{
    /** @param array<string, mixed> $tokens token => identity */
    public function __construct(private array $tokens = [])
    {
    }

    public function issue(string $token, mixed $identity): void
    {
        $this->tokens[$token] = $identity;
    }

    public function authenticate(Request $request, bool $required): mixed
    {
        $header = $request->header('Authorization', '');
        $token = null;

        if (is_string($header) && str_starts_with($header, 'Bearer ')) {
            $token = substr($header, 7);
        }

        if ($token === null || $token === '') {
            if ($required) {
                throw new AuthenticationException();
            }

            return null;
        }

        if (! array_key_exists($token, $this->tokens)) {
            throw new AuthenticationException('Invalid credentials.');
        }

        return $this->tokens[$token];
    }
}
