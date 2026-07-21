<?php

declare(strict_types=1);

namespace OpenMeta\Rest\Responses;

/**
 * Stream response stub — future packages may stream large payloads.
 */
final class StreamResponse extends Response
{
    public function __construct(
        private readonly string $streamId = '',
        int $status = 200,
        array $headers = ['Content-Type' => 'application/octet-stream'],
    ) {
        parent::__construct($status, null, $headers);
    }

    public function streamId(): string
    {
        return $this->streamId;
    }

    public function body(): string
    {
        return '';
    }
}
