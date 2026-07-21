<?php

declare(strict_types=1);

namespace OpenMeta\Rest\Responses;

/**
 * Empty HTTP response (204 No Content by default).
 */
final class EmptyResponse extends Response
{
    public function __construct(int $status = 204, array $headers = [])
    {
        parent::__construct($status, null, $headers);
    }

    public function toArray(): array
    {
        return [];
    }

    public function body(): string
    {
        return '';
    }
}
