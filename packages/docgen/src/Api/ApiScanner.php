<?php

declare(strict_types=1);

namespace OpenMeta\Docgen\Api;

use FilesystemIterator;
use OpenMeta\Docgen\Model\MethodDoc;
use OpenMeta\Docgen\Model\TypeDoc;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ReflectionClass;
use ReflectionMethod;
use ReflectionNamedType;
use ReflectionType;
use SplFileInfo;

/**
 * Scans a package `src/` tree and reflects its public API into {@see TypeDoc}s,
 * drawing summaries from PHPDoc class comments.
 */
final class ApiScanner
{
    /**
     * @return list<TypeDoc>
     */
    public function scan(string $srcDir): array
    {
        if (! is_dir($srcDir)) {
            return [];
        }

        $types = [];

        foreach ($this->phpFiles($srcDir) as $file) {
            $fqcn = $this->fqcnFor($file);

            if ($fqcn === null || ! $this->typeExists($fqcn)) {
                continue;
            }

            $types[] = $this->documentType($fqcn);
        }

        usort($types, static fn (TypeDoc $a, TypeDoc $b): int => strcmp($a->fqcn, $b->fqcn));

        return $types;
    }

    private function documentType(string $fqcn): TypeDoc
    {
        $reflection = new ReflectionClass($fqcn);
        $methods = [];

        foreach ($reflection->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
            if ($method->getDeclaringClass()->getName() !== $fqcn || $method->isConstructor()) {
                continue;
            }

            $methods[] = new MethodDoc(
                $method->getName(),
                $this->signature($method),
                $this->summary($method->getDocComment()),
            );
        }

        usort($methods, static fn (MethodDoc $a, MethodDoc $b): int => strcmp($a->name, $b->name));

        $constants = array_map(
            static fn (int|string $name): string => (string) $name,
            array_keys($reflection->getConstants()),
        );
        sort($constants);

        return new TypeDoc(
            $fqcn,
            $this->kind($reflection),
            $this->summary($reflection->getDocComment()),
            $methods,
            $constants,
        );
    }

    private function kind(ReflectionClass $reflection): string
    {
        if ($reflection->isInterface()) {
            return 'interface';
        }

        if ($reflection->isEnum()) {
            return 'enum';
        }

        if ($reflection->isTrait()) {
            return 'trait';
        }

        if ($reflection->isAbstract()) {
            return 'abstract class';
        }

        return 'class';
    }

    private function signature(ReflectionMethod $method): string
    {
        $params = [];

        foreach ($method->getParameters() as $parameter) {
            $type = $this->typeString($parameter->getType());
            $params[] = trim($type . ' $' . $parameter->getName());
        }

        $return = $this->typeString($method->getReturnType());

        return $method->getName() . '(' . implode(', ', $params) . ')' . ($return === '' ? '' : ': ' . $return);
    }

    private function typeString(?ReflectionType $type): string
    {
        if ($type instanceof ReflectionNamedType) {
            $name = $type->getName();
            $short = strrpos($name, '\\') === false ? $name : substr($name, (int) strrpos($name, '\\') + 1);

            return ($type->allowsNull() && $name !== 'null' && $name !== 'mixed' ? '?' : '') . $short;
        }

        return $type === null ? '' : (string) $type;
    }

    private function summary(string|false $docComment): string
    {
        if ($docComment === false) {
            return '';
        }

        foreach (explode("\n", $docComment) as $line) {
            $line = trim($line, " \t*/");

            if ($line === '' || str_starts_with($line, '@')) {
                continue;
            }

            return $line;
        }

        return '';
    }

    /**
     * @return list<string>
     */
    private function phpFiles(string $dir): array
    {
        $files = [];
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS),
        );

        foreach ($iterator as $file) {
            if ($file instanceof SplFileInfo && $file->isFile() && $file->getExtension() === 'php') {
                $files[] = $file->getPathname();
            }
        }

        sort($files);

        return $files;
    }

    private function fqcnFor(string $file): ?string
    {
        $code = (string) file_get_contents($file);
        $typePattern = '/(?:final\s+|abstract\s+|readonly\s+)*(?:class|interface|trait|enum)\s+(\w+)/';

        if (
            preg_match('/namespace\s+([^;]+);/', $code, $ns) !== 1
            || preg_match($typePattern, $code, $type) !== 1
        ) {
            return null;
        }

        return trim($ns[1]) . '\\' . $type[1];
    }

    private function typeExists(string $fqcn): bool
    {
        return class_exists($fqcn) || interface_exists($fqcn) || trait_exists($fqcn) || enum_exists($fqcn);
    }
}
