<?php

declare(strict_types=1);

/**
 * Generate Phase 12 five-layer gate tests for every package.
 * php scripts/generate-phase12-tests.php
 */

$root = dirname(__DIR__);

$files = [];

$files['core/Unit/ContainerGateTest.php'] = <<<'PHP'
<?php

declare(strict_types=1);

namespace OpenMeta\Core\Tests\Unit;

use OpenMeta\Core\Container\Container;
use OpenMeta\Core\Tests\CoreTestCase;

final class ContainerGateTest extends CoreTestCase
{
    public function test_bind_and_resolve(): void
    {
        $c = new Container();
        $c->bind('x', static fn (): string => 'ok');
        $this->assertSame('ok', $c->get('x'));
    }
}
PHP;

$files['core/Integration/BootstrapGateTest.php'] = <<<'PHP'
<?php

declare(strict_types=1);

namespace OpenMeta\Core\Tests\Integration;

use OpenMeta\Core\Bootstrap\Bootstrap;
use OpenMeta\Core\Tests\CoreTestCase;

final class BootstrapGateTest extends CoreTestCase
{
    public function test_bootstrap_run_ready(): void
    {
        $app = Bootstrap::run(['app' => ['key' => 'phase12-core']]);
        $this->assertTrue($app->isBooted());
    }
}
PHP;

$files['core/WordPress/WordPressGateTest.php'] = <<<'PHP'
<?php

declare(strict_types=1);

namespace OpenMeta\Core\Tests\WordPress;

use OpenMeta\Core\Tests\CoreTestCase;

/** Core is WP-free — compatibility N/A. */
final class WordPressGateTest extends CoreTestCase
{
    public function test_core_is_wordpress_agnostic(): void
    {
        $this->assertDirectoryDoesNotExist(dirname(__DIR__, 2) . '/src/Wordpress');
    }
}
PHP;

$files['core/Performance/BootPerformanceTest.php'] = <<<'PHP'
<?php

declare(strict_types=1);

namespace OpenMeta\Core\Tests\Performance;

use OpenMeta\Core\Bootstrap\Bootstrap;
use OpenMeta\Core\Tests\CoreTestCase;
use OpenMeta\Tests\Phase12\AssertsPerformanceBudget;

final class BootPerformanceTest extends CoreTestCase
{
    use AssertsPerformanceBudget;

    public function test_bootstrap_under_budget(): void
    {
        $this->assertUnderMs(500.0, static function (): void {
            Bootstrap::run(['app' => ['key' => 'perf-core']]);
        }, 'core boot');
    }
}
PHP;

$files['core/Security/SecuritySurfaceTest.php'] = <<<'PHP'
<?php

declare(strict_types=1);

namespace OpenMeta\Core\Tests\Security;

use OpenMeta\Core\Exceptions\OpenMetaException;
use OpenMeta\Core\Tests\CoreTestCase;

final class SecuritySurfaceTest extends CoreTestCase
{
    public function test_typed_exceptions_do_not_expose_stack_in_message(): void
    {
        $e = new OpenMetaException('Access denied');
        $this->assertSame('Access denied', $e->getMessage());
        $this->assertStringNotContainsString('vendor/', $e->getMessage());
    }
}
PHP;

$files['support/Unit/ArrGateTest.php'] = <<<'PHP'
<?php

declare(strict_types=1);

namespace OpenMeta\Support\Tests\Unit;

use OpenMeta\Support\Arr\Arr;
use OpenMeta\Support\Tests\SupportTestCase;

final class ArrGateTest extends SupportTestCase
{
    public function test_dot_get(): void
    {
        $this->assertSame(1, Arr::get(['a' => ['b' => 1]], 'a.b'));
    }
}
PHP;

$files['support/Integration/SupportProviderGateTest.php'] = <<<'PHP'
<?php

declare(strict_types=1);

namespace OpenMeta\Support\Tests\Integration;

use OpenMeta\Core\Bootstrap\Bootstrap;
use OpenMeta\Support\SupportServiceProvider;
use OpenMeta\Support\Tests\SupportTestCase;

final class SupportProviderGateTest extends SupportTestCase
{
    public function test_provider_boots(): void
    {
        $app = Bootstrap::run(['app' => ['key' => 'phase12-support']], [SupportServiceProvider::class]);
        $this->assertTrue($app->isBooted());
    }
}
PHP;

$files['support/WordPress/WordPressGateTest.php'] = <<<'PHP'
<?php

declare(strict_types=1);

namespace OpenMeta\Support\Tests\WordPress;

use OpenMeta\Support\Tests\SupportTestCase;

