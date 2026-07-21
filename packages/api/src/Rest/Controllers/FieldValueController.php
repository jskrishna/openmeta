<?php

declare(strict_types=1);

namespace OpenMeta\Api\Rest\Controllers;

use OpenMeta\Api\Exceptions\ApiException;
use OpenMeta\Api\Exceptions\NotFoundException;
use OpenMeta\Api\Rest\Request;
use OpenMeta\Api\Rest\Resources\FieldValueResource;
use OpenMeta\Api\Rest\Resources\JsonResource;
use OpenMeta\Api\Rest\Response;
use OpenMeta\Fields\Exceptions\FieldException;
use OpenMeta\Fields\Exceptions\UnknownFieldTypeException;
use OpenMeta\Fields\Field\Field;
use OpenMeta\Fields\FieldEngine;
use OpenMeta\Validation\Exceptions\ValidationException;
use OpenMeta\Validation\Validation;

/**
 * Field value endpoints — validate via validation/fields, persist via FieldEngine.
 */
final class FieldValueController extends Controller
{
    public function __construct(private readonly FieldEngine $fields)
    {
    }

    public function show(Request $request): Response
    {
        $field = $this->resolveField($request);
        $entityType = (string) $request->attribute('entityType');
        $entityId = (string) $request->attribute('entityId');
        $value = $this->fields->load($entityType, $entityId, $field);

        return $this->ok(new FieldValueResource($field, $value, $entityType, $entityId));
    }

    public function update(Request $request): Response
    {
        $field = $this->resolveField($request);
        $entityType = (string) $request->attribute('entityType');
        $entityId = (string) $request->attribute('entityId');

        if (! array_key_exists('value', $request->body())) {
            throw new ApiException('Missing value.', 422, 'validation_error');
        }

        $value = $request->input('value');

        try {
            $this->fields->save($entityType, $entityId, $field, $value);
        } catch (FieldException $e) {
            throw new ApiException($e->getMessage(), 422, 'validation_error', $e);
        }

        $stored = $this->fields->load($entityType, $entityId, $field);

        return $this->ok(new FieldValueResource($field, $stored, $entityType, $entityId));
    }

    public function health(Request $request): Response
    {
        return $this->ok(new JsonResource([
            'status' => 'ok',
            'namespace' => 'openmeta/v1',
        ]));
    }

    private function resolveField(Request $request): Field
    {
        $type = (string) $request->input('type', 'text');
        $name = (string) $request->attribute('field');
        /** @var array<string, mixed> $settings */
        $settings = is_array($request->input('settings', []))
            ? $request->input('settings', [])
            : [];

        try {
            Validation::validate(
                ['type' => $type, 'field' => $name],
                ['type' => 'required|string', 'field' => 'required|string']
            );
        } catch (ValidationException $e) {
            throw new ApiException(
                (string) ($e->errors()->first() ?? 'Invalid request.'),
                422,
                'validation_error',
                $e
            );
        }

        try {
            return $this->fields->make($type, $name, $settings);
        } catch (UnknownFieldTypeException $e) {
            throw new NotFoundException($e->getMessage());
        }
    }
}
