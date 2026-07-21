# OpenMeta CLI — developer guide

`@openmeta/cli` is a framework-aware console over the Core container. See
[../SPEC.md](../SPEC.md) for the contract.

## Running

```bash
vendor/bin/openmeta list                 # all commands
vendor/bin/openmeta help make:command    # a command's arguments/options
vendor/bin/openmeta version
vendor/bin/openmeta info --json
vendor/bin/openmeta doctor               # environment diagnostics (non-zero exit on failure)
vendor/bin/openmeta make:command GreetCommand --path src/Commands/GreetCommand.php
```

The binary boots the framework with `CliServiceProvider` and runs the console
application against `argv`.

## Writing a command

```php
use OpenMeta\Cli\Commands\Command;
use OpenMeta\Cli\Contracts\InputInterface;
use OpenMeta\Cli\Contracts\OutputInterface;
use OpenMeta\Cli\Input\ArgumentDefinition;
use OpenMeta\Cli\Input\InputDefinition;
use OpenMeta\Cli\Support\ExitCode;

final class GreetCommand extends Command
{
    protected string $name = 'greet';
    protected string $description = 'Greet someone.';

    public function definition(): InputDefinition
    {
        return (new InputDefinition())
            ->addArgument(new ArgumentDefinition('name', true, 'Who to greet'));
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $errors = $this->validateInput(              // reuses @openmeta/validation
            ['name' => $input->argument('name')],
            ['name' => ['required', 'string']],
        );

        if ($errors !== []) {
            $output->error('A name is required.');
            return ExitCode::INVALID;
        }

        $output->success('Hello, ' . $input->argument('name') . '!');
        return ExitCode::SUCCESS;
    }
}
```

## Registering commands from a package (extensibility)

```php
use OpenMeta\Cli\Contracts\CommandProviderInterface;

final class MyCommands implements CommandProviderInterface
{
    public function commands(): iterable
    {
        yield new GreetCommand();
    }
}

// in your service provider's boot():
$discovery = $container->get(CommandDiscovery::class);
$discovery->fromProviders([new MyCommands()]);
```

## Output

```php
$output->success('Done');
$output->warning('Careful');
$output->error('Broken');
$output->table(['Name', 'Value'], [['php', PHP_VERSION]]);
$output->json(['ok' => true]);

$bar = new ProgressBar($output, total: 100);
$bar->advance(50);
$bar->finish();
```

## Tasks

```php
use OpenMeta\Cli\Tasks\CallableTask;

$runner->register(new CallableTask('build', 'Build assets', function ($output): int {
    $output->writeln('building…');
    return ExitCode::SUCCESS;
}));

$runner->run('build', $output);
```

## Prompts

```php
$name = $prompt->ask('Your name', 'Anonymous');
if ($prompt->confirm('Continue?', true)) { /* … */ }
$env  = $prompt->choice('Environment', ['dev', 'staging', 'prod']);
```

## Not included

No GUI, marketplace, cloud features, or IDE plugins — and no library of
business commands. The package ships the infrastructure and representative
built-ins only.
