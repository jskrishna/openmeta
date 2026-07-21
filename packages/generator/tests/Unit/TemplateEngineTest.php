<?php

declare(strict_types=1);

namespace OpenMeta\Generator\Tests\Unit;

use OpenMeta\Generator\Templates\TemplateEngine;
use PHPUnit\Framework\TestCase;

final class TemplateEngineTest extends TestCase
{
    private TemplateEngine $engine;

    protected function setUp(): void
    {
        parent::setUp();

        $this->engine = new TemplateEngine();
    }

    public function test_replaces_placeholders(): void
    {
        self::assertSame(
            'class Star',
            $this->engine->render('class {{ class }}', ['class' => 'Star']),
        );
    }

    public function test_unknown_placeholder_renders_empty(): void
    {
        self::assertSame('a', $this->engine->render('a{{ missing }}', []));
    }

    public function test_if_block_included_when_truthy(): void
    {
        self::assertSame('use X;', $this->engine->render('{{#if flag}}use X;{{/if}}', ['flag' => '1']));
        self::assertSame('', $this->engine->render('{{#if flag}}use X;{{/if}}', ['flag' => '']));
    }

    public function test_unless_block_included_when_falsy(): void
    {
        self::assertSame('none', $this->engine->render('{{#unless flag}}none{{/unless}}', ['flag' => '']));
        self::assertSame('', $this->engine->render('{{#unless flag}}none{{/unless}}', ['flag' => '1']));
    }

    public function test_combines_conditionals_and_placeholders(): void
    {
        $template = '{{#if base}}extends {{ base }}{{/if}}';
        self::assertSame('extends Command', $this->engine->render($template, ['base' => 'Command']));
    }
}
