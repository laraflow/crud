<?php

namespace Laraflow\Crud\Commands;

use Illuminate\Support\Str;
use Laraflow\Crud\Abstracts\GeneratorCommand;
use Laraflow\Crud\Exceptions\GeneratorException;
use Laraflow\Crud\Support\Config\GenerateConfigReader;
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
    protected $name = 'package:make-resource';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new resource class for the specified package.';

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the resource class.'],
            ['module', InputArgument::OPTIONAL, 'The name of module will be used.'],
        ];
    }

    protected function getOptions()
    {
        return [
            ['collection', 'c', InputOption::VALUE_NONE, 'Create a resource collection.'],
        ];
    }

    /**
     * @return mixed
     *
     * @throws GeneratorException
     */
    protected function getTemplateContents()
    {
        return (new Stub($this->getStubName(), [
            'NAMESPACE' => $this->getClassNamespace($this->getModuleName()),
            'CLASS' => $this->getClass(),
        ]))->render();
    }

    /**
     * @return mixed
     *
     * @throws GeneratorException
     */
    protected function getDestinationFilePath()
    {
        $path = $this->getModulePath($this->getModuleName());

        $resourcePath = GenerateConfigReader::read('resource');

        return $path.$resourcePath->getPath().'/'.$this->getFileName().'.php';
    }

    /**
     * @return string
     */
    private function getFileName()
    {
        return Str::studly($this->argument('name'));
    }

    /**
     * Determine if the command is generating a resource collection.
     */
    protected function collection(): bool
    {
        return $this->option('collection') ||
            Str::endsWith($this->argument('name'), 'Collection');
    }

    protected function getStubName(): string
    {
        if ($this->collection()) {
            return '/resource-collection.stub';
        }

        return '/resource.stub';
    }
}
