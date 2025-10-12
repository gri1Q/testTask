<?php

namespace App\Repositories\ActivityRepository;

use Illuminate\Database\Eloquent\Collection;

interface ActivityRepositoryInterface
{
    /**
     * Поулчить по IDs.
     *
     * @param array $ids
     * @return Collection
     */
    public function getByIDs(array $ids): Collection;
}
