<?php

namespace Dizatech\Pacman\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Illuminate\Support\Facades\File;

class MigrationCreateCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pacman:migration {name} {module_name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new migration file for specific module';

    private $migration;

    private $migrationClass;

    private $module;

    private $fileName;

    protected $type = 'Migration';

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the migration.'],
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
        $this->setMigrationClass();
        $path = $this->getPath($this->fileName);
        if(File::exists('modules/' . $this->module)) {
            if (!empty(glob($this->migration_path() . '*_' . $this->argument('name') . '.php'))) {
                $this->error($this->type.' already exists!');
            }else{
                $this->makeDirectory($path);
                $this->files->put($path, $this->buildClass($this->migrationClass));
                $this->info($this->type.' created successfully.');
                $this->line("<info>Created Migration :</info> $this->fileName");
            }
        }else{
            $this->error('Module does not exist.create the module using pacman:module command.');
        }
    }

    private function setMigrationClass()
    {
        $name = $this->argument('name');
        $migration_name = ucwords(strtolower($name), '_');
        $migration_name = str_replace('_', '' , $migration_name);
        $this->module = ucwords(strtolower($this->argument('module_name')));
        $this->migrationClass = $migration_name;
        $this->fileName = $this->getFileName();

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
            throw new InvalidArgumentException("Missing required argument migration name");
        }
        $stub = parent::replaceClass($stub, $name);
        // Replace stub namespace
        $stub = $this->replaceTable($stub);
        return str_replace('{{ class }}',$this->migration, $stub);
    }

    protected function replaceTable($stub)
    {
        return str_replace('{{ table }}',$this->migrationTable(), $stub);
    }

    /**
     * Generate migration class name.
     *
     * @return array
     */
    protected function migrationTable(): string
    {
        $migration_class = str_replace('create', '' , $this->argument('name'));
        $migration_class = str_replace('table', '' , $migration_class);
        $migration_class = ucwords(strtolower($migration_class), '_');
        $migration_class = str_replace('_', '' , $migration_class);
        return $migration_class;
    }

    /**
     *
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return  __DIR__. '/stubs/migration.create.stub';
    }

    protected function getPath($name)
    {
        $name = Str::replaceFirst($this->rootNamespace(), '', $name);

        return "modules/" . $this->module . '/src/database/migrations/' .str_replace('\\', '/', $name).'.php';
    }

    /**
     * @return string
     */
    private function getFileName()
    {
        return date('Y_m_d_His_') . $this->getSchemaName();
    }

    /**
     * @return array|string
     */
    private function getSchemaName()
    {
        return $this->argument('name');
    }

    private function migration_path(): string
    {
        return 'modules/' . $this->module . '/src/database/migrations/';
    }

}
