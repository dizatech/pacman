<?php

namespace Dizatech\Pacman\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Symfony\Component\Console\Input\InputArgument;

class ModuleCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pacman:module {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new module structure and service provider';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the module.']
        ];
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $name = $this->argument('name');
        if(!File::exists('modules/' . $name)) {
            // path does not exist, create module structure
            $directories = array(
                $name,
                $name . '/src/',
                $name . '/src/Observers',
                $name . '/src/assets',
                $name . '/src/assets/js',
                $name . '/src/assets/sass',
                $name . '/src/config',
                $name . '/src/database',
                $name . '/src/database/migrations',
                $name . '/src/database/seeders',
                $name . '/src/Facades',
                $name . '/src/Http',
                $name . '/src/Http/Controllers',
                $name . '/src/Http/Requests',
                $name . '/src/Models',
                $name . '/src/Repositories',
                $name . '/src/routes',
                $name . '/src/views',
                $name . '/src/views/components'
            );
            foreach ($directories as $directory){
                $this->makeModuleDir($directory);
            }
            $this->call('pacman:provider', ['name' => $name, 'module_name' => $name]);
            $this->info('Module has been created successfully!');
        }else{
            $this->error('Module already exist!');
        }
    }

    public function makeModuleDir($directory)
    {
        File::makeDirectory('modules/' . $directory,0777,true);
    }
}
