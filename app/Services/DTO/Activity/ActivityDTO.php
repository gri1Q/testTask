<?php

declare(strict_types=1);

namespace App\Services\DTO\Activity;

class ActivityDTO
{
    public function __construct(
        public int $id,
        public string $name,
        public int $level,
        public ?int $parentId,
    ) {}
}