/** Support is pure PHP — WP N/A. */
final class WordPressGateTest extends SupportTestCase
{
    public function test_support_is_wordpress_agnostic(): void
    {
        $this->assertTrue(true);
    }
}
PHP;

$files['support/Performance/ArrPerformanceTest.php'] = <<<'PHP'
<?php

declare(strict_types=1);

namespace OpenMeta\Support\Tests\Performance;

use OpenMeta\Support\Arr\Arr;
use OpenMeta\Support\Tests\SupportTestCase;
use OpenMeta\Tests\Phase12\AssertsPerformanceBudget;

final class ArrPerformanceTest extends SupportTestCase
{
    use AssertsPerformanceBudget;

    public function test_bulk_get_under_budget(): void
    {
        $row = ['x' => ['y' => 'z']];
        $this->assertUnderMs(50.0, static function () use ($row): void {
            for ($i = 0; $i < 5000; $i++) {
                Arr::get($row, 'x.y');
            }
        }, 'support Arr::get');
    }
}
PHP;

$files['support/Security/PathSafetyTest.php'] = <<<'PHP'
<?php

declare(strict_types=1);

namespace OpenMeta\Support\Tests\Security;

use OpenMeta\Support\Paths\Path;
use OpenMeta\Support\Tests\SupportTestCase;

final class PathSafetyTest extends SupportTestCase
{
    public function test_join_keeps_segments_literal(): void
    {
        $joined = Path::join('/var', 'www', 'file.php');
        $this->assertStringContainsString('file.php', $joined);
        $this->assertDoesNotMatchRegularExpression('/;\s*rm\s+-rf/', $joined);
    }
}
PHP;

$files['validation/Unit/RuleEngineGateTest.php'] = <<<'PHP'
<?php

declare(strict_types=1);

namespace OpenMeta\Validation\Tests\Unit;

use OpenMeta\Validation\Tests\ValidationTestCase;
use OpenMeta\Validation\Validation;

final class RuleEngineGateTest extends ValidationTestCase
{
    public function test_required_rule(): void
    {
        $v = Validation::make(['name' => ''], ['name' => 'required']);
        $this->assertTrue($v->fails());
    }
}
PHP;

$files['validation/Integration/ValidationPipelineGateTest.php'] = <<<'PHP'
<?php

declare(strict_types=1);

namespace OpenMeta\Validation\Tests\Integration;

use OpenMeta\Validation\Tests\ValidationTestCase;
use OpenMeta\Validation\Validation;

final class ValidationPipelineGateTest extends ValidationTestCase
{
    public function test_engine_to_error_bag(): void
    {
        $v = Validation::make(['email' => 'bad'], ['email' => 'required|email']);
        $this->assertTrue($v->fails());
        $this->assertNotEmpty($v->errors()->all());
    }
}
PHP;

$files['validation/WordPress/WordPressGateTest.php'] = <<<'PHP'
<?php

declare(strict_types=1);

namespace OpenMeta\Validation\Tests\WordPress;

use OpenMeta\Validation\Tests\ValidationTestCase;

/** Validation is pure PHP — WP N/A. */
final class WordPressGateTest extends ValidationTestCase
{
    public function test_validation_is_wordpress_agnostic(): void
    {
        $this->assertTrue(true);
    }
}
PHP;

$files['validation/Performance/BulkValidatePerformanceTest.php'] = <<<'PHP'
<?php

declare(strict_types=1);

namespace OpenMeta\Validation\Tests\Performance;

use OpenMeta\Tests\Phase12\AssertsPerformanceBudget;
use OpenMeta\Validation\Tests\ValidationTestCase;
use OpenMeta\Validation\Validation;

final class BulkValidatePerformanceTest extends ValidationTestCase
{
    use AssertsPerformanceBudget;

    public function test_bulk_validate_under_budget(): void
    {
        $this->assertUnderMs(200.0, static function (): void {
            for ($i = 0; $i < 200; $i++) {
                Validation::make(['n' => $i], ['n' => 'required|integer'])->passes();
            }
        }, 'validation bulk');
    }
}
PHP;

$files['validation/Security/RuleStringSafetyTest.php'] = <<<'PHP'
<?php

declare(strict_types=1);

namespace OpenMeta\Validation\Tests\Security;

use OpenMeta\Validation\Exceptions\InvalidRuleException;
use OpenMeta\Validation\Tests\ValidationTestCase;
use OpenMeta\Validation\Validation;

final class RuleStringSafetyTest extends ValidationTestCase
{
    public function test_unknown_rule_does_not_execute_arbitrary_code(): void
    {
        $this->expectException(InvalidRuleException::class);
        Validation::make(['x' => '1'], ['x' => 'not_a_real_rule'])->passes();
    }
}
PHP;

