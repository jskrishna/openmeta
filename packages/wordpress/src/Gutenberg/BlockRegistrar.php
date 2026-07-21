<?php

declare(strict_types=1);

namespace OpenMeta\Wordpress\Gutenberg;

use OpenMeta\Wordpress\Runtime\WordPressRuntimeInterface;

/**
 * Registers OpenMeta Gutenberg block metadata (editor JS ships separately).
 */
final class BlockRegistrar
{
    public const FIELD_BLOCK = 'openmeta/field';

    public const SCHEMA_BLOCK = 'openmeta/schema';

    public function __construct(private readonly WordPressRuntimeInterface $wp)
    {
    }

    public function register(): void
    {
        $this->wp->addAction('init', [$this, 'onInit']);
    }

    public function onInit(): void
    {
        $this->wp->registerBlockType(self::FIELD_BLOCK, [
            'api_version' => 3,
            'title' => 'OpenMeta Field',
            'category' => 'widgets',
            'attributes' => [
                'field' => ['type' => 'string', 'default' => ''],
                'entityType' => ['type' => 'string', 'default' => 'post'],
                'entityId' => ['type' => 'number', 'default' => 0],
            ],
        ]);

        $this->wp->registerBlockType(self::SCHEMA_BLOCK, [
            'api_version' => 3,
            'title' => 'OpenMeta Schema',
            'category' => 'widgets',
            'attributes' => [
                'schema' => ['type' => 'string', 'default' => ''],
            ],
        ]);
    }

    /** @return list<string> */
    public function blockNames(): array
    {
        return [self::FIELD_BLOCK, self::SCHEMA_BLOCK];
    }
}
