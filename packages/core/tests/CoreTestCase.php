<?php

declare(strict_types=1);

namespace OpenMeta\Core\Tests;

/**
 * Base test case for @openmeta/core.
 *
 * Named CoreTestCase (not TestCase) so Composer autoload and PHPUnit/IDE
 * file discovery never double-load the same class declaration.
 */
abstract class CoreTestCase extends \PHPUnit\Framework\TestCase
{
}
