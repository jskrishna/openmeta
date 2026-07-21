<?php

declare(strict_types=1);

namespace OpenMeta\Validation\Tests;

use OpenMeta\Core\Bootstrap\Bootstrap;
use OpenMeta\Validation\Registry\RuleRegistry;
use OpenMeta\Validation\Validation;
use OpenMeta\Validation\ValidationServiceProvider;

final class ValidationIntegrationTest extends ValidationTestCase
{
    public function test_provider_wires_registry_and_full_pipeline(): void
    {
        $app = Bootstrap::run([], [ValidationServiceProvider::class]);

        self::assertTrue($app->has(RuleRegistry::class));
        self::assertTrue($app->has('validation.engine'));

        Validation::extend('starts_with_om', static function (string $attribute, mixed $value): bool {
            return is_string($value) && str_starts_with($value, 'om-');
        });

        $validator = Validation::make(
            ['slug' => 'om-field', 'meta' => ['a' => 1]],
            [
                'slug' => 'required|string|starts_with_om',
                'meta' => 'required|array',
            ]
        );

        self::assertTrue($validator->passes());
        self::assertSame('om-field', $validator->validate()['slug']);
    }
}
