<?php

declare(strict_types=1);

namespace OpenMeta\Sdk\Manifest;

use OpenMeta\Sdk\Exceptions\InvalidManifestException;

/**
 * Builds and validates {@see Manifest} instances from arrays or JSON files.
 */
final class ManifestFactory
{
    private const REQUIRED_FIELDS = ['packageId', 'name', 'vendor', 'version'];

    /**
     * @param array<string, mixed> $data
     *
     * @throws InvalidManifestException
     */
    public function fromArray(array $data): Manifest
    {
        foreach (self::REQUIRED_FIELDS as $field) {
            if (! isset($data[$field]) || ! is_string($data[$field]) || trim($data[$field]) === '') {
                throw InvalidManifestException::missingField($field);
            }
        }

        return new Manifest(
            $data['packageId'],
            $data['name'],
            $data['vendor'],
            $data['version'],
            $this->string($data, 'description'),
            $this->string($data, 'author'),
            $this->string($data, 'license'),
            $this->parseDependencies($data['dependencies'] ?? []),
            $this->nullableString($data, 'minimumCoreVersion'),
            $this->nullableString($data, 'maximumCoreVersion'),
            $this->parseProviders($data['providers'] ?? []),
            $this->assocArray($data, 'assets'),
            $this->assocArray($data, 'commands'),
            $this->assocArray($data, 'configuration'),
            $this->stringList($data['permissions'] ?? []),
            $this->parseFeatureFlags($data['featureFlags'] ?? []),
            $this->parseRequirements($data['requirements'] ?? []),
        );
    }

    /**
     * Read and parse a JSON manifest file.
     *
     * @throws InvalidManifestException
     */
    public function fromJson(string $json, string $source = '<string>'): Manifest
    {
        /** @var mixed $decoded */
        $decoded = json_decode($json, true);

        if (! is_array($decoded)) {
            throw InvalidManifestException::invalidJson($source);
        }

        /** @var array<string, mixed> $decoded */
        return $this->fromArray($decoded);
    }

    /**
     * @param array<string, mixed> $data
     */
    private function string(array $data, string $key): string
    {
        $value = $data[$key] ?? '';

        return is_string($value) ? $value : '';
    }

    /**
     * @param array<string, mixed> $data
     */
    private function nullableString(array $data, string $key): ?string
    {
        $value = $data[$key] ?? null;

        return is_string($value) && $value !== '' ? $value : null;
    }

    /**
     * @param array<string, mixed> $data
     *
     * @return array<string, mixed>
     */
    private function assocArray(array $data, string $key): array
    {
        $value = $data[$key] ?? [];

        if (! is_array($value)) {
            return [];
        }

        $result = [];

        foreach ($value as $itemKey => $itemValue) {
            $result[(string) $itemKey] = $itemValue;
        }

        return $result;
    }

    /**
     * @param mixed $value
     *
     * @return list<string>
     */
    private function stringList(mixed $value): array
    {
        if (! is_array($value)) {
            return [];
        }

        $result = [];

        foreach ($value as $item) {
            if (is_string($item) && $item !== '') {
                $result[] = $item;
            }
        }

        return $result;
    }

    /**
     * @param mixed $value
     *
     * @return list<class-string>
     */
    private function parseProviders(mixed $value): array
    {
        /** @var list<class-string> $providers */
        $providers = $this->stringList($value);

        return $providers;
    }

    /**
     * @param mixed $value
     *
     * @return list<Dependency>
     *
     * @throws InvalidManifestException
     */
    private function parseDependencies(mixed $value): array
    {
        if (! is_array($value)) {
            return [];
        }

        $dependencies = [];

        foreach ($value as $key => $entry) {
            // Map form: { "vendor/pkg": "^1.0" }
            if (is_string($key)) {
                if (! is_string($entry)) {
                    throw InvalidManifestException::invalidField('dependencies', 'constraint must be a string');
                }

                $dependencies[] = new Dependency($key, $entry);

                continue;
            }

            // List form: [ { "package": "vendor/pkg", "constraint": "^1.0", "optional": false } ]
            if (! is_array($entry) || ! isset($entry['package']) || ! is_string($entry['package'])) {
                throw InvalidManifestException::invalidField('dependencies', 'each entry needs a package id');
            }

            $constraint = isset($entry['constraint']) && is_string($entry['constraint'])
                ? $entry['constraint']
                : '*';
            $optional = isset($entry['optional']) && $entry['optional'] === true;

            $dependencies[] = new Dependency($entry['package'], $constraint, $optional);
        }

        return $dependencies;
    }

    /**
     * @param mixed $value
     *
     * @return array<string, bool>
     */
    private function parseFeatureFlags(mixed $value): array
    {
        if (! is_array($value)) {
            return [];
        }

        $flags = [];

        foreach ($value as $key => $enabled) {
            $flags[(string) $key] = $enabled === true;
        }

        return $flags;
    }

    /**
     * @param mixed $value
     */
    private function parseRequirements(mixed $value): Requirements
    {
        if (! is_array($value)) {
            return new Requirements();
        }

        $php = isset($value['php']) && is_string($value['php']) ? $value['php'] : null;
        $wordpress = isset($value['wordpress']) && is_string($value['wordpress']) ? $value['wordpress'] : null;
        $extensions = $this->stringList($value['phpExtensions'] ?? []);

        return new Requirements($php, $wordpress, $extensions);
    }
}
