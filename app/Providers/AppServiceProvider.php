<?php

namespace App\Providers;

use App\Enums\Roles;
use App\Repositories\Bill\BillRepository;
use App\Repositories\Bill\BillRepositoryInterface;
use App\Repositories\BillCategory\BillCategoryRepository;
use App\Repositories\BillCategory\BillCategoryRepositoryInterface;
use App\Repositories\Building\BuildingRepository;
use App\Repositories\Building\BuildingRepositoryInterface;
use App\Repositories\Flat\FlatRepository;
use App\Repositories\Flat\FlatRepositoryInterface;
use App\Repositories\Tenant\TenantRepository;
use App\Repositories\Tenant\TenantRepositoryInterface;
use App\Repositories\User\UserRepository;
use App\Repositories\User\UserRepositoryInterface;
use App\Services\EmailService;
use App\Services\LogService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Psr\Log\LoggerInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(BuildingRepositoryInterface::class, BuildingRepository::class);
        $this->app->bind(FlatRepositoryInterface::class, FlatRepository::class);
        $this->app->bind(TenantRepositoryInterface::class, TenantRepository::class);
        $this->app->bind(BillCategoryRepositoryInterface::class, BillCategoryRepository::class);
        $this->app->bind(BillRepositoryInterface::class, BillRepository::class);


        $this->app->singleton(LogService::class, function ($app) {
            return new LogService($app->make(LoggerInterface::class));
        });

        $this->app->singleton(EmailService::class, function () {
            return new EmailService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Blade::if('admin', function () {
            return Auth::check() && Auth::user()->role === Roles::ADMIN;
        });
    }
}
