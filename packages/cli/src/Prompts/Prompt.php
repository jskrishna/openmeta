<?php

declare(strict_types=1);

namespace OpenMeta\Cli\Prompts;

use OpenMeta\Cli\Contracts\OutputInterface;
use OpenMeta\Cli\Contracts\PromptInterface;
use RuntimeException;

/**
 * Stream-backed interactive prompts.
 *
 * The input stream is injectable (STDIN by default) so prompts can be scripted
 * from an in-memory stream in tests.
 */
final class Prompt implements PromptInterface
{
    /** @var resource */
    private $input;

    /**
     * @param resource|null $input
     */
    public function __construct(private readonly OutputInterface $output, $input = null)
    {
        $resource = $input ?? fopen('php://stdin', 'r');

        if (! is_resource($resource)) {
            throw new RuntimeException('Unable to open the input stream.');
        }

        $this->input = $resource;
    }

    public function ask(string $question, ?string $default = null): string
    {
        $this->output->write($question . ($default !== null ? " [{$default}]" : '') . ': ');
        $line = $this->readLine();

        if ($line === '' && $default !== null) {
            return $default;
        }

        return $line;
    }

    public function confirm(string $question, bool $default = false): bool
    {
        $this->output->write($question . ($default ? ' [Y/n]: ' : ' [y/N]: '));
        $line = strtolower($this->readLine());

        if ($line === '') {
            return $default;
        }

        return in_array($line, ['y', 'yes'], true);
    }

    public function choice(string $question, array $choices, ?string $default = null): string
    {
        $this->output->writeln($question);

        foreach ($choices as $index => $choice) {
            $this->output->writeln(sprintf('  [%d] %s', $index, $choice));
        }

        $this->output->write('> ');
        $line = $this->readLine();

        if ($line === '' && $default !== null) {
            return $default;
        }

        if (ctype_digit($line) && isset($choices[(int) $line])) {
            return $choices[(int) $line];
        }

        if (in_array($line, $choices, true)) {
            return $line;
        }

        return $default ?? ($choices[0] ?? '');
    }

    public function secret(string $question): string
    {
        // Portable fallback: read without terminal echo toggling.
        $this->output->write($question . ': ');

        return $this->readLine();
    }

    private function readLine(): string
    {
        $line = fgets($this->input);

        return $line === false ? '' : trim($line);
    }
}
