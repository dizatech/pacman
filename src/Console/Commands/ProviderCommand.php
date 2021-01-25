<?php

namespace Dizatech\Pacman\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Exception\InvalidArgumentException;

class ProviderCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pacman:provider {name} {module_name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new service provider for specific module';

    private $provider;

    private $providerClass;

    private $module;

    protected $type = 'Service Provider';

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
        $this->provider = ucwords(strtolower($name));
        $this->module = ucwords(strtolower($this->argument('module_name')));

        $this->providerClass = $name . 'ServiceProvider';

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
        return str_replace('{{ class }}',$this->provider, $stub);
    }

    protected function namespaceReplace($stub)
    {
        return str_replace('{{ moduleNamespace }}',$this->defaultNamespace(), $stub);
    }

    protected function moduleNameReplace($stub)
    {
        return str_replace('{{ module }}',strtolower($this->provider), $stub);
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

        return "modules/" . $this->module . '/src/' .str_replace('\\', '/', $name).'.php';
    }

    protected function defaultNamespace(): string
    {
        return 'Modules\\' . $this->module;
    }
}
