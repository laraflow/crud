<?php

namespace Laraflow\Crud\Commands;

use Illuminate\Support\Str;
use InvalidArgumentException;
use Laraflow\Crud\Abstracts\GeneratorCommand;
use Laraflow\Crud\Support\Config\GenerateConfigReader;
use Laraflow\Crud\Support\Migrations\NameParser;
use Laraflow\Crud\Support\Migrations\SchemaParser;
use Laraflow\Crud\Support\Stub;
use Laraflow\Crud\Traits\ModuleCommandTrait;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class MigrationMakeCommand extends GeneratorCommand
{
    use ModuleCommandTrait;

    /**
     * The stub file type
     *
     * @var string
     */
    protected $type = 'migration';

    /**
     * The name of argument name.
     *
     * @var string
     */
    protected $argumentName = 'name';

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'laraflow:make-migration';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new migration for the specified resource.';

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The migration name will be created.'],
            ['module', InputArgument::OPTIONAL, 'The name of module will be created.'],
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['fields', null, InputOption::VALUE_OPTIONAL, 'The specified fields table.', null],
            ['plain', null, InputOption::VALUE_NONE, 'Create plain migration.'],
        ];
    }

    /**
     * @return mixed
     *
     * @throws InvalidArgumentException
     */
    protected function getTemplateContents(): string
    {
        $parser = new NameParser($this->argument('name'));

        return new Stub('/migration.stub', [
            'TABLE' => $this->argument('name'),
            'FIELDS' => trim((new SchemaParser($this->option('fields')))->render()),
        ]);
    }

    protected function getDestinationFilePath(): string
    {
        $config = GenerateConfigReader::read($this->type);

        return $config->getPath().'/'.$this->getFileName();
    }

    protected function getFileName()
    {
        return date('Y_m_d_His_\c\r\e\a\t\e_').Str::snake($this->argument('name')).'_table.php';
    }

    /**
     * @return array|string
     */
    private function getSchemaName()
    {
        return $this->argument('name');
    }
}
