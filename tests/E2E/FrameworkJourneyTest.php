<?php

declare(strict_types=1);

namespace OpenMeta\Tests\E2E;

use OpenMeta\Cli\Application\ConsoleApplication;
use OpenMeta\Cli\Contracts\CommandRegistryInterface;
use OpenMeta\Cli\Output\BufferedOutput;
use OpenMeta\Cli\Output\OutputFormatter;
use OpenMeta\Cli\Support\ExitCode;
use OpenMeta\Core\Application\Application;
use OpenMeta\Framework\Framework;
use OpenMeta\Generator\Contracts\GeneratorManagerInterface;
use OpenMeta\Generator\Files\FileStatus;
use OpenMeta\Generator\Manager\GenerationOptions;
use OpenMeta\Generator\Manager\GenerationRequest;
use OpenMeta\GraphQL\Contracts\GraphQLManagerInterface;
use OpenMeta\GraphQL\Resolvers\CallableResolver;
use OpenMeta\GraphQL\Resolvers\ResolverRegistry;
use OpenMeta\GraphQL\Types\FieldDefinition;
use OpenMeta\GraphQL\Types\TypeReference;
use PHPUnit\Framework\TestCase;

/**
 * End-to-end journeys that cross package boundaries on the full framework.
 */
final class FrameworkJourneyTest extends TestCase
{
    private function boot(): Application
    {
        return Framework::boot();
    }

    public function test_graphql_journey(): void
    {
        $app = $this->boot();

        /** @var GraphQLManagerInterface $graphql */
        $graphql = $app->get(GraphQLManagerInterface::class);
        /** @var ResolverRegistry $resolvers */
        $resolvers = $app->get(ResolverRegistry::class);

        $resolvers->register('hello', new CallableResolver(static fn (): string => 'world'));
        $graphql->queries()->register(new FieldDefinition('hello', TypeReference::named('String'), resolver: 'hello'));

        $result = $graphql->executeQuery('hello');

        self::assertFalse($result->hasErrors());
        self::assertSame('world', $result->data);
    }

    public function test_generator_journey(): void
    {
        $app = $this->boot();

        /** @var GeneratorManagerInterface $generator */
        $generator = $app->get(GeneratorManagerInterface::class);

        $result = $generator->run(new GenerationRequest('field', 'Star', [], new GenerationOptions(dryRun: true)));

        self::assertSame(FileStatus::Previewed, $result->files[0]->status);
        self::assertStringContainsString('final class Star', $result->files[0]->contents);
    }

    public function test_cli_journey(): void
    {
        $app = $this->boot();

        /** @var CommandRegistryInterface $registry */
        $registry = $app->get(CommandRegistryInterface::class);

        self::assertTrue($registry->has('make:field'), 'generator mounts make:* into the CLI');

        $output = new BufferedOutput(new OutputFormatter(false));
        $console = new ConsoleApplication($registry, $output, $app->events());

        self::assertSame(ExitCode::SUCCESS, $console->run(['version']));
        self::assertStringContainsString('OpenMeta', $output->content());
    }
}
