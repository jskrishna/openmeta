<?php

declare(strict_types=1);

namespace OpenMeta\Rest\Exceptions;

use OpenMeta\Rest\Contracts\ExceptionMapperInterface;
use OpenMeta\Rest\Responses\ErrorResponse;
use OpenMeta\Rest\Responses\Response;
use Throwable;

/**
 * Maps throwables to HTTP responses using registered mappers.
 */
final class ExceptionHandler
{
    /** @var list<ExceptionMapperInterface> */
    private array $mappers = [];

    public function register(ExceptionMapperInterface $mapper): void
    {
        $this->mappers[] = $mapper;
    }

    public function handle(Throwable $throwable): Response
    {
        foreach ($this->mappers as $mapper) {
            if ($mapper->supports($throwable)) {
                return $mapper->map($throwable);
            }
        }

        if ($throwable instanceof ValidationException) {
            $details = [];

            if ($throwable->errors() !== null) {
                $details['errors'] = $throwable->errors()->all();
            }

            return ErrorResponse::make(
                $throwable->getMessage(),
                $throwable->status(),
                $throwable->errorCode(),
                $details,
            );
        }

        if ($throwable instanceof MethodNotAllowedException) {
            $details = [];

            if ($throwable->allowedMethods() !== []) {
                $details['allowed'] = $throwable->allowedMethods();
            }

            return ErrorResponse::make(
                $throwable->getMessage(),
                $throwable->status(),
                $throwable->errorCode(),
                $details,
            );
        }

        if ($throwable instanceof RestException) {
            return ErrorResponse::make(
                $throwable->getMessage(),
                $throwable->status(),
                $throwable->errorCode(),
            );
        }

        return ErrorResponse::make('Internal server error.', 500, 'server_error');
    }
}
