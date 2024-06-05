<?php

namespace Laraflow\ApiCrud\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Laraflow\ApiCrud\Exceptions\GeneratorException;
use Laraflow\ApiCrud\Support\Config\GenerateConfigReader;
use Laraflow\ApiCrud\Support\Config\GeneratorPath;
use Laraflow\ApiCrud\Traits\ModuleCommandTrait;
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
    protected $signature = 'laraflow:make-crud
                            {name : The name of resource will create}
                            {module? : The root namespace where to create (Experimental.)}
                            {--fields=* : The fields will be on the resource (Experimental.)}';

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

            $this->createRequests();

            $this->createResources();

            $this->createModelFiles();

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

    private function createRequests()
    {
        if (!config('api-crud.templates.request.generate', true)) {
            return;
        }
        foreach (['Index', 'Store', 'Update'] as $prefix) {

            $resourcePath = $this->getResourceName() . 'Request';

            $dir = dirname($resourcePath);

            $dir = ($dir == '.') ? '' : $dir . '/';

            $resource = basename($resourcePath);

            $options = [
                'name' => $dir . $prefix . $resource,
                'module' => $this->getModuleName(),
            ];

            if ($prefix == 'Index') {
                $options['--index'] = true;
            }

            if (in_array($prefix, ['Store', 'Update'])) {
                $options['--crud'] = true;
            }

            $this->call('laraflow:make-request', $options);
        }
    }

    private function getResourceName()
    {
        return Str::studly($this->argument('name'));
    }

    private function createResources()
    {
        if (!config('api-crud.templates.resource.generate', true)) {
            return;
        }
        $this->call('laraflow:make-resource', [
            'name' => $this->getResourceName() . 'Resource',
            'module' => $this->getModuleName(),
        ]);

        $this->call('laraflow:make-resource', [
            'name' => $this->getResourceName() . 'Collection',
            'module' => $this->getModuleName(),
            '--collection' => true,
        ]);
    }

    private function createController()
    {
        if (!config('api-crud.templates.controller.generate', true)) {
            return;
        }

        $this->call('laraflow:make-controller', [
            'name' => $this->getResourceName() . 'Controller',
            '--model' => $this->getResourceName(),
            'module' => $this->getModuleName(),
            '--crud' => true,
        ]);
    }

    /**
     * @throws GeneratorException
     */
    private function createModelFiles()
    {

        if (!config('api-crud.templates.model.generate', true)) {
            return;
        }
        $this->call('laraflow:make-model', [
            'name' => $this->getResourceName(),
            'module' => $this->getModuleName(),
            //            '--migration' => config('api-crud.templates.migration.generate', true)
        ]);
    }

    /**
     * @throws GeneratorException
     */
    private function updateRouteFile()
    {
        $filePath = base_path(config('api-crud.route_path', 'routes/api.php'));

        if (!file_exists($filePath)) {
            throw new InvalidArgumentException("Route file location doesn't exist");
        }

        $fileContent = file_get_contents($filePath);

        if (!str_contains($fileContent, '//DO NOT REMOVE THIS LINE//')) {
            throw new GeneratorException("Route file missing the CRUD Pointer comment.");
        }

        $singleName = Str::kebab(basename($this->getResourceName()));

        $resourceName = Str::plural($singleName);

        $controller =
            GeneratorPath::convertPathToNamespace(
                '\\'
                . $this->getModuleName()
                . '\\'
                . GenerateConfigReader::read('controller')->getNamespace()
                . '\\'
                . $this->getResourceName()
                . 'Controller::class'
            );

        $template = <<<HTML
Route::apiResource('$resourceName', $controller);

    //DO NOT REMOVE THIS LINE//
HTML;

        $fileContent = str_replace('//DO NOT REMOVE THIS LINE//', $template, $fileContent);

        file_put_contents($filePath, $fileContent);
    }
}
