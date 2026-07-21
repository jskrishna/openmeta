<?php

declare(strict_types=1);

namespace OpenMeta\Fields\Lifecycle;

/**
 * Canonical field value lifecycle phases.
 */
enum FieldLifecyclePhase: string
{
    case Register = 'register';
    case BuildDefinition = 'build_definition';
    case ValidateConfiguration = 'validate_configuration';
    case Hydrate = 'hydrate';
    case Render = 'render';
    case ValidateValue = 'validate_value';
    case Serialize = 'serialize';
    case Store = 'store';
    case Load = 'load';
    case Deserialize = 'deserialize';
    case Return = 'return';
}
