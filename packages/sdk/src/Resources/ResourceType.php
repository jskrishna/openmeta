<?php

declare(strict_types=1);

namespace OpenMeta\Sdk\Resources;

/**
 * Kinds of host resources an extension may contribute.
 *
 * The SDK aggregates registrations; host packages (Fields, Admin, REST, …)
 * drain the relevant type through their own adapters. The SDK never mounts
 * resources into host internals itself.
 */
enum ResourceType: string
{
    case Field = 'field';
    case Component = 'component';
    case Page = 'page';
    case Route = 'route';
    case Middleware = 'middleware';
    case Template = 'template';
    case Asset = 'asset';
    case Migration = 'migration';
    case Menu = 'menu';
    case Widget = 'widget';
}
