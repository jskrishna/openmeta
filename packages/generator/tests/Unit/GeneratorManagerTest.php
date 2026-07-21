<?php

declare(strict_types=1);

namespace OpenMeta\Generator\Tests\Unit;

use OpenMeta\Generator\Events\FileGeneratedEvent;
use OpenMeta\Generator\Events\FileSkipped;
use OpenMeta\Generator\Events\GenerationCompleted;
use OpenMeta\Generator\Events\GenerationFailed;
use OpenMeta\Generator\Events\GenerationStarted;
use OpenMeta\Generator\Exceptions\GeneratorNotFoundException;
use OpenMeta\Generator\Files\FileStatus;
use OpenMeta\Generator\Manager\GenerationOptions;
use OpenMeta\Generator\Manager\GenerationRequest;
use OpenMeta\Generator\Tests\GeneratorTestCase;

final class GeneratorManagerTest extends GeneratorTestCase
{
    public function test_generates_a_field(): void
    {
        $result = $this->manager->run(new GenerationRequest('field', 'star'));

        self::assertCount(1, $result->written());
        self::assertTrue($this->output->isFile('src/Fields/Star.php'));
        $contents = $this->output->get('src/Fields/Star.php');
        self::assertStringContainsString('final class Star', $contents);
        self::assertStringContainsString("return 'star';", $contents);
    }

    public function test_dry_run_previews_without_writing(): void
    {
        /** @var list<object> $generated */
        $generated = [];
        $this->capture(FileGeneratedEvent::class, $generated);

        $result = $this->manager->run(new GenerationRequest('field', 'star', [], new GenerationOptions(dryRun: true)));

        self::assertSame(FileStatus::Previewed, $result->files[0]->status);
        self::assertFalse($this->output->isFile('src/Fields/Star.php'));
        self::assertCount(1, $generated);
    }

    public function test_skips_existing_file_without_force(): void
    {
        $this->output->put('src/Fields/Star.php', '<?php // mine');

        /** @var list<object> $skipped */
        $skipped = [];
        $this->capture(FileSkipped::class, $skipped);

        $result = $this->manager->run(new GenerationRequest('field', 'star'));

        self::assertSame(FileStatus::Skipped, $result->files[0]->status);
        self::assertSame('<?php // mine', $this->output->get('src/Fields/Star.php'));
        self::assertCount(1, $skipped);
    }

    public function test_force_overwrites_existing_file(): void
    {
        $this->output->put('src/Fields/Star.php', '<?php // mine');

        $result = $this->manager->run(new GenerationRequest('field', 'star', [], new GenerationOptions(force: true)));

        self::assertSame(FileStatus::Created, $result->files[0]->status);
        self::assertStringContainsString('final class Star', $this->output->get('src/Fields/Star.php'));
    }

    public function test_dispatches_start_and_completion(): void
    {
        /** @var list<object> $started */
        $started = [];
        /** @var list<object> $completed */
        $completed = [];
        $this->capture(GenerationStarted::class, $started);
        $this->capture(GenerationCompleted::class, $completed);

        $this->manager->run(new GenerationRequest('command', 'greet'));

        self::assertCount(1, $started);
        self::assertCount(1, $completed);
    }

    public function test_unknown_generator_throws_and_emits_failure(): void
    {
        /** @var list<object> $failed */
        $failed = [];
        $this->capture(GenerationFailed::class, $failed);

        try {
            $this->manager->run(new GenerationRequest('nope', 'x'));
            self::fail('expected exception');
        } catch (GeneratorNotFoundException) {
            // expected
        }

        self::assertCount(1, $failed);
    }
}
