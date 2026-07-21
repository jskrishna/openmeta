<?php

declare(strict_types=1);

namespace OpenMeta\Cli\Commands;

use OpenMeta\Cli\Contracts\InputInterface;
use OpenMeta\Cli\Contracts\OutputInterface;
use OpenMeta\Cli\Input\ArgumentDefinition;
use OpenMeta\Cli\Input\InputDefinition;
use OpenMeta\Cli\Input\OptionDefinition;
use OpenMeta\Cli\Support\ExitCode;
use OpenMeta\Cli\Support\StubGenerator;

/**
 * `make:command <Name>` — representative scaffolding command.
 *
 * Demonstrates the `make:*` infrastructure (validation + stub generation)
 * without hardcoding project-specific generation logic.
 */
final class MakeCommandCommand extends Command
{
    private const STUB = <<<'PHP'
        <?php

        declare(strict_types=1);

        namespace App\Commands;

        use OpenMeta\Cli\Commands\Command;
        use OpenMeta\Cli\Contracts\InputInterface;
        use OpenMeta\Cli\Contracts\OutputInterface;
        use OpenMeta\Cli\Support\ExitCode;

        final class {{class}} extends Command
        {
            protected string $name = '{{name}}';

            protected string $description = 'Describe {{class}}.';

            public function execute(InputInterface $input, OutputInterface $output): int
            {
                $output->success('{{class}} ran.');

                return ExitCode::SUCCESS;
            }
        }

        PHP;

    protected string $name = 'make:command';

    protected string $description = 'Scaffold a new console command class.';

    public function __construct(private readonly StubGenerator $stubs)
    {
    }

    public function definition(): InputDefinition
    {
        return (new InputDefinition())
            ->addArgument(new ArgumentDefinition('name', true, 'The command class name'))
            ->addOption(new OptionDefinition('path', 'p', true, 'Target file path'));
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $class = (string) $input->argument('name');

        $errors = $this->validateInput(['name' => $class], ['name' => ['required', 'string']]);

        if ($errors !== []) {
            $output->error('A command class name is required.');

            return ExitCode::INVALID;
        }

        $pathOption = $input->option('path');
        $path = is_string($pathOption) && $pathOption !== ''
            ? $pathOption
            : 'src/Commands/' . $class . '.php';

        if ($this->stubs->exists($path)) {
            $output->warning(sprintf('File [%s] already exists; skipping.', $path));

            return ExitCode::FAILURE;
        }

        $this->stubs->generate($path, self::STUB, [
            '{{class}}' => $class,
            '{{name}}' => $this->toCommandName($class),
        ]);

        $output->success(sprintf('Created command [%s] at %s.', $class, $path));

        return ExitCode::SUCCESS;
    }

    private function toCommandName(string $class): string
    {
        $name = preg_replace('/Command$/', '', $class) ?? $class;

        return strtolower($name === '' ? $class : $name);
    }
}
