<?php

namespace App\Providers;

use App\Http\Controllers\Api\BuildingsController;
use App\Http\Controllers\Api\OrganizationsController;
use App\Repositories\ActivityRepository\ActivityRepository;
use App\Repositories\ActivityRepository\ActivityRepositoryInterface;
use App\Repositories\BuildingRepository\BuildingRepository;
use App\Repositories\BuildingRepository\BuildingRepositoryInterface;
use App\Repositories\OrganizationActivityRepository\OrganizationActivityRepository;
use App\Repositories\OrganizationActivityRepository\OrganizationActivityRepositoryInterface;
use App\Repositories\OrganizationPhoneRepository\OrganizationPhoneRepository;
use App\Repositories\OrganizationPhoneRepository\OrganizationPhoneRepositoryInterface;
use App\Repositories\OrganizationRepository\OrganizationRepository;
use App\Repositories\OrganizationRepository\OrganizationRepositoryInterface;
use App\Services\ActivityService;
use App\Services\BuildingService;
use App\Services\OrganizationService;
use Generated\Http\Controllers\BuildingsApiInterface;
use Generated\Http\Controllers\OrganizationsApiInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(OrganizationRepositoryInterface::class, OrganizationRepository::class);
        $this->app->bind(OrganizationPhoneRepositoryInterface::class, OrganizationPhoneRepository::class);
        $this->app->bind(BuildingRepositoryInterface::class, BuildingRepository::class);
        $this->app->bind(OrganizationActivityRepositoryInterface::class, OrganizationActivityRepository::class);
        $this->app->bind(ActivityRepositoryInterface::class, ActivityRepository::class);

        $this->app->singleton(OrganizationService::class);
        $this->app->singleton(BuildingService::class);
        $this->app->singleton(ActivityService::class);

        $this->app->singleton(BuildingsApiInterface::class, BuildingsController::class);
        $this->app->singleton(OrganizationsApiInterface::class, OrganizationsController::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
