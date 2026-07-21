<?php

declare(strict_types=1);

namespace OpenMeta\Generator\Manager;

use OpenMeta\Core\Contracts\EventDispatcherInterface;
use OpenMeta\Generator\Configuration\GeneratorConfiguration;
use OpenMeta\Generator\Contracts\ConflictDetectorInterface;
use OpenMeta\Generator\Contracts\FileProcessorInterface;
use OpenMeta\Generator\Contracts\GeneratorManagerInterface;
use OpenMeta\Generator\Contracts\GeneratorRegistryInterface;
use OpenMeta\Generator\Events\FileGeneratedEvent;
use OpenMeta\Generator\Events\FileSkipped;
use OpenMeta\Generator\Events\GenerationCompleted;
use OpenMeta\Generator\Events\GenerationFailed;
use OpenMeta\Generator\Events\GenerationStarted;
use OpenMeta\Generator\Files\Conflict;
use OpenMeta\Generator\Files\ConflictType;
use OpenMeta\Generator\Files\FileGenerator;
use OpenMeta\Generator\Files\FileStatus;
use OpenMeta\Generator\Files\GeneratedFile;
use Throwable;

/**
 * Runs generators: resolves the generator, produces files, applies processors,
 * gates on conflicts, honours dry-run/preview, writes, and emits events.
 *
 * Existing files are never overwritten unless {@see GenerationOptions::$force}
 * is set; reserved-namespace and naming-collision conflicts always skip.
 */
final class GeneratorManager implements GeneratorManagerInterface
{
    /** @var list<FileProcessorInterface> */
    private array $processors = [];

    public function __construct(
        private readonly GeneratorRegistryInterface $registry,
        private readonly FileGenerator $files,
        private readonly ConflictDetectorInterface $conflicts,
        private readonly GeneratorConfiguration $config,
        private readonly EventDispatcherInterface $events,
    ) {
    }

    public function generators(): GeneratorRegistryInterface
    {
        return $this->registry;
    }

    public function addProcessor(FileProcessorInterface $processor): void
    {
        $this->processors[] = $processor;
    }

    public function run(GenerationRequest $request): GenerationResult
    {
        $this->events->dispatch(new GenerationStarted($request->key, $request->name, $request->options->dryRun));

        try {
            $generator = $this->registry->get($request->key);
            $outcomes = [];

            foreach ($generator->generate($request, $this->config) as $file) {
                $outcomes[] = $this->handleFile($this->applyProcessors($file), $request->options);
            }

            $result = new GenerationResult($request->key, $outcomes, $request->options->dryRun);

            $this->events->dispatch(new GenerationCompleted(
                $request->key,
                count($result->written()),
                count($result->skipped()),
            ));

            return $result;
        } catch (Throwable $exception) {
            $this->events->dispatch(new GenerationFailed($request->key, $exception));

            throw $exception;
        }
    }

    private function handleFile(GeneratedFile $file, GenerationOptions $options): FileOutcome
    {
        $conflict = $this->conflicts->detect($file->path, $file->fqcn, $this->config);

        if ($conflict !== null && ! $this->overridable($conflict, $options)) {
            $this->events->dispatch(new FileSkipped($file->path, $conflict));

            return new FileOutcome($file->path, FileStatus::Skipped, $file->contents, $conflict);
        }

        if ($options->writesToDisk()) {
            $this->files->write($file);
            $status = FileStatus::Created;
        } else {
            $status = FileStatus::Previewed;
        }

        $this->events->dispatch(new FileGeneratedEvent($file->path, ! $options->writesToDisk()));

        return new FileOutcome($file->path, $status, $file->contents, $conflict);
    }

    /**
     * Only an existing file may be overridden, and only with an explicit force.
     */
    private function overridable(Conflict $conflict, GenerationOptions $options): bool
    {
        return $conflict->type === ConflictType::ExistingFile && $options->force;
    }

    private function applyProcessors(GeneratedFile $file): GeneratedFile
    {
        foreach ($this->processors as $processor) {
            $file = $processor->process($file);
        }

        return $file;
    }
}
