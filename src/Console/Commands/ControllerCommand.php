<?php

namespace Dizatech\Pacman\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Illuminate\Support\Facades\File;
use Symfony\Component\Console\Input\InputOption;

class ControllerCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pacman:controller {name} {module_name} {--directory=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new controller for specific module';

    private $controllerClass;

    private $module;

    protected $type = 'Controller';

    protected $directory;

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the controller.'],
            ['module_name', InputArgument::REQUIRED, 'The name of the module.'],
        ];
    }

    protected function getOptions()
    {
        return [
            ['directory', InputOption::VALUE_OPTIONAL, 'The name of the directory, default is modules'],
        ];
    }

    /**
     * Execute the console command.
     *
     * @return int
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function handle()
    {
        $this->setControllerClass();
        $path = $this->getPath($this->controllerClass);
        if(File::exists($this->directory . '/' . $this->module)) {
            if ($this->alreadyExists($this->controllerClass)) {
                $this->error($this->type.' already exists!');
            }else{
                $this->makeDirectory($path);
                $this->files->put($path, $this->buildClass($this->controllerClass));
                $this->info($this->type.' created successfully.');
                $this->line("<info>Created Controller :</info> $this->controllerClass");
            }
        }else{
            $this->error('Module does not exist.create the module using pacman:module command.');
        }
    }

    private function setControllerClass()
    {
        $this->controllerClass = ucwords($this->argument('name'));
        $this->module = ucwords($this->argument('module_name'));
        $this->directory = ucwords($this->option('directory'));
        if ($this->directory == null){
            $this->directory = 'Modules';
        }
        return $this;
    }

    /**
     * Replace the class name for the given stub.
     *
     * @param  string  $stub
     * @param  string  $name
     * @return string
     */
    protected function replaceClass($stub, $name)
    {
        if(!$this->argument('name')){
            throw new InvalidArgumentException("Missing required argument controller name");
        }
        $stub = parent::replaceClass($stub, $name);
        // Replace stub namespace
        $stub = $this->namespaceReplace($stub);
        return str_replace('{{ class }}',$this->controllerClass, $stub);
    }

    protected function namespaceReplace($stub)
    {
        return str_replace('{{ moduleNamespace }}',$this->defaultNamespace(), $stub);
    }

    /**
     *
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return  __DIR__. '/stubs/controller.stub';
    }

    protected function getPath($name)
    {
        $name = Str::replaceFirst($this->rootNamespace(), '', $name);

        return strtolower($this->directory) . "/" . $this->module . '/src/Http/Controllers/' .str_replace('\\', '/', $name).'.php';
    }

    protected function defaultNamespace(): string
    {
        return $this->directory . '\\' . $this->module . '\Http\Controllers';
    }
}
