<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\OrganizationService;
use App\UseCases\DTO\Organization\OrganizationActivityItem as UseCaseOrganizationActivityItem;
use App\UseCases\DTO\Organization\OrganizationItem;
use App\UseCases\DTO\Organization\OrganizationListResponse as UseCaseOrganizationListResponse;
use App\UseCases\DTO\Organization\OrganizationPhoneItem as UseCaseOrganizationPhoneItem;
use App\UseCases\DTO\Organization\OrganizationResponse as UseCaseOrganizationResponse;
use App\UseCases\OrganizationUseCase;
use Generated\DTO\Error;
use Generated\DTO\NoContent404;
use Generated\DTO\Organization as OrganizationDTO;
use Generated\DTO\OrganizationActivity;
use Generated\DTO\OrganizationBuilding;
use Generated\DTO\OrganizationListResponse;
use Generated\DTO\OrganizationPhone;
use Generated\DTO\OrganizationResponse;
use Generated\DTO\ValidationError;
use Generated\Http\Controllers\OrganizationsApiInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Context;
use Throwable;

/**
 * Контроллер для работы сорганизациями.
 */
class OrganizationsController extends Controller implements OrganizationsApiInterface
{

    public function __construct(private readonly OrganizationService $organizationService)
    {
    }

    /**
     * Получить организацию по ID.
     *
     * @param int $id Идентификатор организации.
     * @return OrganizationResponse|NoContent404|Error Ответ с организацией или ошибка.
     */
    public function getOrganization(int $id): OrganizationResponse|NoContent404|Error
    {
        try {
            $response = $this->organizationService->getOrganization($id);
        } catch (ModelNotFoundException $e) {
            return new NoContent404;
        } catch (Throwable $e) {
            Context::add('organization_id', $id);
            report($e);

            return new Error('Что то пошло не так');
        }

        return $this->mapOrganizationResponse($response);
    }

    /**
     * Список организаций по виду деятельности.
     *
     * @param int $activityID Идентификатор вида деятельности.
     * @return OrganizationListResponse|NoContent404|Error Список организаций или ошибка.
     */
    public function listOrganizationsByActivity(int $activityID): OrganizationListResponse|NoContent404|Error
    {
        try {
            $response = $this->organizationService->listByActivity($activityID);
        } catch (ModelNotFoundException $e) {
            return new NoContent404;
        } catch (Throwable $e) {
            Context::add('activity_id', $activityID);
            report($e);

            return new Error('Что то пошло не так');
        }

        return $this->mapOrganizationListResponse($response);
    }

    /**
     * Список организаций в здании.
     *
     * @param int $buildingID Идентификатор здания.
     * @return OrganizationListResponse|NoContent404|Error Список организаций или ошибка.
     */
    public function listOrganizationsByBuilding(int $buildingID): OrganizationListResponse|NoContent404|Error
    {
        try {
            $response = $this->organizationService->listByBuilding($buildingID);
        } catch (ModelNotFoundException $e) {
            return new NoContent404;
        } catch (Throwable $e) {
            Context::add('building_id', $buildingID);
            report($e);

            return new Error('Что то пошло не так');
        }

        return $this->mapOrganizationListResponse($response);
    }

