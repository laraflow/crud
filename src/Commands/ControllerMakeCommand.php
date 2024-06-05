<?php

namespace Laraflow\ApiCrud\Commands;

use Illuminate\Support\Str;
use Laraflow\ApiCrud\Abstracts\GeneratorCommand;
use Laraflow\ApiCrud\Exceptions\GeneratorException;
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
    protected $argumentName = 'name';

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
     * @throws GeneratorException
     */
    protected function getTemplateContents(): string
    {
        $replacements = [
            'CLASS_NAMESPACE' => $this->getClassNamespace(),
            'CLASS' => $this->getClass(),
            'MODULE' => $this->getModuleName(),
            'LOWER_NAME' => Str::lower($this->getModuleName()),
            'MODULE_NAMESPACE' => config('api-crud.namespace'),
            //CRUD Options
            'RESOURCE' => $this->getResourceName(),
            'RESOURCE_VARIABLE' => $this->getResourceVariableName(),
            'CONFIG_VARIABLE' => Str::snake($this->getResourceVariableName()),
            'MESSAGE_VARIABLE' => Str::title(Str::replace('-', ' ', Str::kebab($this->getResourceVariableName()))),
            'RESOURCE_NAMESPACES' => '',
            'REQUEST_NAMESPACES' => '',
            'MODEL' => str_replace('/', '\\', $this->getDefaultNamespace('model') . '\\' . $this->option('model')),
            'STORE_REQUEST' => '',
            'UPDATE_REQUEST' => '',
            'INDEX_REQUEST' => '',
        ];

        $this->setRequestNamespaces($replacements);

        $this->setResourceNamespaces($replacements);

        return (new Stub('/controller-crud.stub', $replacements))->render();
    }

    /**
     * @return array|string
     */
    public function getClass(): string
    {
        return class_basename($this->getControllerName());
    }

    /**
     * @return array|string
     */
    protected function getControllerName()
    {
        $controller = Str::studly($this->argument('name'));

        if (Str::contains(strtolower($controller), 'controller') === false) {
            $controller .= 'Controller';
        }

        return $controller;
    }

    /**
     * @return string
     */
    protected function getResourceName()
    {
        return Str::studly(basename($this->option('model')));
    }

    /**
     * @return string
     */
    protected function getResourceVariableName()
    {
        return Str::camel(basename($this->getResourceName()));
    }

    private function setRequestNamespaces(array &$replacements)
    {
        $namespaces = [];

        foreach (['Store', 'Update', 'Index'] as $prefix) {
            $path = $this->getModuleName() . '/'
                . $this->getDefaultNamespace('request') . '/'
                . dirname($this->option('model')) . '/' . $prefix . class_basename($this->option('model')) . 'Request';

            $path = str_replace('/./', '/', $path);
            match ($prefix) {
                'Store' => $replacements['STORE_REQUEST'] = basename($path),
                'Index' => $replacements['INDEX_REQUEST'] = basename($path),
                'Update' => $replacements['UPDATE_REQUEST'] = basename($path),
            };

            $namespaces[] = ('use ' . implode('\\', explode('/', $path)) . ';');

        }

        $replacements['REQUEST_NAMESPACES'] = implode("\n", $namespaces);
    }

    /**
     * @throws GeneratorException
     */
    private function setResourceNamespaces(array &$replacements)
    {
        $namespaces = [];

        foreach (['Resource', 'Collection'] as $suffix) {
            $path = $this->getModuleName() . '/'
                . $this->getDefaultNamespace('resource') . '/'
                . $this->option('model') . $suffix;

            $namespaces[] = ('use ' . implode('\\', explode('/', $path)) . ';');

        }

        $replacements['RESOURCE_NAMESPACES'] = implode("\n", $namespaces);
    }

    protected function getClassPath(string $prefix = '', string $suffix = 'Request')
    {
        $resourcePath = $this->argument('name') . $suffix;

        $dir = dirname($resourcePath);

        $dir = ($dir == '.') ? '' : $dir . '/';

        $resource = basename($resourcePath);

        return $dir . $prefix . $resource;
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the controller class. Exclude `Controller` suffix.'],
            ['module', InputArgument::OPTIONAL, 'The name of module will be used.'],
        ];
    }

    /**
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['crud', null, InputOption::VALUE_NONE, 'Exclude the create and edit methods and add crud code to controller.'],
            ['model', null, InputOption::VALUE_OPTIONAL, 'The model/resources this controller will manage.'],
        ];
    }
}
