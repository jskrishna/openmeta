# Example — Create a Field

tags: fields, example

Scaffold a field with the generator, then register it.

## Scaffold

```bash
php bin/openmeta make:field Star
# → src/Fields/Star.php
```

## The generated field

```php
<?php

declare(strict_types=1);

namespace App\Fields;

final class Star
{
    public function type(): string
    {
        return 'star';
    }
}
```

## Register it

```php
use OpenMeta\Fields\Registry\FieldRegistry;

/** @var FieldRegistry $fields */
$fields = $app->get(FieldRegistry::class);
$fields->register('star', App\Fields\Star::class);
```

See the [fields package](../../packages/fields/README.md) for the full field
lifecycle.
