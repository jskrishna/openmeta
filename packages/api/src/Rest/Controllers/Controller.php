<?php

declare(strict_types=1);

namespace OpenMeta\Api\Rest\Controllers;

use OpenMeta\Api\Rest\Request;
use OpenMeta\Api\Rest\Resources\ResourceInterface;
use OpenMeta\Api\Rest\Response;

/**
 * Thin controller base — AuthN/AuthZ handled by RestKernel before invocation.
 */
abstract class Controller
{
    protected function ok(ResourceInterface|array|null $data = null, int $status = 200): Response
    {
        if ($data instanceof ResourceInterface) {
            $data = $data->toArray();
        }

        return Response::json($data, $status);
    }

    protected function created(ResourceInterface|array $data): Response
    {
        return $this->ok($data, 201);
    }
}
