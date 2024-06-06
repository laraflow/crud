<?php

namespace Laraflow\ApiCrud\Commands;

use Laraflow\ApiCrud\Abstracts\GeneratorCommand;
use Laraflow\ApiCrud\Exceptions\GeneratorException;
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
    protected $name = 'laraflow:make-request';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new form request class for the specified resource.';

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments(): array
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
    protected function getOptions(): array
    {
        return [
            ['index', null, InputOption::VALUE_NONE, 'The request should have index default validation.', null],
            ['crud', null, InputOption::VALUE_NONE, 'The request will have resource store and update fields as validation.', null],
            ['fields', null, InputOption::VALUE_OPTIONAL, 'The request will have given field as validation rules.', null],
        ];
    }

    /**
     * return rendered stubs file contents
     *
     * @return mixed
     *
     * @throws GeneratorException
     */
    protected function getTemplateContents(): string
    {
        return (new Stub('/request.stub', [
            'NAMESPACE' => $this->getClassNamespace(),
            'CLASS' => $this->getClass(),
            'RULES' => $this->getRules()
        ]))->render();
    }

    /**
     * return the default rules needed in request class
     *
     * @return string
     */
    protected function getRules(): string
    {
        $rules = [];

        if ($this->option('index')) {

            $rules['search'] = ['string', 'nullable', 'max:255'];
            $rules['per_page'] = ['integer', 'nullable'];
            $rules['page'] = ['integer', 'nullable', 'min:1'];
            $rules['sort'] = ['string', 'nullable', 'min:2', 'max:255'];
            $rules['dir'] = ['string', 'nullable', 'min:3', 'max:4'];
            $rules['id'] = ['integer', 'nullable', 'min:1'];
        }

        if ($this->option('crud')) {

            $fields = $this->option('fields');

            if (!is_null($fields)) {
                foreach (explode(',', trim($fields)) as $field) {
                    $rules[$field] = ['string', 'nullable', 'max:255'];
                }
            }
        }

        return var_export($rules, true);

    }
}
