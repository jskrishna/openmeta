<?php

declare(strict_types=1);

namespace OpenMeta\Generator\Tests\Unit;

use OpenMeta\Generator\Configuration\GeneratorConfiguration;
use OpenMeta\Generator\Resolvers\PlaceholderResolver;
use PHPUnit\Framework\TestCase;

final class PlaceholderResolverTest extends TestCase
{
    private PlaceholderResolver $resolver;

    protected function setUp(): void
    {
        parent::setUp();

        $this->resolver = new PlaceholderResolver();
    }

    public function test_derives_naming_variants(): void
    {
        $vars = $this->resolver->resolve('user profile', [], new GeneratorConfiguration());

        self::assertSame('user profile', $vars['name']);
        self::assertSame('UserProfile', $vars['class']);
        self::assertSame('user_profile', $vars['snake']);
        self::assertSame('user-profile', $vars['kebab']);
    }

    public function test_extras_override_defaults(): void
    {
        $vars = $this->resolver->resolve(
            'star',
            ['class' => 'StarField', 'extends' => 'Field'],
            new GeneratorConfiguration(),
        );

        self::assertSame('StarField', $vars['class']);
        self::assertSame('Field', $vars['extends']);
    }

    public function test_year_defaults_and_can_be_configured(): void
    {
        $vars = $this->resolver->resolve('x', [], new GeneratorConfiguration(year: '2099'));

        self::assertSame('2099', $vars['year']);
        self::assertNotSame('', $this->resolver->resolve('x', [], new GeneratorConfiguration())['year']);
    }
}
