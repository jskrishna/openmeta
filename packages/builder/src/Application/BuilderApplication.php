<?php

declare(strict_types=1);

namespace OpenMeta\Builder\Application;

use OpenMeta\Builder\Canvas\Canvas;
use OpenMeta\Builder\Canvas\CanvasNode;
use OpenMeta\Builder\Clipboard\ClipboardManager;
use OpenMeta\Builder\Conditions\ConditionBuilder;
use OpenMeta\Builder\DragDrop\DragDrop;
use OpenMeta\Builder\Events\ComponentAdded;
use OpenMeta\Builder\Events\ComponentRemoved;
use OpenMeta\Builder\Events\ComponentUpdated;
use OpenMeta\Builder\Events\PreviewGenerated;
use OpenMeta\Builder\Events\SchemaSaved;
use OpenMeta\Builder\Exceptions\BuilderException;
use OpenMeta\Builder\History\HistoryManager;
use OpenMeta\Builder\Inspector\InspectorPanel;
use OpenMeta\Builder\Layouts\LayoutEngine;
use OpenMeta\Builder\Library\BlockLibrary;
use OpenMeta\Builder\Preview\PreviewEngine;
use OpenMeta\Builder\Preview\PreviewResult;
use OpenMeta\Builder\Properties\PropertyEditor;
use OpenMeta\Builder\Registry\ComponentRegistry;
use OpenMeta\Builder\Schema\SchemaManager;
use OpenMeta\Builder\Templates\TemplateManager;
use OpenMeta\Core\Contracts\EventDispatcherInterface;
use OpenMeta\Fields\FieldEngine;
use OpenMeta\Security\Nonce\Nonce;
use OpenMeta\Security\Permissions\Gate;
use OpenMeta\Security\Permissions\Permission;

/**
 * Visual Builder application — low-code configuration engine (no UI rendering).
 */
class BuilderApplication
{
    public const SCREEN_ID = 'openmeta-builder';

    /** @var array<string, mixed> */
    private array $savedSnapshot = [];

    public function __construct(
        private readonly SchemaManager $schema,
        private readonly ComponentRegistry $registry,
        private readonly DragDrop $dragDrop,
        private readonly TemplateManager $templates,
        private readonly ConditionBuilder $conditions,
        private readonly PreviewEngine $preview,
        private readonly HistoryManager $history,
        private readonly ClipboardManager $clipboard,
        private readonly BlockLibrary $library,
        private readonly LayoutEngine $layouts,
        private readonly PropertyEditor $properties,
        private readonly InspectorPanel $inspector,
        private readonly FieldEngine $fields,
        private readonly Gate $gate,
        private readonly Nonce $nonce,
        private readonly EventDispatcherInterface $events,
    ) {
    }

    public function canvas(): Canvas
    {
        return $this->schema->canvas();
    }

    public function registry(): ComponentRegistry
    {
        return $this->registry;
    }

    public function schema(): SchemaManager
    {
        return $this->schema;
    }

    public function history(): HistoryManager
    {
        return $this->history;
    }

    public function dragDrop(): DragDrop
    {
        return $this->dragDrop;
    }

    public function templates(): TemplateManager
    {
        return $this->templates;
    }

    public function conditions(): ConditionBuilder
    {
        return $this->conditions;
    }

    public function preview(): PreviewEngine
    {
        return $this->preview;
    }

    public function clipboard(): ClipboardManager
    {
        return $this->clipboard;
    }

    public function library(): BlockLibrary
    {
        return $this->library;
    }

    public function layouts(): LayoutEngine
    {
        return $this->layouts;
    }

    public function properties(): PropertyEditor
    {
        return $this->properties;
    }

    public function inspector(): InspectorPanel
    {
        return $this->inspector;
    }

    /**
     * Portable session descriptor for frontend hosts (Admin / WP bridges).
     *
     * @return array<string, mixed>
     */
    public function sessionState(): array
    {
        $this->assertCanManage();

        return [
            'screen' => self::SCREEN_ID,
            'nonce' => $this->nonce->create(self::SCREEN_ID),
            'schema' => $this->schema->build(),
            'selection' => $this->canvas()->selection()->ids(),
            'library' => $this->library->catalog(),
        ];
    }

    public function addComponent(CanvasNode $node, ?int $index = null): void
    {
        $this->assertCanManage();
        $this->history->record(function () use ($node, $index): void {
            $this->canvas()->add($node, $index);
            $this->events->dispatch(new ComponentAdded($node));
        });
    }

    public function removeComponent(string $id): void
    {
        $this->assertCanManage();
        $this->history->record(function () use ($id): void {
            $this->canvas()->remove($id);
            $this->events->dispatch(new ComponentRemoved($id));
        });
    }

    public function updateComponent(CanvasNode $node): void
    {
        $this->assertCanManage();
        $before = $this->canvas()->find($node->id);
        if ($before === null) {
            throw new BuilderException(sprintf('Unknown node [%s].', $node->id));
        }

        $this->history->record(function () use ($before, $node): void {
            $this->canvas()->replace($node);
            $this->events->dispatch(new ComponentUpdated($before, $node));
        });
    }

    /**
     * @param array<string, mixed> $values
     */
    public function generatePreview(array $values = []): PreviewResult
    {
        $this->assertCanManage();
        $result = $this->preview->generate($this->canvas(), $values);
        $this->events->dispatch(new PreviewGenerated($result));

        return $result;
    }

    /**
     * Persist schema after nonce + permission check.
     *
     * @return array<string, mixed>
     */
    public function save(string $nonce): array
    {
        $this->assertCanManage();
        $this->nonce->check($nonce, self::SCREEN_ID);

        foreach ($this->canvas()->nodes() as $node) {
            $this->fields->make($node->type, $node->name, $node->settings);
        }

        $schema = $this->schema->build();
        $this->savedSnapshot = $schema;
        $this->events->dispatch(new SchemaSaved($schema));

        return $schema;
    }

    public function discard(): void
    {
        $this->assertCanManage();
        if ($this->savedSnapshot === []) {
            return;
        }

        $this->schema->load($this->savedSnapshot);
    }

    /** @return array<string, mixed> */
    public function saved(): array
    {
        return $this->savedSnapshot;
    }

    private function assertCanManage(): void
    {
        if ($this->gate->cannot(Permission::MANAGE_FIELDS) && $this->gate->cannot(Permission::MANAGE)) {
            throw new BuilderException('You do not have permission to use the builder.');
        }
    }
}
