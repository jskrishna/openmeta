<?php

declare(strict_types=1);

namespace OpenMeta\Validation\Tests;

use OpenMeta\Validation\Support\AttributeFormatter;
use OpenMeta\Validation\Support\DataNormalizer;
use stdClass;

final class SupportHelpersTest extends ValidationTestCase
{
    public function test_data_normalizer_and_attribute_formatter(): void
    {
        self::assertSame(['a' => 1], DataNormalizer::normalize(['a' => 1]));

        $obj = new stdClass();
        $obj->c = 3;
        self::assertSame(['c' => 3], DataNormalizer::normalize($obj));

        self::assertSame('user profile email', AttributeFormatter::display('user.profile_email'));
    }
}
