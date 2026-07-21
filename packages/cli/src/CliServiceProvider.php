<?php

declare(strict_types=1);

namespace OpenMeta\Cli;

use OpenMeta\Cli\Application\ConsoleApplication;
use OpenMeta\Cli\Commands\DoctorCommand;
use OpenMeta\Cli\Commands\HelpCommand;
use OpenMeta\Cli\Commands\InfoCommand;
use OpenMeta\Cli\Commands\ListCommand;
use OpenMeta\Cli\Commands\MakeCommandCommand;
use OpenMeta\Cli\Commands\VersionCommand;
use OpenMeta\Cli\Configuration\ConfigurationLoader;
use OpenMeta\Cli\Contracts\CommandRegistryInterface;
use OpenMeta\Cli\Contracts\ConsoleApplicationInterface;
use OpenMeta\Cli\Contracts\LoggerInterface;
use OpenMeta\Cli\Contracts\OutputFormatterInterface;
use OpenMeta\Cli\Contracts\OutputInterface;
use OpenMeta\Cli\Contracts\PromptInterface;
use OpenMeta\Cli\Contracts\TaskRunnerInterface;
use OpenMeta\Cli\Discovery\CommandDiscovery;
use OpenMeta\Cli\Environment\EnvironmentInspector;
use OpenMeta\Cli\Input\InputParser;
use OpenMeta\Cli\Logging\ConsoleLogger;
use OpenMeta\Cli\Output\ConsoleOutput;
use OpenMeta\Cli\Output\OutputFormatter;
use OpenMeta\Cli\Prompts\Prompt;
use OpenMeta\Cli\Registry\CommandRegistry;
use OpenMeta\Cli\Support\StubGenerator;
use OpenMeta\Cli\Tasks\TaskRunner;
use OpenMeta\Core\Contracts\ContainerInterface;
use OpenMeta\Core\Contracts\EventDispatcherInterface;
use OpenMeta\Core\Providers\ServiceProvider;
use OpenMeta\Support\Filesystem\LocalFilesystem;

/**
 * Registers the CLI services and the built-in commands.
 *
 * Public surface (console application, command registry, task runner) is bound
 * to its interface; supporting collaborators are bound to concretes.
 */
final class CliServiceProvider extends ServiceProvider
{
    public function register(ContainerInterface $container): void
    {
        $container->singleton(OutputFormatter::class, static fn (): OutputFormatter => new OutputFormatter());
        $container->alias(OutputFormatter::class, OutputFormatterInterface::class);

        $container->singleton(ConsoleOutput::class, static function (ContainerInterface $c): ConsoleOutput {
            return new ConsoleOutput($c->get(OutputFormatterInterface::class));
        });
        $container->alias(ConsoleOutput::class, OutputInterface::class);

        $container->singleton(CommandRegistry::class, static fn (): CommandRegistry => new CommandRegistry());
        $container->alias(CommandRegistry::class, CommandRegistryInterface::class);

        $container->singleton(InputParser::class, static fn (): InputParser => new InputParser());
        $container->singleton(
            EnvironmentInspector::class,
            static fn (): EnvironmentInspector => new EnvironmentInspector(),
        );
        $container->singleton(
            StubGenerator::class,
            static fn (): StubGenerator => new StubGenerator(new LocalFilesystem()),
        );
        $container->singleton(
            ConfigurationLoader::class,
            static fn (): ConfigurationLoader => new ConfigurationLoader(new LocalFilesystem()),
        );

        $container->singleton(TaskRunner::class, static fn (): TaskRunner => new TaskRunner());
        $container->alias(TaskRunner::class, TaskRunnerInterface::class);

        $container->singleton(ConsoleLogger::class, static function (ContainerInterface $c): ConsoleLogger {
            return new ConsoleLogger($c->get(OutputInterface::class));
        });
        $container->alias(ConsoleLogger::class, LoggerInterface::class);

        $container->singleton(Prompt::class, static function (ContainerInterface $c): Prompt {
            return new Prompt($c->get(OutputInterface::class));
        });
        $container->alias(Prompt::class, PromptInterface::class);

        $container->singleton(ConsoleApplication::class, static function (ContainerInterface $c): ConsoleApplication {
            return new ConsoleApplication(
                $c->get(CommandRegistryInterface::class),
                $c->get(OutputInterface::class),
                $c->get(EventDispatcherInterface::class),
                $c->get(InputParser::class),
            );
        });
        $container->alias(ConsoleApplication::class, ConsoleApplicationInterface::class);
        $container->alias(ConsoleApplication::class, 'cli');

        $container->singleton(CommandDiscovery::class, static function (ContainerInterface $c): CommandDiscovery {
            return new CommandDiscovery($c->get(CommandRegistryInterface::class));
        });
    }

    public function boot(ContainerInterface $container): void
    {
        /** @var CommandRegistry $registry */
        $registry = $container->get(CommandRegistryInterface::class);
        /** @var EnvironmentInspector $inspector */
        $inspector = $container->get(EnvironmentInspector::class);
        /** @var StubGenerator $stubs */
        $stubs = $container->get(StubGenerator::class);

        $registry->register(new VersionCommand());
        $registry->register(new InfoCommand($inspector));
        $registry->register(new DoctorCommand($inspector));
        $registry->register(new ListCommand($registry));
        $registry->register(new HelpCommand($registry));
        $registry->register(new MakeCommandCommand($stubs));
    }
}
