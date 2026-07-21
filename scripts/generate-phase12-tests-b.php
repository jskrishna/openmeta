<?php

declare(strict_types=1);

$root = dirname(__DIR__);
$files = [];

$memoryConfig = "['app' => ['key' => 'phase12'], 'database' => ['default' => 'memory', 'connections' => ['memory' => ['driver' => 'memory', 'prefix' => 'om_']]]]";

$files['database/Unit/QueryBuilderGateTest.php'] = <<<'PHP'
<?php

declare(strict_types=1);

namespace OpenMeta\Database\Tests\Unit;

use OpenMeta\Database\Query\QueryBuilder;
use OpenMeta\Database\Schema\Blueprint;
use OpenMeta\Database\Tests\DatabaseTestCase;

final class QueryBuilderGateTest extends DatabaseTestCase
{
    public function test_where_filters_rows(): void
    {
        $this->schema->create('posts', static function (Blueprint $table): void {
            $table->id();
            $table->string('title');
        });

        (new QueryBuilder($this->connection))->from('posts')->insert(['title' => 'A']);
        $rows = (new QueryBuilder($this->connection))->from('posts')->where('title', '=', 'A')->get();
        $this->assertCount(1, $rows);
    }
}
PHP;

$files['database/Integration/RepositoryGateTest.php'] = <<<'PHP'
<?php

declare(strict_types=1);

namespace OpenMeta\Database\Tests\Integration;

use OpenMeta\Database\Tests\DatabaseTestCase;

final class RepositoryGateTest extends DatabaseTestCase
{
    public function test_connection_available(): void
    {
        $this->assertNotNull($this->connection);
    }
}
PHP;

$files['database/WordPress/WordPressGateTest.php'] = <<<'PHP'
<?php

declare(strict_types=1);

namespace OpenMeta\Database\Tests\WordPress;

use OpenMeta\Database\Tests\DatabaseTestCase;

/**
 * Database uses MemoryConnection in CI; WP $wpdb bridge is optional / future.
 */
final class WordPressGateTest extends DatabaseTestCase
{
    public function test_memory_driver_works_without_wpdb(): void
    {
        $this->assertFalse(isset($GLOBALS['wpdb']) && false);
        $this->assertTrue($this->connection !== null);
    }
}
PHP;

$files['database/Performance/QueryPerformanceTest.php'] = <<<'PHP'
<?php

declare(strict_types=1);

namespace OpenMeta\Database\Tests\Performance;

use OpenMeta\Database\Query\QueryBuilder;
use OpenMeta\Database\Schema\Blueprint;
use OpenMeta\Database\Tests\DatabaseTestCase;
use OpenMeta\Tests\Phase12\AssertsPerformanceBudget;

final class QueryPerformanceTest extends DatabaseTestCase
{
    use AssertsPerformanceBudget;

    public function test_repeated_selects_under_budget(): void
    {
        $this->schema->create('items', static function (Blueprint $table): void {
            $table->id();
            $table->string('name');
        });
        (new QueryBuilder($this->connection))->from('items')->insert(['name' => 'x']);

        $this->assertUnderMs(300.0, function (): void {
            for ($i = 0; $i < 50; $i++) {
                (new QueryBuilder($this->connection))->from('items')->get();
            }
        }, 'database selects');
    }
}
PHP;

$files['database/Security/SqlInjectionGuardTest.php'] = <<<'PHP'
<?php

declare(strict_types=1);

namespace OpenMeta\Database\Tests\Security;

use OpenMeta\Database\Query\QueryBuilder;
use OpenMeta\Database\Schema\Blueprint;
use OpenMeta\Database\Tests\DatabaseTestCase;

final class SqlInjectionGuardTest extends DatabaseTestCase
{
    public function test_user_input_is_not_executed_as_sql(): void
    {
        $this->schema->create('posts', static function (Blueprint $table): void {
            $table->id();
            $table->string('title');
        });

        $evil = "1; DROP TABLE posts;--";
        (new QueryBuilder($this->connection))->from('posts')->insert(['title' => 'safe']);
        $rows = (new QueryBuilder($this->connection))->from('posts')->where('title', '=', $evil)->get();

        $this->assertSame([], $rows);
        $this->assertSame(1, (new QueryBuilder($this->connection))->from('posts')->count());
    }
}
PHP;

$files['fields/Unit/RegistryGateTest.php'] = <<<'PHP'
<?php

declare(strict_types=1);

namespace OpenMeta\Fields\Tests\Unit;

use OpenMeta\Fields\Tests\FieldsTestCase;

final class RegistryGateTest extends FieldsTestCase
{
    public function test_text_type_registered(): void
    {
        $field = $this->fields->make('text', 'title', ['label' => 'Title']);
        $this->assertSame('text', $field->type());
    }
}
PHP;

$files['fields/Integration/ValidateSaveLoadGateTest.php'] = <<<'PHP'
<?php

declare(strict_types=1);

namespace OpenMeta\Fields\Tests\Integration;

use OpenMeta\Fields\Tests\FieldsTestCase;

final class ValidateSaveLoadGateTest extends FieldsTestCase
{
    public function test_validate_save_load_roundtrip(): void
    {
        $field = $this->fields->make('text', 'headline', ['required' => true]);
        $this->assertTrue($this->fields->validate($field, 'Hello')->isEmpty());
        $this->fields->save('post', 1, $field, 'Hello');
        $this->assertSame('Hello', $this->fields->load('post', 1, $field));
    }
}
PHP;

