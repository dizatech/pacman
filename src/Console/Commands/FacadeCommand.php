<?php

namespace Dizatech\Pacman\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Illuminate\Support\Facades\File;

class FacadeCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pacman:facade {name} {module_name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new facade for specific module';

    private $facadeClass;

    private $module;

    protected $type = 'Facade';

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the facade.'],
            ['module_name', InputArgument::REQUIRED, 'The name of the module.'],
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
        $this->setFacadeClass();
        $path = $this->getPath($this->facadeClass);
        if(File::exists('modules/' . $this->module)) {
            if ($this->alreadyExists($this->facadeClass)) {
                $this->error($this->type.' already exists!');
            }else{
                $this->makeDirectory($path);
                $this->files->put($path, $this->buildClass($this->facadeClass));
                $this->info($this->type.' created successfully.');
                $this->line("<info>Created Facade :</info> $this->facadeClass");
            }
        }else{
            $this->error('Module does not exist.create the module using pacman:module command.');
        }
    }

    private function setFacadeClass()
    {
        $this->facadeClass = ucwords(strtolower($this->argument('name')));
        $this->module = ucwords(strtolower($this->argument('module_name')));
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
            throw new InvalidArgumentException("Missing required argument facade name");
        }
        $stub = parent::replaceClass($stub, $name);
        // Replace stub namespace
        $stub = $this->namespaceReplace($stub);
        return str_replace('{{ class }}',$this->facadeClass, $stub);
    }

    protected function namespaceReplace($stub)
    {
        return str_replace('{{ moduleNamespace }}',$this->defaultNamespace(), $stub);
    }

    protected function repositoryNamespaceReplace($stub)
    {
        return str_replace('{{ repositoryNamespace }}',$this->repositoryDefaultNamespace(), $stub);
    }

    /**
     *
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return  __DIR__. '/stubs/facade.stub';
    }

    protected function getPath($name)
    {
        $name = Str::replaceFirst($this->rootNamespace(), '', $name);

        return "modules/" . $this->module . '/src/Facades/' .str_replace('\\', '/', $name).'.php';
    }

    protected function defaultNamespace(): string
    {
        return 'Modules\\' . $this->module . '\Facades';
    }

    protected function repositoryDefaultNamespace(): string
    {
        return 'Modules\\' . $this->module . '\Repositories';
    }
}
