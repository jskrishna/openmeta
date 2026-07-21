<?php

declare(strict_types=1);

namespace OpenMeta\Rest\Contracts;

use OpenMeta\Rest\Requests\Request;
use OpenMeta\Rest\Routes\Route;

/**
 * REST route table contract.
 */
interface RouterInterface
{
    public function add(Route $route): void;

    /**
     * @return array{route: Route, params: array<string, string>}
     */
    public function match(Request $request): array;

    /** @return list<Route> */
    public function routes(): array;
}
