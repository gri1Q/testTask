<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\DTO\Activity\ActivityItem;
use App\Services\DTO\Organization\OrganizationItem;
use App\Services\OrganizationService;
use Generated\DTO\Activity as GeneratedActivity;
use Generated\DTO\Error;
use Generated\DTO\ListOrganizationsInBuildingResponse;
use Generated\DTO\ListOrganizationsResponse as GeneratedListOrganizationsResponse;
use Generated\DTO\NoContent404;
use Generated\DTO\Organization as GeneratedOrganization;
use Generated\DTO\OrganizationResponse as GeneratedOrganizationResponse;
use Generated\DTO\ValidationError;
use Generated\Http\Controllers\OrganizationsApiInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Throwable;

class OrganizationsController extends Controller implements OrganizationsApiInterface
{
    public function __construct(
        private OrganizationService $organizationService,
    ) {
    }

    public function listOrganizationsInBuilding(
        int $id,
    ): ListOrganizationsInBuildingResponse|NoContent404|Error {
        try {
            $response = $this->organizationService->listOrganizationsInBuilding($id);
        } catch (ModelNotFoundException $e) {
            return new NoContent404;
        } catch (Throwable $e) {
            report($e);

            return new Error('Что то пошло не так');
        }

        $organizations = array_map(
            fn(OrganizationItem $item): GeneratedOrganization => $this->makeTransportOrganization($item),
            $response->organizations,
        );

        return new ListOrganizationsInBuildingResponse($organizations);
    }

    /**
     * Получить организацию по её идентификатору.
     *
     * @param int $id
     * @return GeneratedOrganizationResponse|NoContent404|Error
     */
    public function getOrganization(int $id): GeneratedOrganizationResponse|NoContent404|Error
    {
        try {
            $item = $this->organizationService->getOrganization($id);
        } catch (ModelNotFoundException $e) {
            return new NoContent404;
        } catch (Throwable $e) {
            report($e);

            return new Error('Что то пошло не так');
        }

        $organization = $this->makeTransportOrganization($item);

        return new GeneratedOrganizationResponse($organization);
    }

    /**
     * Фильтрация и поиск организаций.
     */
    public function listOrganizations(
        ?string $name = null,
        ?int $activityID = null,
        ?int $buildingID = null,
    ): GeneratedListOrganizationsResponse|ValidationError|Error {
        try {
            $result = $this->organizationService->filterOrganizations(
                $name,
                $activityID,
                $buildingID,
            );
        } catch (Throwable $e) {
            report($e);
            return new Error('Что то пошло не так');
        }

        $organizations = array_map(
            fn(OrganizationItem $item): GeneratedOrganization => $this->makeTransportOrganization($item),
            $result->organizations,
        );

        return new GeneratedListOrganizationsResponse(
            $organizations
        );
    }

    /**
     * Преобразовать DTO сервиса в транспортный объект Organization.
     *
     * @param OrganizationItem $item
     * @return GeneratedOrganization
     */
    private function makeTransportOrganization(OrganizationItem $item): GeneratedOrganization
    {
        $activities = array_map(
            fn(ActivityItem $a): GeneratedActivity => $this->makeTransportActivity($a),
            $item->activities ?? []
        );

        return new GeneratedOrganization(
            $item->organizationID,
            $item->name,
            $item->buildingID,
            $item->description,
            $item->email,
            $item->phones,
            $activities,
            $item->createdAt,
            $item->updatedAt,
        );
    }

    /**
     * @param ActivityItem $a
     * @return GeneratedActivity
     */
    private function makeTransportActivity(ActivityItem $a): GeneratedActivity
    {
        return new GeneratedActivity(
            $a->id,
            $a->name

        );
    }
}
