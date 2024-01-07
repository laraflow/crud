<?php

namespace Laraflow\Crud\Commands;

use Laraflow\Crud\Abstracts\GeneratorCommand;
use Laraflow\Crud\Exceptions\GeneratorException;
use Laraflow\Crud\Support\Config\GenerateConfigReader;
use Laraflow\Crud\Support\Stub;
use Laraflow\Crud\Traits\ModuleCommandTrait;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class ServiceMakeCommand extends GeneratorCommand
{
    use ModuleCommandTrait;

    /**
     * The stub file type
     *
     * @var string
     */
    protected $type = 'service';

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
    protected $name = 'package:make-service';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate new Service for the specified package.';

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the command.'],
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
            ['crud', null, InputOption::VALUE_NONE, 'if the service class will have crud code.', null],
            ['repository', null, InputOption::VALUE_REQUIRED, 'the repository interface.', null],
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
            'CLASS' => $this->getClass(),
            'REPO_VARIABLE' => Str::camel(basename($this->getRepoName())),
            'REPO' => basename($this->getRepoName()),
            'REPO_NAMESPACE' => $this->getRepoNS(),
        ]))->render();
    }

    private function getRepoNS()
    {
        $ns = config('fintech.generators.namespace')
            .'/'.$this->getModuleName()
            .'/'.'Interfaces'
            .'/'.$this->getRepoName();

        return implode('\\', explode('/', $ns));

    }

    protected function getRepoName()
    {
        $repository = $this->option('repository');

        if (!$repository) {
            $repository = Str::replace('Service', 'Repository', $this->getClass());

            if (!Str::contains($repository, 'Repository')) {
                $repository .= 'Repository';
            }
        }

        return $repository;
    }

    private function getStub()
    {
        return ($this->option('crud'))
            ? '/service-crud.stub'
            : '/service.stub';
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
