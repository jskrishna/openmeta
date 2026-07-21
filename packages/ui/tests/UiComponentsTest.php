<?php

declare(strict_types=1);

namespace OpenMeta\Ui\Tests;

use OpenMeta\Ui\Components\Card;
use OpenMeta\Ui\Components\DataTable;
use OpenMeta\Ui\Components\Form;
use OpenMeta\Ui\Primitives\Button;
use OpenMeta\Ui\Primitives\Input;
use OpenMeta\Ui\Primitives\Notice;
use OpenMeta\Ui\Theme\Theme;
use OpenMeta\Ui\Tokens\Tokens;

final class UiComponentsTest extends \PHPUnit\Framework\TestCase
{
    public function test_tokens_and_theme(): void
    {
        self::assertStringContainsString('--om-color-accent', Tokens::css());
        $theme = new Theme(['--om-color-accent' => '#111']);
        self::assertStringContainsString('#111', $theme->css());
        self::assertStringContainsString('om-theme', $theme->wrap('x'));
    }

    public function test_primitives_and_components_escape_output(): void
    {
        self::assertStringContainsString('Save', Button::render('Save'));
        self::assertStringContainsString('&lt;b&gt;', Input::render('t', '', 'text', '<b>Title</b>'));
        self::assertStringContainsString('om-notice--error', Notice::render('Nope', 'error'));
        self::assertStringContainsString('Hello', Card::render('Hello', '<p>Body</p>'));
        self::assertStringContainsString('No items found.', DataTable::render(['A'], []));
        self::assertStringContainsString('om-form', Form::render('/save', 'post', '<p>f</p>'));
    }
}
