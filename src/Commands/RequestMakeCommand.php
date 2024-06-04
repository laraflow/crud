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

class RequestMakeCommand extends GeneratorCommand
{
    use ModuleCommandTrait;

    /**
     * The stub file type
     *
     * @var string
     */
    protected $type = 'request';

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
    protected $name = 'package:make-request';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new form request class for the specified package.';

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the form request class.'],
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
            ['index', null, InputOption::VALUE_NONE, 'The terminal command that should be assigned.', null],
            ['crud', null, InputOption::VALUE_NONE, 'The terminal command that should be assigned.', null],
        ];
    }

    /**
     * @return mixed
     *
     * @throws GeneratorException
     */
    protected function getTemplateContents()
    {
        return (new Stub('/request.stub', [
            'NAMESPACE' => $this->getClassNamespace('RestApi').'\\'.$this->getModuleName(),
            'CLASS' => $this->getClass(),
            'RULES' => $this->getRules(),
            'PAGINATE_TRAIT' => $this->getPaginateTrait(),
        ]))->render();
    }

    /**
     * @return string
     */
    protected function getRules()
    {
        if ($this->option('index')) {
            return <<<'HTML'
'search' => ['string', 'nullable', 'max:255'],
            'per_page' => ['integer', 'nullable', 'min:10', 'max:500'],
            'page' => ['integer', 'nullable', 'min:1'],
            'paginate' => ['boolean'],
            'sort' => ['string', 'nullable', 'min:2', 'max:255'],
            'dir' => ['string', 'min:3', 'max:4'],
            'trashed' => ['boolean', 'nullable'],
HTML;
        } elseif ($this->option('crud')) {
            return '//';
        } else {
            return '//';
        }
    }

    /**
     * @return string
     */
    protected function getPaginateTrait()
    {
        if ($this->option('index')) {
            return 'use \Fintech\RestApi\Traits\HasPaginateQuery;'.PHP_EOL;
        } else {
            return '';
        }
    }

    /**
     * @return mixed
     *
     * @throws GeneratorException
     */
    protected function getDestinationFilePath()
    {
        $requestPath = GenerateConfigReader::read('request');

        return $this->getModulePath('RestApi')
            .$requestPath->getPath().'/'
            .$this->getModuleName().'/'
            .$this->getFileName().'.php';
    }

    /**
     * @return string
     */
    private function getFileName()
    {
        return Str::studly($this->argument('name'));
    }
}
