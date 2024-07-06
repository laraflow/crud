<?php

namespace Laraflow\Crud\Commands;

use Illuminate\Support\Str;
use Laraflow\Crud\Abstracts\GeneratorCommand;
use Laraflow\Crud\Exceptions\GeneratorException;
use Laraflow\Crud\Support\Stub;
use Laraflow\Crud\Traits\ModuleCommandTrait;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class ModelMakeCommand extends GeneratorCommand
{
    use ModuleCommandTrait;

    /**
     * The stub file type
     *
     * @var string
     */
    protected $type = 'model';

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
    protected $name = 'laraflow:make-model';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new model for the specified resource.';

    /**
     * Get the console command arguments.
     */
    protected function getArguments(): array
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of model will be created.'],
            ['module', InputArgument::OPTIONAL, 'The name of module will be used.'],
        ];
    }

    /**
     * Get the console command options.
     */
    protected function getOptions(): array
    {
        return [
            ['fillable', null, InputOption::VALUE_OPTIONAL, 'The fillable attributes.', null],
            ['migration', 'm', InputOption::VALUE_NONE, 'Flag to create associated migrations', null],
            ['controller', 'c', InputOption::VALUE_NONE, 'Flag to create associated controllers', null],
            ['seed', 's', InputOption::VALUE_NONE, 'Create a new seeder for the model', null],
            ['request', 'r', InputOption::VALUE_NONE, 'Create a new request for the model', null],
        ];
    }

    /**
     * @return mixed
     *
     * @throws GeneratorException
     */
    protected function getTemplateContents(): string
    {
        return (new Stub('/model.stub', [
            'NAME' => $this->getModelName(),
            'TABLE' => $this->getTableName(),
            'FILLABLE' => $this->getFillable(),
            'NAMESPACE' => $this->getClassNamespace(),
            'CLASS' => $this->getClass(),
        ]))->render();
    }

    /**
     * @return mixed|string
     */
    private function getModelName()
    {
        return Str::studly($this->argument('name'));
    }

    /**
     * @experimental
     *
     * @return string
     */
    private function getFillable()
    {
        $field = 'guarded = ["id"]';

        $fillable = $this->option('fillable');

        if (!is_null($fillable)) {

            $arrays = array_map(function ($column) {
                return (string)$column;
            }, explode(',', trim($fillable)));

            $field = 'fillable = ' . json_encode($arrays, JSON_PRETTY_PRINT);
        }

        return $field;
    }

    protected function getFileName(): string
    {
        return Str::studly($this->argument('name')) . '.php';
    }
}
