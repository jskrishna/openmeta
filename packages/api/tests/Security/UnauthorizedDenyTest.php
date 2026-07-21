<?php

declare(strict_types=1);

namespace OpenMeta\Api\Tests\Security;

use OpenMeta\Api\Rest\Request;
use OpenMeta\Api\Tests\ApiTestCase;

final class UnauthorizedDenyTest extends ApiTestCase
{
    public function test_protected_route_denies_anonymous(): void
    {
        $response = $this->api->handle(new Request('GET', '/openmeta/v1/fields/post/1/title'));
        $this->assertSame(401, $response->status());
    }
}
