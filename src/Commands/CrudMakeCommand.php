<?php

namespace Laraflow\Crud\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Laraflow\Crud\Exceptions\GeneratorException;
use Laraflow\Crud\Support\Config\GenerateConfigReader;
use Laraflow\Crud\Support\Config\GeneratorPath;
use Laraflow\Crud\Traits\ModuleCommandTrait;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Throwable;

/**
 * Class CrudMakeCommand
 */
class CrudMakeCommand extends Command
{
    use ModuleCommandTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'laraflow:make-crud';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new API Restful CRUD stub files';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {

            $this->createRequest();

            $this->createResource();

            $this->createModel();

            $this->createMigration();

            $this->createController();

            $this->updateRouteFile();

            $this->components->twoColumnDetail('API Crud Stubs File(s) Created.', '<fg=green;options=bold>DONE</>');

            return self::SUCCESS;

        } catch (Throwable $exception) {
            //            $this->components->twoColumnDetail($exception->getMessage(), '<fg=red;options=bold>ERROR</>');
            $this->error($exception);
        }

        return self::FAILURE;
    }

    /**
     * Create Request classes for resource store,
     * update and index operation.
     *
     * @throws GeneratorException
     */
    private function createRequest(): void
    {
        if (! config('crud.templates.request.generate', true)) {
            return;
        }
        foreach (['Index', 'Store', 'Update'] as $prefix) {

            $resourcePath = $this->getResourceName().'Request';

            $dir = dirname($resourcePath);

            $dir = ($dir == '.') ? '' : $dir.'/';

            $resource = basename($resourcePath);

            $options = [
                'name' => $dir.$prefix.$resource,
                'module' => $this->getModuleName(),
                '--fields' => $this->option('fields'),
            ];

            if ($prefix == 'Index') {
                $options['--index'] = true;
            }

            $this->call('laraflow:make-request', $options);
        }
    }

    /**
     * Get the console command options.
     */
    private function getResourceName(): string
    {
        return Str::studly($this->argument('name'));
    }

    /**
     * Create Resource classes for resource list and
     * show response.
     *
     * @throws GeneratorException
     */
    private function createResource(): void
    {
        if (! config('crud.templates.resource.generate', true)) {
            return;
        }
        $this->call('laraflow:make-resource', [
            'name' => $this->getResourceName().'Resource',
            'module' => $this->getModuleName(),
        ]);

        $this->call('laraflow:make-resource', [
            'name' => $this->getResourceName().'Collection',
            'module' => $this->getModuleName(),
            '--collection' => true,
        ]);
    }

    /**
     * Create Model class for resource.
     *
     * @throws GeneratorException
     */
    private function createModel(): void
    {

        if (! config('crud.templates.model.generate', true)) {
            return;
        }

        $this->call('laraflow:make-model', [
            'name' => $this->getResourceName(),
            'module' => $this->getModuleName(),
            '--fillable' => $this->option('fields'),
        ]);
    }

    /**
     * Create migration script for resource table.
     *
     * @throws GeneratorException
     */
    private function createMigration(): void
    {

        if (! config('crud.templates.migration.generate', true)) {
            return;
        }

        $this->call('laraflow:make-migration', [
            'name' => $this->getTableName(),
            'module' => $this->getModuleName(),
            '--fields' => $this->option('fields'),
        ]);
    }

    /**
     * Create Controller class for resource list, store,
     * show, update and destroy response.
     *
     * @throws GeneratorException
     */
    private function createController(): void
    {
        if (! config('crud.templates.controller.generate', true)) {
            return;
        }

        $this->call('laraflow:make-controller', [
            'name' => $this->getResourceName().'Controller',
            '--model' => $this->getResourceName(),
            'module' => $this->getModuleName(),
            '--crud' => true,
        ]);
    }

    /**
     * @throws GeneratorException
     */
    private function updateRouteFile(): void
    {
        $filePath = base_path(config('crud.route_path', 'routes/api.php'));

        if (! file_exists($filePath)) {
            throw new InvalidArgumentException("Route file location doesn't exist");
        }

        $fileContent = file_get_contents($filePath);

        if (! str_contains($fileContent, '//DO NOT REMOVE THIS LINE//')) {
            throw new GeneratorException('Route file missing the CRUD Pointer comment.');
        }

        $singleName = Str::kebab(basename($this->getResourceName()));

        $resourceName = Str::plural($singleName);

        $controller =
            GeneratorPath::convertPathToNamespace(
                '\\'
                .$this->getModuleName()
                .'\\'
                .GenerateConfigReader::read('controller')->getNamespace()
                .'\\'
                .$this->getResourceName()
                .'Controller::class'
            );

        $template = <<<HTML
Route::apiResource('$resourceName', $controller);

    //DO NOT REMOVE THIS LINE//
HTML;

        $fileContent = str_replace('//DO NOT REMOVE THIS LINE//', $template, $fileContent);

        file_put_contents($filePath, $fileContent);
    }

    /**
     * Get the console command arguments.
     */
    protected function getArguments(): array
    {
        return [
            ['name', InputArgument::REQUIRED, 'The resource name will be created.'],
            ['module', InputArgument::OPTIONAL, 'The name of module where will be created. (Experimental.)'],
        ];
    }

    /**
     * Get the console command options.
     */
    protected function getOptions(): array
    {
        return [
            ['fields', null, InputOption::VALUE_OPTIONAL, 'The specified table fields (Experimental).', null],
        ];
    }
}
