<?php

namespace Hanoivip\Iap;

use Hanoivip\Iap\Services\IapService;
use Illuminate\Support\ServiceProvider;
use Hanoivip\Iap\Services\IOrderGenerator;
use Hanoivip\Iap\Services\GameOrder;
use Hanoivip\GameContracts\Contracts\IGameOperator;
use Hanoivip\Iap\Services\SelfOrder;

class ModServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../views' => resource_path('views/vendor/hanoivip'),
            __DIR__.'/../lang' => resource_path('lang/vendor/hanoivip'),
            __DIR__.'/../config' => config_path(),
            __DIR__.'/../resources/assets' => resource_path('assets/vendor/hanoivip'),
            __DIR__.'/../resources/images' => public_path('images'),
        ]);
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadViewsFrom(__DIR__ . '/../views', 'hanoivip');
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');
        $this->loadTranslationsFrom( __DIR__.'/../lang', 'hanoivip');
        $this->mergeConfigFrom( __DIR__.'/../config/iap.php', 'iap');
    }

    public function register()
    {
        $this->commands([
        ]);
        $this->app->bind("LocalIapService", IapService::class);
        $this->app->bind(IOrderGenerator::class, function () {
            $order = config('iap.order');
            if ($order == 'game')
                return new GameOrder($this->app->make(IGameOperator::class));
            return new SelfOrder();
        });
    }
}