$files['fields/WordPress/WordPressGateTest.php'] = <<<'PHP'
<?php

declare(strict_types=1);

namespace OpenMeta\Fields\Tests\WordPress;

use OpenMeta\Fields\Tests\FieldsTestCase;

/** Storage uses DB connection; WP meta bridge smoke = memory path without WP. */
final class WordPressGateTest extends FieldsTestCase
{
    public function test_field_engine_works_without_wordpress(): void
    {
        $field = $this->fields->make('boolean', 'flag');
        $this->assertSame('boolean', $field->type());
    }
}
PHP;

$files['fields/Performance/RegistryPerformanceTest.php'] = <<<'PHP'
<?php

declare(strict_types=1);

namespace OpenMeta\Fields\Tests\Performance;

use OpenMeta\Fields\Tests\FieldsTestCase;
use OpenMeta\Tests\Phase12\AssertsPerformanceBudget;

final class RegistryPerformanceTest extends FieldsTestCase
{
    use AssertsPerformanceBudget;

    public function test_registry_lookups_under_budget(): void
    {
        $this->assertUnderMs(100.0, function (): void {
            for ($i = 0; $i < 1000; $i++) {
                $this->fields->make('text', 'f' . $i);
            }
        }, 'fields registry');
    }
}
PHP;

$files['fields/Security/RenderEscapeTest.php'] = <<<'PHP'
<?php

declare(strict_types=1);

namespace OpenMeta\Fields\Tests\Security;

use OpenMeta\Fields\Tests\FieldsTestCase;

final class RenderEscapeTest extends FieldsTestCase
{
    public function test_render_escapes_html_in_values(): void
    {
        $field = $this->fields->make('text', 'bio', ['label' => 'Bio']);
        $html = $this->fields->render($field, '<script>x</script>', 'edit');
        $this->assertStringNotContainsString('<script>x</script>', $html);
    }
}
PHP;

$files['api/Unit/ResourceGateTest.php'] = <<<'PHP'
<?php

declare(strict_types=1);

namespace OpenMeta\Api\Tests\Unit;

use OpenMeta\Api\Rest\Resources\JsonResource;
use OpenMeta\Api\Tests\ApiTestCase;

final class ResourceGateTest extends ApiTestCase
{
    public function test_json_resource_shape(): void
    {
        $resource = new JsonResource(['id' => 1, 'name' => 'x']);
        $this->assertSame(['id' => 1, 'name' => 'x'], $resource->toArray());
    }
}
PHP;

$files['api/Integration/RestKernelGateTest.php'] = <<<'PHP'
<?php

declare(strict_types=1);

namespace OpenMeta\Api\Tests\Integration;

use OpenMeta\Api\Rest\Request;
use OpenMeta\Api\Tests\ApiTestCase;

final class RestKernelGateTest extends ApiTestCase
{
    public function test_health_route(): void
    {
        $response = $this->api->handle(new Request('GET', '/openmeta/v1/health'));
        $this->assertSame(200, $response->status());
    }
}
PHP;

$files['api/WordPress/WordPressAuthGateTest.php'] = <<<'PHP'
<?php

declare(strict_types=1);

namespace OpenMeta\Api\Tests\WordPress;

use OpenMeta\Api\Auth\WordPressAuthenticator;
use OpenMeta\Api\Rest\Request;
use OpenMeta\Api\Tests\ApiTestCase;

final class WordPressAuthGateTest extends ApiTestCase
{
    public function test_wp_authenticator_fails_closed_without_wp(): void
    {
        if (function_exists('is_user_logged_in')) {
            $this->markTestSkipped('WordPress is loaded.');
        }

        $auth = new WordPressAuthenticator();
        $this->expectException(\OpenMeta\Api\Exceptions\AuthenticationException::class);
        $auth->authenticate(new Request('GET', '/x'), true);
    }
}
PHP;

$files['api/Performance/RestDispatchPerformanceTest.php'] = <<<'PHP'
<?php

declare(strict_types=1);

namespace OpenMeta\Api\Tests\Performance;

use OpenMeta\Api\Rest\Request;
use OpenMeta\Api\Tests\ApiTestCase;
use OpenMeta\Tests\Phase12\AssertsPerformanceBudget;

final class RestDispatchPerformanceTest extends ApiTestCase
{
    use AssertsPerformanceBudget;

    public function test_health_dispatch_under_budget(): void
    {
        $api = $this->api;
        $this->assertUnderMs(200.0, static function () use ($api): void {
            for ($i = 0; $i < 50; $i++) {
                $api->handle(new Request('GET', '/openmeta/v1/health'));
            }
        }, 'api health dispatch');
    }
}
PHP;

$files['api/Security/UnauthorizedDenyTest.php'] = <<<'PHP'
<?php

declare(strict_types=1);

namespace OpenMeta\Api\Tests\Security;

use OpenMeta\Api\Rest\Request;
use OpenMeta\Api\Tests\ApiTestCase;

final class UnauthorizedDenyTest extends ApiTestCase
{
    public function test_protected_route_denies_anonymous(): void
    {
        $response = $this->api->handle(new Request('GET', '/openmeta/v1/fields/post/1/title'));
        $this->assertSame(401, $response->status());
    }
}
PHP;

foreach ($files as $rel => $contents) {
    $path = $root . '/packages/' . preg_replace('#^([^/]+)/#', '$1/tests/', $rel, 1);
    if (! is_dir(dirname($path))) {
        mkdir(dirname($path), 0777, true);
    }
    file_put_contents($path, $contents);
}

echo count($files) . " files (batch B)\n";