    /**
     * Список организаций в радиусе от точки.
     *
     * @param float $latitude Широта центра.
     * @param float $longitude Долгота центра.
     * @param float $radius Радиус поиска в метрах.
     * @return OrganizationListResponse|ValidationError|Error Список организаций или ошибка.
     */
    public function listOrganizationsNearby(
        float $latitude,
        float $longitude,
        float $radius,
    ): OrganizationListResponse|ValidationError|Error {
        $ve = $this->validateOrNull([
            'latitude' => $latitude,
            'longitude' => $longitude,
            'radius' => $radius,
        ], [
            'latitude' => ['required', 'numeric', 'gte:-90', 'lte:90'],
            'longitude' => ['required', 'numeric', 'gte:-180', 'lte:180'],
            'radius' => ['required', 'numeric', 'gt:0'],
        ], [
            'latitude.gte' => 'Широта не может быть ниже -90 градусов.',
            'latitude.lte' => 'Широта не может быть выше 90 градусов.',
            'longitude.gte' => 'Долгота не может быть меньше -180 градусов.',
            'longitude.lte' => 'Долгота не может быть больше 180 градусов.',
            'radius.gt' => 'Радиус должен быть больше нуля.',
        ]);
        if ($ve !== null) {
            return $ve;
        }

        try {
            $response = $this->organizationService->listByRadius($latitude, $longitude, $radius);
        } catch (Throwable $e) {
            Context::add('latitude', $latitude);
            Context::add('longitude', $longitude);
            Context::add('radius', $radius);
            report($e);

            return new Error('Что то пошло не так');
        }

        return $this->mapOrganizationListResponse($response);
    }

    /**
     * Поиск организаций по названию.
     *
     * @param string $name Часть названия организации.
     * @return OrganizationListResponse|ValidationError|Error Список организаций или ошибка.
     */
    public function searchOrganizationsByName(string $name): OrganizationListResponse|ValidationError|Error
    {
        $ve = $this->validateOrNull([
            'name' => $name,
        ], [
            'name' => ['required', 'string', 'min:1', 'max:255'],
        ], [
            'name.required' => 'Укажите часть названия организации.',
            'name.min' => 'Название не может быть пустым.',
            'name.max' => 'Название слишком длинное (максимум 255 символов).',
        ]);
        if ($ve !== null) {
            return $ve;
        }

        try {
            $response = $this->organizationService->searchByName($name);
        } catch (Throwable $e) {
            Context::add('search_name', $name);
            report($e);

            return new Error('Что то пошло не так');
        }

        return $this->mapOrganizationListResponse($response);
    }

    /**
     * Преобразует ответ use case со списком организаций в транспортный DTO.
     *
     * @param UseCaseOrganizationListResponse $response Ответ use case.
     * @return OrganizationListResponse Транспортный DTO.
     */
    private function mapOrganizationListResponse(UseCaseOrganizationListResponse $response): OrganizationListResponse
    {
        $organizations = array_map(
            fn(OrganizationItem $item): OrganizationDTO => $this->mapOrganizationItem($item),
            $response->organizations,
        );

        return new OrganizationListResponse($organizations);
    }

    /**
     * Преобразует ответ use case с организацией в транспортный DTO.
     *
     * @param UseCaseOrganizationResponse $response Ответ use case.
     * @return OrganizationResponse Транспортный DTO.
     */
    private function mapOrganizationResponse(UseCaseOrganizationResponse $response): OrganizationResponse
    {
        return new OrganizationResponse($this->mapOrganizationItem($response->organization));
    }

    /**
     * Конвертирует DTO сервиса организации в транспортный объект.
     *
     * @param OrganizationItem $item DTO организации из use case.
     * @return OrganizationDTO Транспортный объект организации.
     */
    private function mapOrganizationItem(OrganizationItem $item): OrganizationDTO
    {
        $building = new OrganizationBuilding(
            $item->building->id,
            $item->building->address,
            $item->building->latitude,
            $item->building->longitude,
        );

        $phones = array_map(
            static fn(UseCaseOrganizationPhoneItem $phone): OrganizationPhone => new OrganizationPhone($phone->phone),
            $item->phones,
        );

        $activities = array_map(
            static fn(UseCaseOrganizationActivityItem $activity): OrganizationActivity => new OrganizationActivity(
                $activity->id,
                $activity->name,
                $activity->level,
                $activity->parentID,
            ),
            $item->activities,
        );

        return new OrganizationDTO(
            $item->id,
            $item->name,
            $building,
            $phones,
            $activities,
        );
    }
}
