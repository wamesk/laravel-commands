<?php

namespace Wame\LaravelCommands;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Wame\LaravelCommands\Console\Commands\WameApiController;
use Wame\LaravelCommands\Console\Commands\WameEvents;
use Wame\LaravelCommands\Console\Commands\WameListeners;
use Wame\LaravelCommands\Console\Commands\WameMake;
use Wame\LaravelCommands\Console\Commands\WameMigration;
use Wame\LaravelCommands\Console\Commands\WameModel;
use Wame\LaravelCommands\Console\Commands\WameObserver;

class LaravelCommandsServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/wame-commands.php', 'wame-commands');
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            // Export configs
            $this->publishConfigs();

            // Registering commands
            $this->commands([
                WameApiController::class,
                WameEvents::class,
                WameListeners::class,
                WameMake::class,
                WameMigration::class,
                WameModel::class,
                WameObserver::class,
            ]);
        }
    }

    /**
     * @return void
     */
    private function publishConfigs(): void
    {
        $this->publishes([
            __DIR__.'/../config/wame-commands.php' => config_path('wame-commands.php'),
        ], 'config');
    }
}
