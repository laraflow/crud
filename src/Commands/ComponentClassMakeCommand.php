<?php

namespace Laraflow\Crud\Commands;

use Illuminate\Support\Str;
use Laraflow\Crud\Abstracts\GeneratorCommand;
use Laraflow\Crud\Support\Config\GenerateConfigReader;
use Laraflow\Crud\Support\Stub;
use Laraflow\Crud\Traits\ModuleCommandTrait;
use Symfony\Component\Console\Input\InputArgument;

class ComponentClassMakeCommand extends GeneratorCommand
{
    use ModuleCommandTrait;

    /**
     * The stub file type
     *
     * @var string
     */
    protected $type = 'component-class';

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
    protected $name = 'package:make-component';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new component-class for the specified package.';

    public function handle(): int
    {
        if (parent::handle() === E_ERROR) {
            return E_ERROR;
        }
        $this->writeComponentViewTemplate();

        return 0;
    }

    /**
     * Write the view template for the component.
     *
     * @return void
     */
    protected function writeComponentViewTemplate()
    {
        $this->call('module:make-component-view', ['name' => $this->argument('name'), 'module' => $this->argument('module')]);
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the component.'],
            ['module', InputArgument::OPTIONAL, 'The name of module will be used.'],
        ];
    }

    /**
     * @return mixed
     */
    protected function getTemplateContents()
    {
        $module = $this->laravel['modules']->findOrFail($this->getModuleName());

        return (new Stub('/component-class.stub', [
            'NAMESPACE' => $this->getClassNamespace($module),
            'CLASS' => $this->getClass(),
            'LOWER_NAME' => $module->getLowerName(),
            'COMPONENT_NAME' => 'components.'.Str::lower($this->argument('name')),
        ]))->render();
    }

    /**
     * @return mixed
     */
    protected function getDestinationFilePath()
    {
        $path = $this->getModulePath($this->getModuleName());
        $factoryPath = GenerateConfigReader::read('component-class');

        return $path.$factoryPath->getPath().'/'.$this->getFileName();
    }

    /**
     * @return string
     */
    private function getFileName()
    {
        return Str::studly($this->argument('name')).'.php';
    }
}
