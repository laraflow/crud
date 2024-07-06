<?php

namespace Laraflow\Crud\Commands;

use Illuminate\Support\Str;
use Laraflow\Crud\Abstracts\GeneratorCommand;
use Laraflow\Crud\Exceptions\GeneratorException;
use Laraflow\Crud\Support\Stub;
use Laraflow\Crud\Traits\ModuleCommandTrait;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class ResourceMakeCommand extends GeneratorCommand
{
    use ModuleCommandTrait;

    /**
     * The stub file type
     *
     * @var string
     */
    protected $type = 'resource';

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
    protected $name = 'laraflow:make-resource';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new resource class for the specified resource.';

    /**
     * Get the console command arguments.
     */
    protected function getArguments(): array
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the resource class.'],
            ['module', InputArgument::OPTIONAL, 'The name of module will be used.'],
        ];
    }

    protected function getOptions(): array
    {
        return [
            ['collection', 'c', InputOption::VALUE_NONE, 'The resource class will be a collection.'],
        ];
    }

    /**
     * @return mixed
     *
     * @throws GeneratorException
     */
    protected function getTemplateContents(): string
    {

        return (new Stub($this->getStubName(), [
            'NAMESPACE' => $this->getClassNamespace(),
            'CLASS' => $this->getClass(),
        ]))->render();
    }

    protected function getStubName(): string
    {
        return ($this->isCollection())
            ? '/resource-collection.stub'
            : '/resource.stub';
    }

    /**
     * Determine if the command is generating a resource collection.
     */
    protected function isCollection(): bool
    {
        return $this->option('collection') ||
            Str::endsWith($this->argument('name'), 'Collection');
    }

    protected function getFileName(): string
    {
        return Str::studly($this->argument('name')) . '.php';
    }
}
