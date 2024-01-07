<?php

namespace Laraflow\Crud\Commands;

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Str;
use Laraflow\Crud\Abstracts\GeneratorCommand;
use Laraflow\Crud\Support\Config\GenerateConfigReader;
use Laraflow\Crud\Support\Stub;
use Laraflow\Crud\Traits\ModuleCommandTrait;
use Symfony\Component\Console\Input\InputArgument;

class ComponentViewMakeCommand extends GeneratorCommand
{
    use ModuleCommandTrait;

    /**
     * The stub file type
     *
     * @var string
     */
    protected $type = 'component-view';

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
    protected $name = 'package:make-component-view';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new component-view for the specified package.';

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
        return (new Stub('/component-view.stub', ['QUOTE' => Inspiring::quote()]))->render();
    }

    /**
     * @return mixed
     *
     * @throws \Laraflow\Crud\Exceptions\GeneratorException
     */
    protected function getDestinationFilePath()
    {
        $path = $this->getModulePath($this->getModuleName());
        $factoryPath = GenerateConfigReader::read('component-view');

        return $path.$factoryPath->getPath().'/'.$this->getFileName();
    }

    /**
     * @return string
     */
    private function getFileName()
    {
        return Str::lower($this->argument('name')).'.blade.php';
    }
}
