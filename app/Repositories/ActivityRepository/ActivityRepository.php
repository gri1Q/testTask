<?php

namespace App\Repositories\ActivityRepository;

use App\Models\Activity;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class ActivityRepository implements ActivityRepositoryInterface
{
    public function first(int $id): Activity
    {
        return Activity::query()->findOrFail($id);
    }

    public function getByIDs(array $ids): Collection
    {
        return Activity::query()->whereIn('id', $ids)->get();
    }

    public function getDescendantIDs(int $activityId): array
    {
        // Пример с adjacency list (parent_id). Для производительности лучше CTE.
        $result = [];
        $queue = [$activityId];

        while ($queue) {
            $parent = array_pop($queue);
            $children = Activity::query()->where('parent_id', $parent)->pluck('id')->all();
            foreach ($children as $c) {
                if (!isset($result[$c])) {
                    $result[$c] = true;
                    $queue[] = $c;
                }
            }
        }
        return array_keys($result);
    }

    public function getActivityIDsByOrganizationIDs(array $organizationIds): array
    {
        // pivot: organization_activity (organization_id, activity_id)
        $rows = DB::table('organization_activity')
            ->whereIn('organization_id', $organizationIds)
            ->select('organization_id', 'activity_id')
            ->get();

        $map = [];
        foreach ($rows as $r) {
            $map[$r->organization_id][] = (int)$r->activity_id;
        }
        foreach ($organizationIds as $id) {
            $map[$id] = $map[$id] ?? [];
        }
        return $map;
    }
}
