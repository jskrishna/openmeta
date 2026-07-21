<?php

declare(strict_types=1);

namespace OpenMeta\Security\Tests;

use OpenMeta\Security\Authorization\Authorizer;
use OpenMeta\Security\CSRF\CsrfTokenManager;
use OpenMeta\Security\Capabilities\ArrayCapabilityChecker;
use OpenMeta\Security\Contracts\PolicyInterface;
use OpenMeta\Security\Exceptions\CsrfException;
use OpenMeta\Security\Exceptions\InvalidTokenException;
use OpenMeta\Security\Exceptions\PermissionDeniedException;
use OpenMeta\Security\Hashing\Hasher;
use OpenMeta\Security\Hashing\PasswordHasher;
use OpenMeta\Security\Permissions\Gate;
use OpenMeta\Security\Permissions\Permission;
use OpenMeta\Security\Permissions\PermissionMap;
use OpenMeta\Security\Random\SecureRandom;
use OpenMeta\Security\Support\ConstantTime\Comparator;
use OpenMeta\Security\Tokens\TokenGenerator;

final class CryptoAndAuthzTest extends SecurityTestCase
{
    public function test_password_hasher_and_timing_safe_compare(): void
    {
        $hasher = new PasswordHasher();
        $hash = $hasher->hash('secret-pass');

        self::assertTrue($hasher->verify('secret-pass', $hash));
        self::assertFalse($hasher->verify('wrong', $hash));
        self::assertTrue(Comparator::equals('abc', 'abc'));
        self::assertFalse(Hasher::equals('abc', 'abd'));
    }

    public function test_secure_random_and_tokens(): void
    {
        $random = new SecureRandom();
        self::assertSame(64, strlen($random->hex(32)));
        self::assertNotSame($random->token(), $random->token());

        $tokens = new TokenGenerator($random, 'unit-test-secret');
        $signed = $tokens->signed('payload', 60);
        self::assertSame('payload', $tokens->verifySigned($signed));
        self::assertNotEmpty($tokens->uuid());

        $this->expectException(InvalidTokenException::class);
        $tokens->verifySigned('bad.token.here');
    }

    public function test_csrf_generate_validate_rotate(): void
    {
        $csrf = new CsrfTokenManager(new SecureRandom(), 'csrf-secret', 3600);
        $token = $csrf->generate();
        self::assertTrue($csrf->isValid($token));
        $csrf->validate($token);

        $rotated = $csrf->rotate();
        self::assertNotSame($token, $rotated);
        self::assertTrue($csrf->isValid($rotated));

        $this->expectException(CsrfException::class);
        $csrf->validate('invalid');
    }

    public function test_authorizer_permission_and_policy(): void
    {
        $checker = new ArrayCapabilityChecker(['manage_options']);
        $gate = new Gate(new PermissionMap(), $checker);
        $authz = new Authorizer($gate);

        self::assertTrue($authz->can(Permission::MANAGE_FIELDS));
        $authz->denyUnless(Permission::MANAGE_FIELDS);

        $authz->registerPolicy('demo', new class implements PolicyInterface {
            public function allows(string $ability, mixed $subject, mixed $resource = null, array $context = []): bool
            {
                return $ability === 'edit' && $subject === 'owner';
            }
        });

        self::assertTrue($authz->allows('demo', 'edit', 'owner'));
        self::assertFalse($authz->allows('demo', 'edit', 'guest'));

        $denied = new Authorizer(new Gate(new PermissionMap(), new ArrayCapabilityChecker()));
        $this->expectException(PermissionDeniedException::class);
        $denied->denyUnless(Permission::MANAGE_FIELDS);
    }
}
