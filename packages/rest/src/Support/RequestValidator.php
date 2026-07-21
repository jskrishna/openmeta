<?php

declare(strict_types=1);

namespace OpenMeta\Rest\Support;

use OpenMeta\Rest\Exceptions\ValidationException;
use OpenMeta\Rest\Requests\Request;
use OpenMeta\Validation\Validation;

/**
 * Validates REST requests via {@see Validation}.
 */
final class RequestValidator
{
    /**
     * @param array<string, list<string>|string> $rules
     * @param array<string, string> $messages
     * @return array<string, mixed>
     */
    public function validate(Request $request, array $rules, array $messages = []): array
    {
        $validator = Validation::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            $errors = $validator->errors();

            throw new ValidationException(
                $errors->first() ?? 'Validation failed.',
                $errors,
            );
        }

        return $request->all();
    }
}
