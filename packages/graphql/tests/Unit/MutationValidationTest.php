<?php

declare(strict_types=1);

namespace OpenMeta\GraphQL\Tests\Unit;

use OpenMeta\GraphQL\Errors\ErrorCategory;
use OpenMeta\GraphQL\Events\MutationExecuted;
use OpenMeta\GraphQL\Tests\GraphQLTestCase;
use OpenMeta\GraphQL\Types\FieldDefinition;
use OpenMeta\GraphQL\Types\TypeReference;

final class MutationValidationTest extends GraphQLTestCase
{
    private function registerCreatePost(): void
    {
        $this->resolver(
            'createPost',
            static fn (mixed $root, array $args): array => ['title' => $args['title'] ?? null],
        );
        $this->mutation(new FieldDefinition(
            'createPost',
            TypeReference::named('String'),
            resolver: 'createPost',
            rules: ['title' => ['required', 'string']],
        ));
    }

    public function test_invalid_arguments_yield_validation_error(): void
    {
        $this->registerCreatePost();

        /** @var list<object> $executed */
        $executed = [];
        $this->capture(MutationExecuted::class, $executed);

        $result = $this->graphql->executeMutation('createPost', []);

        self::assertTrue($result->hasErrors());
        self::assertSame(ErrorCategory::Validation, $result->errors[0]->category);
        self::assertArrayHasKey('validation', $result->errors[0]->extensions);
        self::assertCount(1, $executed);
    }

    public function test_valid_arguments_pass(): void
    {
        $this->registerCreatePost();

        $result = $this->graphql->executeMutation('createPost', ['title' => 'Hello']);

        self::assertFalse($result->hasErrors());
        self::assertSame(['title' => 'Hello'], $result->data);
    }
}
