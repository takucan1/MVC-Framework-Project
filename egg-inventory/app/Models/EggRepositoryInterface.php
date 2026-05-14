<?php

declare(strict_types=1);

namespace App\Models;

use Core\Database\Findable;
use Core\Database\Persistable;

/**
 * DIP: EggController depends on this interface, not a concrete repository.
 * ISP: Inherits only Findable and Persistable — no unused methods.
 */
interface EggRepositoryInterface extends Findable, Persistable
{
    /** Find all eggs of a given type (quail / white / brown). */
    public function findByType(string $type): array;

    /** Summarize total stock grouped by egg type. */
    public function stockSummary(): array;
}
