<?php

declare(strict_types=1);

namespace OpenMeta\Admin\Tests;

use OpenMeta\Admin\Admin;
use OpenMeta\Admin\AdminServiceProvider;
use OpenMeta\Admin\Dashboard\Dashboard;
use OpenMeta\Admin\Exceptions\AdminException;
use OpenMeta\Admin\Forms\AdminForm;
use OpenMeta\Admin\Tables\AdminTable;
use OpenMeta\Core\Bootstrap\Bootstrap;
use OpenMeta\Security\Capabilities\ArrayCapabilityChecker;
use OpenMeta\Security\Contracts\CapabilityCheckerInterface;
use OpenMeta\Security\Nonce\Nonce;
use OpenMeta\Security\Permissions\Gate;
use OpenMeta\Security\Permissions\Permission;
use OpenMeta\Security\SecurityServiceProvider;
use OpenMeta\Ui\UiServiceProvider;
use OpenMeta\Validation\ValidationServiceProvider;

abstract class AdminTestCase extends \PHPUnit\Framework\TestCase
{
    protected Admin $admin;

    protected ArrayCapabilityChecker $capabilities;

    protected Gate $gate;

    protected Nonce $nonce;

    protected function setUp(): void
    {
        parent::setUp();

        $app = Bootstrap::run(
            ['app' => ['key' => 'admin-test-secret']],
            [
                ValidationServiceProvider::class,
                SecurityServiceProvider::class,
                UiServiceProvider::class,
                AdminServiceProvider::class,
            ]
        );

        $this->admin = $app->get(Admin::class);
        /** @var ArrayCapabilityChecker $caps */
        $caps = $app->get(CapabilityCheckerInterface::class);
        $this->capabilities = $caps;
        $this->gate = $app->get(Gate::class);
        $this->nonce = $app->get(Nonce::class);
    }

    protected function grant(string ...$capabilities): void
    {
        $this->capabilities->grant($capabilities);
        $this->gate->flushCache();
    }
}
