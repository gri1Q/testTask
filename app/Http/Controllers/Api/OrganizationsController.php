<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Generated\DTO\Error;
use Generated\DTO\ListOrganizationsResponse;
use Generated\DTO\NoContent401;
use Generated\DTO\NoContent404;
use Generated\DTO\OrganizationResponse;
use Generated\DTO\ValidationError;
use Generated\Http\Controllers\OrganizationsApiInterface;

class OrganizationsController extends Controller implements OrganizationsApiInterface
{
    //
    public function getOrganization(int $id,
    ): OrganizationResponse|NoContent401|NoContent404|Error {
        // TODO: Implement getOrganization() method.
    }

    public function listOrganizations(
        ?int $buildingId,
        ?int $activityId,
        ?string $name,
        ?float $latitude,
        ?float $longitude,
        ?int $radiusMeters,
        ?int $limit,
    ): ListOrganizationsResponse|ValidationError|NoContent401|Error {
        // TODO: Implement listOrganizations() method.
    }
}
