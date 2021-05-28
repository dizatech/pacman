<?php

namespace Dizatech\Pacman;

use Dizatech\Pacman\Console\Commands\BaseFacadeCommand;
use Dizatech\Pacman\Console\Commands\ControllerCommand;
use Dizatech\Pacman\Console\Commands\FacadeCommand;
use Dizatech\Pacman\Console\Commands\MigrationCreateCommand;
use Dizatech\Pacman\Console\Commands\ModelCommand;
use Dizatech\Pacman\Console\Commands\ModuleCommand;
use Dizatech\Pacman\Console\Commands\ProviderCommand;
use Dizatech\Pacman\Console\Commands\RepositoryCommand;
use Dizatech\Pacman\Console\Commands\RequestCommand;
use Dizatech\Pacman\Console\Commands\SeederCommand;
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
                RequestCommand::class,
                FacadeCommand::class,
                BaseFacadeCommand::class,
                RepositoryCommand::class,
                SeederCommand::class
            ]);
        }

        $this->publishes([
            __DIR__ . '/config/pacman.php' => config_path('pacman.php'),
        ], 'pacman');
    }
}
