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

            // Export commands
            $this->publishCommands();
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

    /**
     * @return void
     */
    private function publishCommands(): void
    {
        $this->publishes([
            __DIR__.'/../app/' => app_path(),
        ], 'commands');
    }
}
