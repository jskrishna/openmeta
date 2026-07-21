<?php

declare(strict_types=1);

namespace OpenMeta\Api\Exceptions;

final class NotFoundException extends ApiException
{
    public function __construct(string $message = 'Not found.')
    {
        parent::__construct($message, 404, 'not_found');
    }
}
