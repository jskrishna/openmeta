<?php

declare(strict_types=1);

namespace OpenMeta\Fields\Tests;

use OpenMeta\Core\Application\Application;
use OpenMeta\Core\Bootstrap\Bootstrap;
use OpenMeta\Database\DatabaseServiceProvider;
use OpenMeta\Fields\FieldEngine;
use OpenMeta\Fields\FieldsServiceProvider;
use OpenMeta\Validation\ValidationServiceProvider;

abstract class FieldsTestCase extends \PHPUnit\Framework\TestCase
{
    protected Application $app;

    protected FieldEngine $fields;

    protected function setUp(): void
    {
        parent::setUp();

        $this->app = Bootstrap::run(
            [
                'database' => [
                    'default' => 'memory',
                    'connections' => [
                        'memory' => ['driver' => 'memory', 'prefix' => 'om_'],
                    ],
                ],
            ],
            [
                ValidationServiceProvider::class,
                DatabaseServiceProvider::class,
                FieldsServiceProvider::class,
            ]
        );

        $this->fields = $this->app->get(FieldEngine::class);
    }
}
