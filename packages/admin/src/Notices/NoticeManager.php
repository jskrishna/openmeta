<?php

declare(strict_types=1);

namespace OpenMeta\Admin\Notices;

use OpenMeta\Ui\Primitives\Notice;

/**
 * Success / error / warning / info notices (dismissible).
 */
final class NoticeManager
{
    /** @var list<array{type: string, message: string, dismissible: bool}> */
    private array $queue = [];

    public function success(string $message, bool $dismissible = true): void
    {
        $this->queue[] = ['type' => 'success', 'message' => $message, 'dismissible' => $dismissible];
    }

    public function error(string $message, bool $dismissible = false): void
    {
        $this->queue[] = ['type' => 'error', 'message' => $message, 'dismissible' => $dismissible];
    }

    public function warning(string $message, bool $dismissible = true): void
    {
        $this->queue[] = ['type' => 'warning', 'message' => $message, 'dismissible' => $dismissible];
    }

    public function info(string $message, bool $dismissible = true): void
    {
        $this->queue[] = ['type' => 'info', 'message' => $message, 'dismissible' => $dismissible];
    }

    public function render(): string
    {
        $html = '';

        foreach ($this->queue as $notice) {
            $class = 'om-notice om-notice--' . $notice['type'];

            if ($notice['dismissible']) {
                $class .= ' om-notice--dismissible';
            }

            $html .= '<div class="' . $class . '">'
                . Notice::render($notice['message'], $notice['type'])
                . '</div>';
        }

        return $html;
    }

    public function flush(): void
    {
        $this->queue = [];
    }
}
