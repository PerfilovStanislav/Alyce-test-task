<?php

namespace App\Providers;

use App\Adapters\UserAdapter;
use App\Interfaces\RightsServiceInterface;
use App\Interfaces\UserAdapterInterface;
use App\Services\RightsService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(RightsServiceInterface::class, function(){
            return resolve(RightsService::class);
        });
        $this->app->singleton(UserAdapterInterface::class, function(){
            return resolve(UserAdapter::class);
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
