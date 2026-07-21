<?php

declare(strict_types=1);

namespace OpenMeta\Api\Rest\Routes;

use OpenMeta\Api\Rest\Controllers\FieldValueController;
use OpenMeta\Security\Permissions\Permission;

/**
 * Registers default OpenMeta REST routes.
 */
final class RouteRegistrar
{
    public function register(Router $router): void
    {
        $router->get('/health', [FieldValueController::class, 'health'], false);

        $router->get(
            '/fields/{entityType}/{entityId}/{field}',
            [FieldValueController::class, 'show'],
            true,
            [Permission::READ, Permission::EDIT_CONTENT, Permission::MANAGE]
        );

        $router->put(
            '/fields/{entityType}/{entityId}/{field}',
            [FieldValueController::class, 'update'],
            true,
            [Permission::EDIT_CONTENT, Permission::MANAGE]
        );
    }
}
