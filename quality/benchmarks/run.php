<?php

declare(strict_types=1);

/**
 * OpenMeta benchmark suite (dependency-free).
 *
 * Measures execution time, per-iteration cost, and peak memory for the hot
 * paths across Core, Container, Field Engine, Validation, GraphQL, and
 * serialization. Prints a table and writes quality/reports/benchmarks.json.
 *
 * Usage: composer bench   (or: php quality/benchmarks/run.php)
 */

require __DIR__ . '/../../vendor/autoload.php';

use OpenMeta\Fields\Registry\FieldRegistry;
use OpenMeta\Framework\Framework;
use OpenMeta\GraphQL\Contracts\GraphQLManagerInterface;
use OpenMeta\GraphQL\Contracts\SchemaManagerInterface;
use OpenMeta\GraphQL\Resolvers\CallableResolver;
use OpenMeta\GraphQL\Resolvers\ResolverRegistry;
use OpenMeta\GraphQL\Types\FieldDefinition;
use OpenMeta\GraphQL\Types\TypeReference;
use OpenMeta\Security\Contracts\GateInterface;
use OpenMeta\Support\Str\Str;
use OpenMeta\Validation\Validation;

/**
 * @param callable():void $fn
 * @return array<string, mixed>
 */
function bench(string $name, int $iterations, callable $fn): array
{
    try {
        $fn(); // warm up
    } catch (Throwable $e) {
        return ['name' => $name, 'error' => $e->getMessage()];
    }

    gc_collect_cycles();
    $memoryBefore = memory_get_usage();
    $start = hrtime(true);

    for ($i = 0; $i < $iterations; $i++) {
        $fn();
    }

    $elapsedMs = (hrtime(true) - $start) / 1_000_000;

    return [
        'name' => $name,
        'iterations' => $iterations,
        'total_ms' => round($elapsedMs, 3),
        'avg_ms' => round($elapsedMs / $iterations, 5),
        'mem_delta_kb' => round((memory_get_usage() - $memoryBefore) / 1024, 2),
        'peak_mb' => round(memory_get_peak_usage() / 1_048_576, 2),
    ];
}

$app = Framework::boot();

/** @var GraphQLManagerInterface $graphql */
$graphql = $app->get(GraphQLManagerInterface::class);
/** @var ResolverRegistry $resolvers */
$resolvers = $app->get(ResolverRegistry::class);
$resolvers->register('now', new CallableResolver(static fn (): string => 'value'));
$graphql->queries()->register(new FieldDefinition('now', TypeReference::named('String'), resolver: 'now'));
/** @var SchemaManagerInterface $schema */
$schema = $app->get(SchemaManagerInterface::class);

$dataset = [];
for ($i = 0; $i < 500; $i++) {
    $dataset[] = ['id' => $i, 'name' => 'row ' . $i, 'active' => $i % 2 === 0];
}

$results = [
    bench('framework.boot', 50, static fn () => Framework::boot()),
    bench('container.resolve', 5000, static fn () => $app->get(GateInterface::class)),
    bench('fields.register_100', 500, static function (): void {
        $registry = new FieldRegistry();
        for ($i = 0; $i < 100; $i++) {
            $registry->register('type_' . $i, static fn (): mixed => null);
        }
    }),
    bench('validation.run', 5000, static function (): void {
        Validation::make(
            ['email' => 'a@b.com', 'age' => 21],
            ['email' => ['required', 'email'], 'age' => ['required', 'integer']],
        )->passes();
    }),
    bench('graphql.schema_build', 2000, static fn () => $schema->rebuild()),
    bench('graphql.execute', 5000, static fn () => $graphql->executeQuery('now')),
    bench('serialization.json', 5000, static fn () => json_encode($dataset)),
    bench('support.str', 20000, static function (): void {
        Str::snake(Str::studly('user profile field'));
    }),
];

$line = str_repeat('-', 86);
echo $line . "\n";
printf("| %-26s | %10s | %12s | %12s | %8s |\n", 'benchmark', 'iters', 'avg (ms)', 'mem Δ (kb)', 'peak MB');
echo $line . "\n";

foreach ($results as $row) {
    if (isset($row['error'])) {
        printf("| %-26s | %-58s |\n", $row['name'], 'SKIPPED: ' . $row['error']);
        continue;
    }

    printf(
        "| %-26s | %10d | %12s | %12s | %8s |\n",
        $row['name'],
        $row['iterations'],
        $row['avg_ms'],
        $row['mem_delta_kb'],
        $row['peak_mb'],
    );
}

echo $line . "\n";

$reportDir = __DIR__ . '/../reports';
if (! is_dir($reportDir)) {
    mkdir($reportDir, 0755, true);
}

file_put_contents(
    $reportDir . '/benchmarks.json',
    json_encode(['php' => PHP_VERSION, 'results' => $results], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n",
);

echo "Report written to quality/reports/benchmarks.json\n";