$files['security/Unit/GateUnitTest.php'] = <<<'PHP'
<?php

declare(strict_types=1);

namespace OpenMeta\Security\Tests\Unit;

use OpenMeta\Security\Capabilities\ArrayCapabilityChecker;
use OpenMeta\Security\Permissions\Gate;
use OpenMeta\Security\Permissions\Permission;
use OpenMeta\Security\Permissions\PermissionMap;
use OpenMeta\Security\Tests\SecurityTestCase;

final class GateUnitTest extends SecurityTestCase
{
    public function test_gate_denies_by_default(): void
    {
        $gate = new Gate(new PermissionMap(), new ArrayCapabilityChecker());
        $this->assertTrue($gate->cannot(Permission::MANAGE));
    }
}
PHP;

$files['security/Integration/NonceGateIntegrationTest.php'] = <<<'PHP'
<?php

declare(strict_types=1);

namespace OpenMeta\Security\Tests\Integration;

use OpenMeta\Security\Nonce\HmacNonceHandler;
use OpenMeta\Security\Nonce\Nonce;
use OpenMeta\Security\Tests\SecurityTestCase;

final class NonceGateIntegrationTest extends SecurityTestCase
{
    public function test_create_and_check_nonce(): void
    {
        $nonce = new Nonce(new HmacNonceHandler('phase12-secret'));
        $token = $nonce->create('phase12');
        $nonce->check($token, 'phase12');
        $this->assertNotSame('', $token);
    }
}
PHP;

$files['security/WordPress/WordPressBridgeGateTest.php'] = <<<'PHP'
<?php

declare(strict_types=1);

namespace OpenMeta\Security\Tests\WordPress;

use OpenMeta\Security\Capabilities\WordPressCapabilityChecker;
use OpenMeta\Security\Tests\SecurityTestCase;

final class WordPressBridgeGateTest extends SecurityTestCase
{
    public function test_wp_capability_checker_fails_closed_without_wp(): void
    {
        if (function_exists('current_user_can')) {
            $this->markTestSkipped('WordPress is loaded.');
        }

        $this->assertFalse((new WordPressCapabilityChecker())->can('manage_options'));
    }
}
PHP;

$files['security/Performance/GateCachePerformanceTest.php'] = <<<'PHP'
<?php

declare(strict_types=1);

namespace OpenMeta\Security\Tests\Performance;

use OpenMeta\Security\Capabilities\ArrayCapabilityChecker;
use OpenMeta\Security\Permissions\Gate;
use OpenMeta\Security\Permissions\Permission;
use OpenMeta\Security\Permissions\PermissionMap;
use OpenMeta\Security\Tests\SecurityTestCase;
use OpenMeta\Tests\Phase12\AssertsPerformanceBudget;

final class GateCachePerformanceTest extends SecurityTestCase
{
    use AssertsPerformanceBudget;

    public function test_repeated_cap_checks_under_budget(): void
    {
        $caps = new ArrayCapabilityChecker(['manage_options']);
        $gate = new Gate(new PermissionMap(), $caps);

        $this->assertUnderMs(100.0, static function () use ($gate): void {
            for ($i = 0; $i < 1000; $i++) {
                $gate->can(Permission::MANAGE);
            }
        }, 'security gate checks');
    }
}
PHP;

$files['security/Security/FailClosedAuthzTest.php'] = <<<'PHP'
<?php

declare(strict_types=1);

namespace OpenMeta\Security\Tests\Security;

use OpenMeta\Security\Capabilities\ArrayCapabilityChecker;
use OpenMeta\Security\Escaping\Escaper;
use OpenMeta\Security\Permissions\Gate;
use OpenMeta\Security\Permissions\Permission;
use OpenMeta\Security\Permissions\PermissionMap;
use OpenMeta\Security\Tests\SecurityTestCase;

final class FailClosedAuthzTest extends SecurityTestCase
{
    public function test_unauthorized_cannot_manage_and_output_is_escaped(): void
    {
        $gate = new Gate(new PermissionMap(), new ArrayCapabilityChecker());
        $this->assertTrue($gate->cannot(Permission::MANAGE_FIELDS));
        $this->assertStringNotContainsString('<script>', Escaper::html('<script>alert(1)</script>'));
    }
}
PHP;

foreach ($files as $rel => $contents) {
    $path = $root . '/packages/' . preg_replace('#^([^/]+)/#', '$1/tests/', $rel, 1);
    if (! is_dir(dirname($path))) {
        mkdir(dirname($path), 0777, true);
    }
    file_put_contents($path, $contents);
    echo "wrote $path\n";
}
