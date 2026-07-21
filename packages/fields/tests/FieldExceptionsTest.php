<?php

declare(strict_types=1);

namespace OpenMeta\Fields\Tests;

use OpenMeta\Fields\Exceptions\InvalidDefinitionException;
use OpenMeta\Fields\Exceptions\InvalidFieldException;
use OpenMeta\Fields\Exceptions\SerializationFailureException;
use OpenMeta\Fields\Exceptions\StorageFailureException;
use OpenMeta\Fields\Exceptions\UnknownFieldTypeException;
use OpenMeta\Fields\Exceptions\ValidationFailedException;
use OpenMeta\Validation\Results\ErrorBag;

final class FieldExceptionsTest extends FieldsTestCase
{
    public function test_exception_hierarchy(): void
    {
        self::assertInstanceOf(InvalidFieldException::class, new InvalidFieldException('x'));
        self::assertInstanceOf(InvalidDefinitionException::class, new InvalidDefinitionException('x'));
        self::assertInstanceOf(UnknownFieldTypeException::class, new UnknownFieldTypeException('x'));
        self::assertInstanceOf(SerializationFailureException::class, new SerializationFailureException('x'));
        self::assertInstanceOf(StorageFailureException::class, new StorageFailureException('x'));

        $failed = new ValidationFailedException('bad', ErrorBag::empty());
        self::assertTrue($failed->errors()->isEmpty());
    }
}
