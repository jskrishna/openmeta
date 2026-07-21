<?php

declare(strict_types=1);

namespace OpenMeta\Cli\Output;

use OpenMeta\Cli\Contracts\OutputFormatterInterface;

/**
 * Applies ANSI styles to messages. Styles are named and extensible; when
 * output is not decorated, messages pass through unchanged.
 */
final class OutputFormatter implements OutputFormatterInterface
{
    /** @var array<string, string> style => ANSI SGR code */
    private array $styles = [
        'success' => '32',
        'warning' => '33',
        'error' => '31',
        'info' => '36',
        'comment' => '90',
    ];

    public function __construct(private bool $decorated = true)
    {
    }

    public function format(string $style, string $message): string
    {
        if (! $this->decorated || ! isset($this->styles[$style])) {
            return $message;
        }

        return "\033[" . $this->styles[$style] . 'm' . $message . "\033[0m";
    }

    public function addStyle(string $style, string $ansi): void
    {
        $this->styles[$style] = $ansi;
    }

    public function setDecorated(bool $decorated): void
    {
        $this->decorated = $decorated;
    }

    public function isDecorated(): bool
    {
        return $this->decorated;
    }
}
