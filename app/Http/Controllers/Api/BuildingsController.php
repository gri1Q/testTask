<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\BuildingService;
use App\Services\DTO\Building\BuildingItem;
use Generated\DTO\Building as GeneratedBuilding;
use Generated\DTO\Error;
use Generated\DTO\GetBuildingResponse as GeneratedGetBuildingResponse;
use Generated\DTO\ListBuildingsResponse as GeneratedListBuildingsResponse;
use Generated\DTO\NoContent404;
use Generated\DTO\ValidationError;
use Generated\DTO\ValidationErrorItem;
use Generated\Http\Controllers\BuildingsApiInterface;
use Illuminate\Contracts\Validation\Validator as ValidatorContract;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Context;
use Illuminate\Support\Facades\Validator;
use Throwable;

/**
 * Контроллер зданий.
 */
class BuildingsController extends Controller implements BuildingsApiInterface
{

    public function __construct(private BuildingService $buildingService)
    {
    }


    /**
     * Получить список зданий.
     *
     * @return GeneratedListBuildingsResponse|ValidationError|Error
     */
    public function listBuildings(): GeneratedListBuildingsResponse|ValidationError|Error
    {
        try {
            $response = $this->buildingService->listBuildings();
        } catch (Throwable $e) {
            report($e);

            return new Error('Что то пошло не так');
        }

        $buildings = array_map(
            fn(BuildingItem $item): GeneratedBuilding => $this->makeTransportBuilding($item),
            $response->buildings,
        );

        return new GeneratedListBuildingsResponse($buildings);
    }


    /**
     * Получить данные одного здания.
     *
     * @param int $id
     * @return GeneratedGetBuildingResponse|NoContent404|Error
     */
    public function getBuilding(int $id): GeneratedGetBuildingResponse|NoContent404|Error
    {
        try {
            $response = $this->buildingService->getBuilding($id);
        } catch (ModelNotFoundException $e) {
            return new NoContent404;
        } catch (Throwable $e) {
            Context::add('building_id', $id);
            report($e);

            return new Error('Что то пошло не так');
        }

        return new GeneratedGetBuildingResponse(
            $this->makeTransportBuilding($response->building),
        );
    }

    /**
     * Преобразовать DTO сервиса в транспортный объект.
     *
     * @param BuildingItem $item
     * @return GeneratedBuilding
     */
    private function makeTransportBuilding(BuildingItem $item): GeneratedBuilding
    {
        return new GeneratedBuilding(
            $item->buildingID,
            $item->address,
            $item->latitude,
            $item->longitude,
            $item->createdAt,
            $item->updatedAt,
        );
    }


    /**
     * Валидирует данные и возвращает ошибку или null.
     *
     * @param array $data
     * @param array $rules
     * @param array $messages
     * @return ValidationError|null
     */
    private function validateOrNull(array $data, array $rules, array $messages = []): ?ValidationError
    {
        $validator = Validator::make($data, $rules, $messages);
        if ($validator->fails()) {
            return $this->makeValidationError($validator);
        }

        return null;
    }


    /**
     * Сформировать объект ошибки валидации из валидатора Laravel.
     *
     * @param ValidatorContract $validator
     * @return ValidationError
     */
    private function makeValidationError(ValidatorContract $validator): ValidationError
    {
        $errorItems = [];
        foreach ($validator->errors()->toArray() as $field => $messages) {
            foreach ($messages as $message) {
                $errorItems[] = new ValidationErrorItem($message, $field);
            }
        }

        return new ValidationError(null, $errorItems);
    }
}
