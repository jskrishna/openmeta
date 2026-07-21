<?php

declare(strict_types=1);

namespace OpenMeta\Ui;

use OpenMeta\Core\Contracts\ContainerInterface;
use OpenMeta\Core\Providers\ServiceProvider;
use OpenMeta\Ui\Theme\Theme;

final class UiServiceProvider extends ServiceProvider
{
    public function register(ContainerInterface $container): void
    {
        $container->singleton(Theme::class, static fn (): Theme => new Theme());
        $container->alias(Theme::class, 'ui.theme');
    }

    public function boot(ContainerInterface $container): void
    {
    }
}
