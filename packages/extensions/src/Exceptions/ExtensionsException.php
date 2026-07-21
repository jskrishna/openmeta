<?php

declare(strict_types=1);

namespace OpenMeta\Extensions\Exceptions;

use OpenMeta\Core\Exceptions\OpenMetaException;

/**
 * Base exception for the Extension SDK.
 *
 * All SDK failures extend this type so consumers can catch the whole
 * package with a single `catch (ExtensionsException $e)`.
 */
class ExtensionsException extends OpenMetaException
{
}
