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

class RepositoryMakeCommand extends GeneratorCommand
{
    use ModuleCommandTrait;

    /**
     * The stub file type
     *
     * @var string
     */
    protected $type = 'repository';

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
    protected $name = 'package:make-repository';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate new repository for the specified package.';

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
            ['crud', null, InputOption::VALUE_NONE, 'The terminal command that should be assigned.'],
            ['repository', null, InputOption::VALUE_REQUIRED, 'The terminal command that should be assigned.'],
            ['model', null, InputOption::VALUE_REQUIRED, 'The terminal command that should be assigned.'],
        ];
    }

    /**
     * @return mixed
     *
     * @throws GeneratorException
     */
    protected function getTemplateContents()
    {
        $replacements = [
            'CLASS_NAMESPACE' => $this->getClassNamespace($this->getModuleName()),
            'CLASS' => $this->getClass(),
            'JSON_FIELD' => Str::snake(Str::replace('Repository', '', $this->getClass()).'_data'),
            'LOWER_MODULE' => Str::lower($this->getModuleName()),
            'MODULE' => $this->getModuleName(),
            'NAMESPACE' => config('fintech.generators.namespace'),
            'EXCEPTION_NAMESPACE' => $this->setExceptionNS(),
            'EXCEPTION' => $this->getClass().'Exception',
            'BASE_REPO_NS' => $this->getBaseRepoNamespace(),
            'BASE_REPO' => class_basename($this->getBaseRepoNamespace()),
            'BASE_MODEL' => $this->getBaseModel(),
            'REPO_TYPE' => $this->getRepoType(),
            'MODEL' => $this->option('model'),
            'CONFIG_MODEL' => Str::snake(class_basename($this->option('model'))),
        ];

        return (new Stub($this->getStub(), $replacements))->render();
    }

    private function getBaseRepoNamespace()
    {
        if (strpos($this->getClassNamespace($this->getModuleName()), 'Eloquent')) {
            return 'Fintech\Core\Repositories\EloquentRepository';
        }

        return 'Fintech\Core\Repositories\MongodbRepository';
    }

    private function getBaseModel()
    {
        if (strpos($this->getClassNamespace($this->getModuleName()), 'Eloquent')) {
            return 'Illuminate\Database\Eloquent\Model';
        }

        return 'MongoDB\Laravel\Eloquent\Model';
    }

    private function getRepoType()
    {
        if (strpos($this->getClassNamespace($this->getModuleName()), 'Eloquent')) {
            return 'Eloquent';
        }

        return 'Mongodb';
    }

    private function setExceptionNS()
    {

        $ns = 'use '.config('fintech.generators.namespace')
            .'/'.$this->getModuleName()
            .'/'.'Exceptions'
            .'/'.$this->argument($this->argumentName)
            .';';

        return implode('\\', explode('/', $ns));

    }

    protected function getStub()
    {
        return ($this->option('crud'))
            ? '/repository-crud.stub'
            : '/repository.stub';
    }

    /**
     * @return mixed
     *
     * @throws GeneratorException
     */
    protected function getDestinationFilePath()
    {
        $path = $this->getModulePath($this->getModuleName());

        $commandPath = GenerateConfigReader::read('repository');

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
