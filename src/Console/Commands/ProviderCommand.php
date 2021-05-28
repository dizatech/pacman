<?php

namespace Dizatech\Pacman\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Input\InputOption;

class ProviderCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pacman:provider {name} {module_name} {--directory=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new service provider for specific module';

    private $providerClass;

    private $module;

    protected $type = 'Service Provider';

    protected $directory = 'modules';

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the service provider.'],
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
        $this->setProviderClass();
        $path = $this->getPath($this->providerClass);
        if ($this->alreadyExists($this->providerClass)) {
            $this->error($this->type.' already exists!');
        }else{
            $this->makeDirectory($path);
            $this->files->put($path, $this->buildClass($this->providerClass));
            $this->info($this->type.' created successfully.');
            $this->line("<info>Created Provider :</info> $this->providerClass");
        }
    }

    private function setProviderClass()
    {
        $name = $this->argument('name');
        $this->providerClass = ucwords($name);
        $this->directory = $this->option('directory');
        if ($this->directory == null){
            $this->directory = 'modules';
        }
        $this->module = $this->argument('module_name');

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
            throw new InvalidArgumentException("Missing required argument service provider name");
        }
        $stub = parent::replaceClass($stub, $name);
        // Replace stub namespace
        $stub = $this->namespaceReplace($stub);
        // Replace module name
        $stub = $this->moduleNameReplace($stub);
        return str_replace('{{ class }}',$this->providerClass, $stub);
    }

    protected function namespaceReplace($stub)
    {
        return str_replace('{{ moduleNamespace }}',$this->defaultNamespace(), $stub);
    }

    protected function moduleNameReplace($stub)
    {
        $moduleNamespace = str_replace('ServiceProvider', '', $this->providerClass);
        return str_replace('{{ module }}',lcfirst($moduleNamespace), $stub);
    }

    /**
     *
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return  __DIR__. '/stubs/provider.stub';
    }

    protected function getPath($name)
    {
        $name = Str::replaceFirst($this->rootNamespace(), '', $name);

        return strtolower($this->directory) . "/" . $this->module . '/src/' .str_replace('\\', '/', $name).'.php';
    }

    protected function defaultNamespace(): string
    {
        return ucwords($this->directory) . '\\' . ucwords($this->module);
    }
}
