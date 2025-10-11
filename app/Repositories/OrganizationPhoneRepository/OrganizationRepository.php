<?php

namespace App\Repositories\OrganizationPhoneRepository;

use App\Models\Building;

class OrganizationRepository
{
    public function create(Building $building)
    {
        $building->save();
    }

    public function update(Building $building)
    {
        $building->save();
    }

    public function delete(Building $building)
    {
        $building->delete();
    }

    public function getByID(int $id)
    {
        return Building::query()->findOrFail($id);
    }

    public function getAll()
    {
        return Building::all();
    }
}
