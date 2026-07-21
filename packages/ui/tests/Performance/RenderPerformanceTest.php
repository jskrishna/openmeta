<?php

declare(strict_types=1);

namespace OpenMeta\Ui\Tests\Performance;

use OpenMeta\Tests\Phase12\AssertsPerformanceBudget;
use OpenMeta\Ui\Components\Card;
use PHPUnit\Framework\TestCase;

final class RenderPerformanceTest extends TestCase
{
    use AssertsPerformanceBudget;

    public function test_card_render_under_budget(): void
    {
        $this->assertUnderMs(50.0, static function (): void {
            for ($i = 0; $i < 200; $i++) {
                $html = Card::render('T', '<p>B</p>');
                unset($html);
            }
        }, 'ui card render');
    }
}
