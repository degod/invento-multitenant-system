<?php

namespace App\Providers;

use App\Enums\Roles;
use App\Models\BillCategory;
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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

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