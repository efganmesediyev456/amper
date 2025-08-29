<?php

namespace App\Providers;

use App\Models\Language;
use App\Repositories\CacheComparisonRepository;
use App\Repositories\Contracts\CacheComparisonRepositoryInterface;
use App\Repositories\Contracts\ProductReviewRepositoryInterface;
use App\Repositories\Contracts\SessionComparisonRepositoryInterface;
use App\Repositories\Contracts\UserCardRepositoryInterface;
use App\Repositories\Contracts\UserComparisonRepositoryInterface;
use App\Repositories\Contracts\UserFavoriteRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\ProductReviewRepository;
use App\Repositories\SessionComparisonRepository;
use App\Repositories\UserCardRepository;
use App\Repositories\UserComparisonRepository;
use App\Repositories\UserFavoriteRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Laravel\Passport\Passport;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(ProductReviewRepositoryInterface::class, ProductReviewRepository::class);
        $this->app->bind(UserFavoriteRepositoryInterface::class,UserFavoriteRepository::class);
        $this->app->bind(UserCardRepositoryInterface::class,UserCardRepository::class);
        $this->app->bind(UserComparisonRepositoryInterface::class,UserComparisonRepository::class);
        $this->app->bind(UserComparisonRepositoryInterface::class,UserComparisonRepository::class);
        $this->app->bind(SessionComparisonRepositoryInterface::class, SessionComparisonRepository::class);
        $this->app->bind(CacheComparisonRepositoryInterface::class, CacheComparisonRepository::class);

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        try {

            if (Schema::hasTable('languages')) {
                $languages = Language::get();
                view()->share('languages', $languages);
            }
        } catch (\Exception $e) {
        }


        Passport::enablePasswordGrant();
    }
}
