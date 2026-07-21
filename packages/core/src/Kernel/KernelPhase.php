<?php

declare(strict_types=1);

namespace OpenMeta\Core\Kernel;

/**
 * Kernel lifecycle phases.
 *
 *   Bootstrap
 *       ↓
 *   Initialize
 *       ↓
 *   Ready
 *
 * No WordPress-specific phases.
 */
enum KernelPhase: string
{
    case Pending = 'pending';
    case Bootstrap = 'bootstrap';
    case Initialize = 'initialize';
    case Ready = 'ready';
}
