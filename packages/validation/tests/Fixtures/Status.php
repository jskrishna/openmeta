<?php

declare(strict_types=1);

namespace OpenMeta\Validation\Tests\Fixtures;

enum Status: string
{
    case Active = 'active';
    case Inactive = 'inactive';
}
