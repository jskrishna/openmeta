<?php

declare(strict_types=1);

namespace OpenMeta\Admin\Modals;

use OpenMeta\Admin\Components\ComponentDescriptor;
use OpenMeta\Admin\Events\ModalOpened;
use OpenMeta\Admin\Exceptions\AdminException;
use OpenMeta\Core\Contracts\EventDispatcherInterface;

/**
 * Confirmation, form, alert, and wizard modal descriptors.
 */
final class ModalManager
{
    /** @var array<string, array{type: string, title: string, body: string}> */
    private array $modals = [];

    public function __construct(private readonly ?EventDispatcherInterface $events = null)
    {
    }

    public function registerConfirmation(string $id, string $title, string $body): void
    {
        $this->modals[$id] = ['type' => 'confirmation', 'title' => $title, 'body' => $body];
    }

    public function registerAlert(string $id, string $title, string $body): void
    {
        $this->modals[$id] = ['type' => 'alert', 'title' => $title, 'body' => $body];
    }

    public function registerForm(string $id, string $title, string $formId): void
    {
        $this->modals[$id] = ['type' => 'form', 'title' => $title, 'body' => $formId];
    }

    public function registerWizard(string $id, string $title): void
    {
        $this->modals[$id] = ['type' => 'wizard', 'title' => $title, 'body' => ''];
    }

    public function open(string $id): ComponentDescriptor
    {
        if (! isset($this->modals[$id])) {
            throw new AdminException(sprintf('Unknown modal [%s].', $id));
        }

        $modal = $this->modals[$id];
        $this->events?->dispatch(new ModalOpened($id, $modal['type']));

        return new ComponentDescriptor('modal', [
            'id' => $id,
            'type' => $modal['type'],
            'title' => $modal['title'],
            'body' => $modal['body'],
        ]);
    }
}
