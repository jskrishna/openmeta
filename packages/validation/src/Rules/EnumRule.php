<?php

declare(strict_types=1);

namespace OpenMeta\Validation\Rules;

use BackedEnum;
use UnitEnum;

final class EnumRule extends Rule
{
    public function __construct()
    {
        parent::__construct('enum', 'The selected :attribute is invalid.');
    }

    public function passes(string $attribute, mixed $value, array $parameters = [], array $data = []): bool
    {
        if ($parameters === []) {
            return false;
        }

        $enumClass = $parameters[0];

        if (is_string($enumClass) && enum_exists($enumClass)) {
            if (is_subclass_of($enumClass, BackedEnum::class)) {
                foreach ($enumClass::cases() as $case) {
                    if (! $case instanceof BackedEnum) {
                        continue;
                    }

                    if ($case->value === $value) {
                        return true;
                    }

                    if (is_scalar($value) && (string) $case->value === (string) $value) {
                        return true;
                    }
                }

                return false;
            }

            if (! is_string($value)) {
                return false;
            }

            foreach ($enumClass::cases() as $case) {
                if ($case instanceof UnitEnum && $case->name === $value) {
                    return true;
                }
            }

            return false;
        }

        return in_array((string) $value, $parameters, true)
            || in_array($value, $parameters, true);
    }
}
