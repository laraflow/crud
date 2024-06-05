<?php

namespace Laraflow\ApiCrud\Commands;

use Illuminate\Support\Str;
use InvalidArgumentException;
use Laraflow\ApiCrud\Abstracts\GeneratorCommand;
use Laraflow\ApiCrud\Support\Config\GenerateConfigReader;
use Laraflow\ApiCrud\Support\Migrations\NameParser;
use Laraflow\ApiCrud\Support\Migrations\SchemaParser;
use Laraflow\ApiCrud\Support\Stub;
use Laraflow\ApiCrud\Traits\ModuleCommandTrait;
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
    protected $description = 'Create a new migration for the specified package.';

    /**
     * Run the command.
     */
    public function handle(): int
    {
        if (parent::handle() === E_ERROR) {
            return E_ERROR;
        }

        if (app()->environment() === 'testing') {
            return 0;
        }

        return 0;
    }

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
            'class' => $this->getClass(),
            'table' => $this->argument('name'),
            'fields' => $this->getSchemaParser()->render(),
        ]);
    }

    public function getClass(): string
    {
        return $this->getClassName();
    }

    /**
     * @return string
     */
    private function getClassName()
    {
        return Str::studly($this->argument('name'));
    }

    /**
     * Get schema parser.
     *
     * @return SchemaParser
     */
    public function getSchemaParser()
    {
        return new SchemaParser($this->option('fields'));
    }

    /**
     * @return array|string
     */
    private function getSchemaName()
    {
        return $this->argument('name');
    }

    protected function getDestinationFilePath(): string
    {
        $config = GenerateConfigReader::read($this->type);

        return $config->getPath().'/' .$this->getFileName();
    }

    protected function getFileName()
    {
        return date('Y_m_d_His_\c\r\e\a\t\e_') . Str::snake($this->argument('name')) . "_table.php";
    }
}
