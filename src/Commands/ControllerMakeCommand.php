<?php

namespace Laraflow\ApiCrud\Commands;

use Illuminate\Support\Str;
use Laraflow\ApiCrud\Abstracts\GeneratorCommand;
use Laraflow\ApiCrud\Exceptions\GeneratorException;
use Laraflow\ApiCrud\Support\Config\GenerateConfigReader;
use Laraflow\ApiCrud\Support\Stub;
use Laraflow\ApiCrud\Traits\ModuleCommandTrait;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class ControllerMakeCommand extends GeneratorCommand
{
    use ModuleCommandTrait;

    /**
     * The stub file type
     *
     * @var string
     */
    protected $type = 'controller';

    /**
     * The name of argument being used.
     *
     * @var string
     */
    protected $argumentName = 'controller';

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'laraflow:make-controller';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate new restful controller for the specified package.';

    /**
     * Get controller name.
     *
     * @return string
     *
     * @throws GeneratorException
     */
    public function getDestinationFilePath(): string
    {
        $controllerPath = GenerateConfigReader::read($this->type);

        return $this->getModulePath('RestApi')
            .$controllerPath->getPath().'/'
            .$this->getModuleName().'/'
            .$this->getControllerName().'.php';
    }

    /**
     * @return array|string
     */
    protected function getControllerName()
    {
        $controller = Str::studly($this->argument('controller'));

        if (Str::contains(strtolower($controller), 'controller') === false) {
            $controller .= 'Controller';
        }

        return $controller;
    }

    /**
     * @return string
     *
     * @throws GeneratorException
     */
    protected function getTemplateContents(): string
    {
        $replacements = [
            'CLASS_NAMESPACE' => $this->getClassNamespace('RestApi').'\\'.$this->getModuleName(),
            'CLASS' => $this->getControllerNameWithoutNamespace(),
            'MODULE' => $this->getModuleName(),
            'LOWER_NAME' => Str::lower($this->getModuleName()),
            'MODULE_NAMESPACE' => config('fintech.generators.namespace'),
            //CRUD Options
            'RESOURCE' => $this->getResourceName(),
            'RESOURCE_VARIABLE' => $this->getResourceVariableName(),
            'CONFIG_VARIABLE' => Str::snake($this->getResourceVariableName()),
            'MESSAGE_VARIABLE' => Str::title(Str::replace('-', ' ', Str::kebab($this->getResourceVariableName()))),
            'RESOURCE_NAMESPACES' => '',
            'REQUEST_NAMESPACES' => '',
            'IMPORT_REQUEST' => basename($this->getClassPath('Import')),
            'STORE_REQUEST' => basename($this->getClassPath('Store')),
            'UPDATE_REQUEST' => basename($this->getClassPath('Update')),
            'INDEX_REQUEST' => basename($this->getClassPath('Index')),
        ];

        $this->setRequestNamespaces($replacements);

        $this->setResourceNamespaces($replacements);

        return (new Stub($this->getStubName(), $replacements))->render();
    }

    /**
     * @return array|string
     */
    private function getControllerNameWithoutNamespace()
    {
        return class_basename($this->getControllerName());
    }

    /**
     * @return string
     */
    protected function getResourceName()
    {
        return Str::studly(basename($this->argument('controller')));
    }

    /**
     * @return string
     */
    protected function getResourceVariableName()
    {
        return Str::camel(basename($this->getResourceName()));
    }

    protected function getClassPath(string $prefix = '', string $suffix = 'Request')
    {
        $resourcePath = $this->argument('controller').$suffix;

        $dir = dirname($resourcePath);

        $dir = ($dir == '.') ? '' : $dir.'/';

        $resource = basename($resourcePath);

        return $dir.$prefix.$resource;
    }

    private function setRequestNamespaces(array &$replacements)
    {
        $namespaces = [];

        foreach (['Import', 'Store', 'Update', 'Index'] as $prefix) {
            $path = $replacements['MODULE_NAMESPACE'].'/RestApi/Http/Requests/'.$replacements['MODULE'].'/'.$this->getClassPath($prefix);
            $namespaces[] = ('use '.implode('\\', explode('/', $path)).';');

        }

        $replacements['REQUEST_NAMESPACES'] = implode("\n", $namespaces);
    }

    private function setResourceNamespaces(array &$replacements)
    {
        $namespaces = [];

        foreach (['Resource', 'Collection'] as $suffix) {
            $path = $replacements['MODULE_NAMESPACE'].'/RestApi/Http/Resources/'.$replacements['MODULE'].'/'.$this->getClassPath('', $suffix);
            $namespaces[] = ('use '.implode('\\', explode('/', $path)).';');

        }

        $replacements['RESOURCE_NAMESPACES'] = implode("\n", $namespaces);
    }

    /**
     * Get the stub file name based on the options
     *
     * @return string
     */
    protected function getStubName()
    {
        if ($this->option('plain') === true) {
            $stub = '/controller-plain.stub';
        } elseif ($this->option('api') === true) {
            $stub = '/controller-api.stub';
        } elseif ($this->option('crud') === true) {
            $stub = '/controller-crud.stub';
        } else {
            $stub = '/controller.stub';
        }

        return $stub;
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['controller', InputArgument::REQUIRED, 'The name of the controller class. Exclude `Controller` suffix.'],
            ['module', InputArgument::OPTIONAL, 'The name of module will be used.'],
        ];
    }

    /**
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['plain', 'p', InputOption::VALUE_NONE, 'Generate a plain controller', null],
            ['api', null, InputOption::VALUE_NONE, 'Exclude the create and edit methods from the controller.'],
            ['crud', null, InputOption::VALUE_NONE, 'Exclude the create and edit methods and add crud code to controller.'],
        ];
    }
}
