<?php

namespace Dizatech\Pacman;

use Dizatech\Pacman\Console\Commands\ControllerCommand;
use Dizatech\Pacman\Console\Commands\MigrationCreateCommand;
use Dizatech\Pacman\Console\Commands\ModelCommand;
use Dizatech\Pacman\Console\Commands\ModuleCommand;
use Dizatech\Pacman\Console\Commands\ProviderCommand;
use Dizatech\Pacman\Console\Commands\RequestCommand;
use Illuminate\Support\ServiceProvider;

class PacmanServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                ModuleCommand::class,
                ProviderCommand::class,
                ControllerCommand::class,
                MigrationCreateCommand::class,
                ModelCommand::class,
                RequestCommand::class
            ]);
        }

        $this->publishes([
            __DIR__ . '/config/module-commands.php' => config_path('module-commands.php'),
        ], 'module-commands');
    }
}
