<?php

declare(strict_types=1);

namespace OpenMeta\Builder\Templates;

use OpenMeta\Builder\Canvas\Canvas;
use OpenMeta\Builder\Exceptions\BuilderException;
use OpenMeta\Builder\Canvas\CanvasNode;

/**
 * Starter schema presets — data only until applied.
 */
final class TemplateRegistry
{
    /** @var array<string, array{title: string, category: string, nodes: list<array<string, mixed>>}> */
    private array $templates = [];

    /**
     * @param list<array<string, mixed>> $nodes
     */
    public function register(string $id, string $title, array $nodes, string $category = 'general'): void
    {
        $this->templates[$id] = [
            'title' => $title,
            'category' => $category,
            'nodes' => $nodes,
        ];
    }

    public function has(string $id): bool
    {
        return isset($this->templates[$id]);
    }

    /** @return list<string> */
    public function ids(): array
    {
        return array_keys($this->templates);
    }

    /** @return list<string> */
    public function categories(): array
    {
        $categories = [];
        foreach ($this->templates as $template) {
            $categories[$template['category']] = true;
        }

        return array_keys($categories);
    }

    /** @return list<array{id: string, title: string, category: string}> */
    public function list(string $category = ''): array
    {
        $items = [];
        foreach ($this->templates as $id => $template) {
            if ($category !== '' && $template['category'] !== $category) {
                continue;
            }

            $items[] = [
                'id' => $id,
                'title' => $template['title'],
                'category' => $template['category'],
            ];
        }

        return $items;
    }

    public function apply(string $id, Canvas $canvas): void
    {
        if (! isset($this->templates[$id])) {
            throw new BuilderException(sprintf('Unknown template [%s].', $id));
        }

        foreach ($canvas->nodes() as $node) {
            $canvas->remove($node->id);
        }

        foreach ($this->templates[$id]['nodes'] as $data) {
            $canvas->add(CanvasNode::fromArray($data));
        }
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function preview(string $id): array
    {
        if (! isset($this->templates[$id])) {
            throw new BuilderException(sprintf('Unknown template [%s].', $id));
        }

        return $this->templates[$id]['nodes'];
    }

    /**
     * @return array<string, mixed>
     */
    public function export(string $id): array
    {
        if (! isset($this->templates[$id])) {
            throw new BuilderException(sprintf('Unknown template [%s].', $id));
        }

        return [
            'id' => $id,
            ...$this->templates[$id],
        ];
    }

    /**
     * @param array<string, mixed> $payload
     */
    public function import(array $payload): void
    {
        $id = (string) ($payload['id'] ?? '');
        if ($id === '') {
            throw new BuilderException('Template import requires an id.');
        }

        $nodes = is_array($payload['nodes'] ?? null) ? $payload['nodes'] : [];
        $this->register(
            $id,
            (string) ($payload['title'] ?? $id),
            $nodes,
            (string) ($payload['category'] ?? 'imported'),
        );
    }
}
