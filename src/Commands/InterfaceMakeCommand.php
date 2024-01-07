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

class InterfaceMakeCommand extends GeneratorCommand
{
    use ModuleCommandTrait;

    /**
     * The stub file type
     *
     * @var string
     */
    protected $type = 'interface';

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
    protected $name = 'package:make-interface';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate new Interface for the specified package.';

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the interface.'],
            ['module', InputArgument::OPTIONAL, 'The name of module will be used.'],
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
            ['crud', null, InputOption::VALUE_NONE, 'If this will be a crud repo interface.', null],
            ['repository', '-r', InputOption::VALUE_OPTIONAL, 'The repository exception that should be assigned.', null],
        ];
    }

    /**
     * @return mixed
     *
     * @throws GeneratorException
     */
    protected function getTemplateContents()
    {
        return (new Stub($this->getStub(), [
            'NAMESPACE' => $this->getClassNamespace($this->getModuleName()),
            'INTERFACE' => $this->getClass(),
            'EXCEPTION' => basename($this->getExceptionClassName()),
            'EXCEPTION_NAMESPACE' => $this->getExceptNamespace($this->getExceptionClassName()),
        ]))->render();
    }

    private function getExceptNamespace($class)
    {
        $ns = config('fintech.generators.namespace')
            .'/'.$this->getModuleName()
            .'/'.config('fintech.generators.paths.generator.exception.namespace')
            .'/'.$class;

        return implode('\\', explode('/', $ns));

    }

    private function getExceptionClassName()
    {
        if ($this->option('crud')) {

            $repository = $this->option('repository');

            if (! $repository) {
                $repository = (Str::contains($this->getClass(), 'Repository', true))
                    ? $this->getClass()
                    : $this->getClass().'Repository';
            }

            if (! Str::contains($repository, 'Exception')) {
                $repository .= 'Exception';
            }

            return $repository;
        }

        return '';
    }

    private function getStub()
    {
        return ($this->option('crud'))
            ? '/interface-crud.stub'
            : '/interface.stub';
    }

    /**
     * @return mixed
     *
     * @throws GeneratorException
     */
    protected function getDestinationFilePath()
    {
        $path = $this->getModulePath($this->getModuleName());

        $commandPath = GenerateConfigReader::read($this->type);

        return $path.$commandPath->getPath().'/'.$this->getFileName().'.php';
    }

    /**
     * @return string
     */
    private function getFileName()
    {
        return Str::studly($this->argument('name'));
    }
}
