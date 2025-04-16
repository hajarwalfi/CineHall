<?php

namespace App\Providers;

use App\Repositories\Interfaces\ReservationRepositoryInterface;
use App\Repositories\Interfaces\SalleRepositoryInterface;
use App\Repositories\Interfaces\SiegeRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\ReservationRepository;
use App\Repositories\SalleRepository;
use App\Repositories\SiegeRepository;
use App\Repositories\UserRepository;
use App\Repositories\Interfaces\FilmRepositoryInterface;
use App\Repositories\FilmRepository;
use App\Repositories\Interfaces\SeanceRepositoryInterface;
use App\Repositories\SeanceRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */

    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);        $this->app->bind(FilmRepositoryInterface::class, FilmRepository::class);
        $this->app->bind(SeanceRepositoryInterface::class, SeanceRepository::class);
        $this->app->bind(SiegeRepositoryInterface::class, SiegeRepository::class);
        $this->app->bind(ReservationRepositoryInterface::class, ReservationRepository::class);
        $this->app->bind(SalleRepositoryInterface::class, SalleRepository::class);
    }


    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
