<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\OrganizationService;
use Generated\DTO\Error;
use Generated\DTO\ListOrganizationsRequest;
use Generated\DTO\ListOrganizationsResponse;
use Generated\DTO\NoContent401;
use Generated\DTO\NoContent404;
use Generated\DTO\OrganizationResponse;
use Generated\DTO\ValidationError;
use Generated\Http\Controllers\OrganizationsApiInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;
use Throwable;

class OrganizationsController extends Controller implements OrganizationsApiInterface
{
    public function __construct(
        private OrganizationService $organizationService,
    ) {}

    /**
     * Список организаций с фильтрами и пагинацией.
     */
    public function listOrganizations(
        ?int $buildingId,
        ?int $activityId,
        ?string $search,
        ?float $latitude,
        ?float $longitude,
        ?float $radius,
        ?int $page,
        ?int $perPage,
    ): ListOrganizationsResponse|ValidationError|NoContent401|Error {
        $validationError = $this->validateOrNull(
            [
                'building_id' => $buildingId,
                'activity_id' => $activityId,
                'search' => $search,
                'latitude' => $latitude,
                'longitude' => $longitude,
                'radius' => $radius,
                'page' => $page,
                'per_page' => $perPage,
            ],
            [
                'building_id' => ['nullable', 'integer', 'min:1'],
                'activity_id' => ['nullable', 'integer', 'min:1'],
                'search' => ['nullable', 'string', 'min:2', 'max:255'],
                'latitude' => ['nullable', 'numeric', 'between:-90,90'],
                'longitude' => ['nullable', 'numeric', 'between:-180,180'],
                'radius' => ['nullable', 'numeric', 'min:0.1', 'max:100'],
                'page' => ['nullable', 'integer', 'min:1'],
                'per_page' => ['nullable', 'integer', 'min:1', 'max:50'],
            ],
            [
                'building_id.integer' => 'ID здания должен быть целым числом.',
                'building_id.min' => 'ID здания должен быть положительным.',
                'activity_id.integer' => 'ID вида деятельности должен быть целым числом.',
                'activity_id.min' => 'ID вида деятельности должен быть положительным.',
                'search.min' => 'Поисковый запрос должен содержать минимум два символа.',
                'search.max' => 'Поисковый запрос слишком длинный.',
                'latitude.numeric' => 'Широта должна быть числом.',
                'latitude.between' => 'Широта должна быть в диапазоне от -90 до 90.',
                'longitude.numeric' => 'Долгота должна быть числом.',
                'longitude.between' => 'Долгота должна быть в диапазоне от -180 до 180.',
                'radius.numeric' => 'Радиус должен быть числом.',
                'radius.min' => 'Радиус должен быть больше 0.',
                'radius.max' => 'Радиус не может превышать 100 км.',
                'page.integer' => 'Номер страницы должен быть целым числом.',
                'page.min' => 'Номер страницы начинается с 1.',
                'per_page.integer' => 'Размер страницы должен быть целым числом.',
                'per_page.min' => 'Размер страницы не меньше 1.',
                'per_page.max' => 'Размер страницы не может превышать 50 записей.',
            ],
        );

        if ($validationError !== null) {
            return $validationError;
        }

        // Проверка на частично заполненные геопараметры
        $geoParameters = [$latitude, $longitude, $radius];
        $filledGeoParameters = array_filter($geoParameters, static fn($v) => $v !== null);
        if ($filledGeoParameters !== [] && count($filledGeoParameters) !== 3) {
            return new ValidationError('Для геопоиска укажите широту, долготу и радиус.');
        }

        $pageValue = $page ?? 1;
        $perPageValue = $perPage ?? 20;

        try {
            return $this->organizationService->listOrganizations(
                new ListOrganizationsRequest(
                    $buildingId,
                    $activityId,
                    $search,
                    $latitude,
                    $longitude,
                    $radius,
                    $pageValue,
                    $perPageValue,
                )
            );
        } catch (Throwable $exception) {
            // Используем стандартный логгер, чтобы избежать неопределённого Context::add
            Log::withContext([
                'building_id' => $buildingId,
                'activity_id' => $activityId,
                'search'      => $search,
                'latitude'    => $latitude,
                'longitude'   => $longitude,
                'radius'      => $radius,
                'page'        => $pageValue,
                'per_page'    => $perPageValue,
            ]);
            report($exception);

            return new Error('Что-то пошло не так.');
        }
    }

    /**
     * Детальная информация об организации.
     */
    public function getOrganization(int $id): OrganizationResponse|NoContent401|NoContent404|Error
    {
        try {
            return $this->organizationService->getOrganization($id);
        } catch (ModelNotFoundException $exception) {
            return new NoContent404();
        } catch (Throwable $exception) {
            Log::withContext(['organization_id' => $id]);
            report($exception);

            return new Error('Что-то пошло не так.');
        }
    }
}
