<?php

namespace Laraflow\Crud\Commands;

use Laraflow\Crud\Abstracts\GeneratorCommand;
use Laraflow\Crud\Exceptions\GeneratorException;
use Laraflow\Crud\Support\Stub;
use Laraflow\Crud\Traits\ModuleCommandTrait;
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
     */
    protected function getOptions(): array
    {
        return [
            ['index', null, InputOption::VALUE_NONE, 'The request should have index default validation.', null],
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
            'RULES' => $this->getRules(),
        ]))->render();
    }

    /**
     * return the default rules needed in request class
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

        $fields = $this->option('fields');

        if (!is_null($fields)) {
            foreach (explode(',', trim($fields)) as $field) {
                $rules[$field] = ['string', 'nullable', 'max:255'];
            }
        }

        $rulesString = json_encode($rules);

        //wrap the outer layer

        $rulesString = preg_replace('/^\{(.*)}$/i', "[$1\n]", $rulesString);

        //add newline on each field
        return preg_replace('/("[a-z0-9_.]+"):/i', "\n $1 => ", $rulesString);

    }
}
