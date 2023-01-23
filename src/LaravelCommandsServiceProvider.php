<?php

namespace Wame\LaravelCommands;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class LaravelCommandsServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/wame-commands.php', 'wame-commands');
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
            $this->publishes([__DIR__ . '/../config/wame-commands.php' => config_path('wame-commands.php')], 'config');

            // Export Base Nova Resource
            $this->publishes([__DIR__ . '/../app/Nova/BaseResource.php' => resource_path()], 'nova');

            // Registering commands
            $this->commands([
                'Wame\LaravelCommands\Console\Commands\WameApiController',
                'Wame\LaravelCommands\Console\Commands\WameEvents',
                'Wame\LaravelCommands\Console\Commands\WameListeners',
                'Wame\LaravelCommands\Console\Commands\WameMake',
                'Wame\LaravelCommands\Console\Commands\WameMigration',
                'Wame\LaravelCommands\Console\Commands\WameModel',
                'Wame\LaravelCommands\Console\Commands\WameNova',
                'Wame\LaravelCommands\Console\Commands\WameObserver',
            ]);
        }
    }

}
