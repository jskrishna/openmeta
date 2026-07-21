<?php

declare(strict_types=1);

namespace OpenMeta\Security\Tests;

use OpenMeta\Core\Bootstrap\Bootstrap;
use OpenMeta\Security\Capabilities\ArrayCapabilityChecker;
use OpenMeta\Security\Contracts\CapabilityCheckerInterface;
use OpenMeta\Security\Nonce\Nonce;
use OpenMeta\Security\Permissions\Gate;
use OpenMeta\Security\Permissions\Permission;
use OpenMeta\Security\SecurityServiceProvider;

final class SecurityIntegrationTest extends SecurityTestCase
{
    public function test_provider_wires_gate_and_nonce_deny_paths(): void
    {
        $app = Bootstrap::run(
            ['app' => ['key' => 'integration-secret']],
            [SecurityServiceProvider::class]
        );

        /** @var ArrayCapabilityChecker $caps */
        $caps = $app->get(CapabilityCheckerInterface::class);
        self::assertInstanceOf(ArrayCapabilityChecker::class, $caps);

        /** @var Gate $gate */
        $gate = $app->get(Gate::class);
        self::assertTrue($gate->cannot(Permission::MANAGE));

        $caps->grant(['manage_options']);
        $gate->flushCache();

        self::assertTrue($gate->can(Permission::MANAGE));

        /** @var Nonce $nonce */
        $nonce = $app->get(Nonce::class);
        $token = $nonce->create('admin_save');
        self::assertTrue($nonce->verify($token, 'admin_save'));
        self::assertFalse($nonce->verify($token, 'other'));
        self::assertTrue($app->has('security.gate'));
    }
}
