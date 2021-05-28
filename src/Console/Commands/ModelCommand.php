<?php

namespace Dizatech\Pacman\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Illuminate\Support\Facades\File;
use Symfony\Component\Console\Input\InputOption;

class ModelCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pacman:model {name} {module_name} {--directory=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Eloquent model class for specific module';

    private $model;

    private $modelClass;

    private $module;

    protected $type = 'Model';

    protected $directory;

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the model.'],
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
        $this->setModelClass();
        $path = $this->getPath($this->modelClass);
        if(File::exists($this->directory . '/' . $this->module)) {
            if ($this->alreadyExists($this->modelClass)) {
                $this->error($this->type.' already exists!');
            }else{
                $this->makeDirectory($path);
                $this->files->put($path, $this->buildClass($this->modelClass));
                $this->info($this->type.' created successfully.');
                $this->line("<info>Created Model :</info> $this->modelClass");
            }
        }else{
            $this->error('Module does not exist.create the module using pacman:module command.');
        }
    }

    private function setModelClass()
    {
        $name = $this->argument('name');
        $this->modelClass = ucwords($name);
        $this->module = $this->argument('module_name');
        $this->directory = $this->option('directory');
        if ($this->directory == null){
            $this->directory = 'modules';
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
            throw new InvalidArgumentException("Missing required argument model name");
        }
        $stub = parent::replaceClass($stub, $name);
        // Replace stub namespace
        $stub = $this->namespaceReplace($stub);
        return str_replace('{{ class }}',$this->modelClass, $stub);
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
        return  __DIR__. '/stubs/model.stub';
    }

    protected function getPath($name)
    {
        $name = Str::replaceFirst($this->rootNamespace(), '', $name);

        return strtolower($this->directory) . "/" . $this->module . '/src/Models/' .str_replace('\\', '/', $name).'.php';
    }

    protected function defaultNamespace(): string
    {
        return ucwords($this->directory) . '\\' . ucwords($this->module) . '\Models';
    }
}
