<?php

declare(strict_types=1);

namespace OpenMeta\Rest\Contracts;

use OpenMeta\Rest\Responses\Response;
use Throwable;

/**
 * Maps throwables to HTTP responses.
 */
interface ExceptionMapperInterface
{
    public function supports(Throwable $throwable): bool;

    public function map(Throwable $throwable): Response;
}
