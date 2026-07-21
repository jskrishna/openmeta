<?php

declare(strict_types=1);

namespace OpenMeta\Fields\Contracts;

use OpenMeta\Fields\Field\Field;

/**
 * Pluggable value serializer (array / JSON / object / custom).
 */
interface FieldSerializerInterface
{
    public function serialize(Field $field, mixed $value): mixed;

    public function deserialize(Field $field, mixed $payload): mixed;
}
