<?php

namespace Laraflow\ApiCrud\Commands;

use Laraflow\ApiCrud\Exceptions\GeneratorException;
use Laraflow\ApiCrud\Support\Config\GenerateConfigReader;
use Laraflow\ApiCrud\Support\Config\GeneratorPath;
use Laraflow\ApiCrud\Traits\ModuleCommandTrait;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Symfony\Component\Console\Input\InputArgument;
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

            $this->createRequests();

            $this->createResources();

            $this->createController();

            $this->updateRouteFile();

            $this->createModelFiles();

            return self::SUCCESS;

        } catch (Throwable $exception) {
            $this->error($exception);
        }
        return self::FAILURE;
    }

    private function createRequests()
    {
        foreach (['Index', 'Store', 'Update', 'Import'] as $prefix) {

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

            Artisan::call('laraflow:make-request', $options);
        }
    }

    private function getResourceName()
    {
        return Str::studly($this->argument('name'));
    }

    private function createResources()
    {
        Artisan::call('laraflow:make-resource', [
            'name' => $this->getResourceName() . 'Resource',
            'module' => $this->getModuleName(),
        ]);

        Artisan::call('laraflow:make-resource', [
            'name' => $this->getResourceName() . 'Collection',
            'module' => $this->getModuleName(),
            '--collection',
        ]);
    }

    private function createController()
    {
        Artisan::call('laraflow:make-controller', [
            'controller' => $this->getResourceName(),
            'module' => $this->getModuleName(),
            '--crud' => true,
        ]);
    }

    private function createModelFiles()
    {

        Artisan::call('laraflow:make-model', [
            'model' => $this->getResourceName(),
            'module' => $this->getModuleName(),
        ]);

        Artisan::call('laraflow:make-seed', [
            'name' => $this->getResourceName(),
            'module' => $this->getModuleName(),
        ]);

        Artisan::call('laraflow:make-migration', [
            'name' => $this->getResourceName(),
            'module' => $this->getModuleName(),
        ]);

    }

    /**
     * @throws GeneratorException
     */
    private function updateRouteFile()
    {
        $filePath = $this->getModulePath('RestApi')
            . GenerateConfigReader::read('routes')->getPath()
            . '/' . Str::lower($this->getModuleName()) . '.php';

        if (!file_exists($filePath)) {
            throw new InvalidArgumentException("Route file location doesn't exist");
        }

        $fileContent = file_get_contents($filePath);

        $singleName = Str::kebab(basename($this->getResourceName()));

        $resourceName = Str::plural($singleName);

        $controller = GeneratorPath::convertPathToNamespace(
            $this->getModuleNS('RestApi')
            . GenerateConfigReader::read('controller')->getNamespace()
            . '\\' . $this->getModuleName()
            . '\\' . $this->getResourceName()
            . 'Controller::class'
        );

        $pathParam = '{' . Str::snake(basename($this->getResourceName())) . '}';
        $template = <<<HTML
Route::apiResource('$resourceName', $controller);
    Route::post('$resourceName/$pathParam/restore', [$controller, 'restore'])->name('$resourceName.restore');

    //DO NOT REMOVE THIS LINE//
HTML;

        $fileContent = str_replace('//DO NOT REMOVE THIS LINE//', $template, $fileContent);

        file_put_contents($filePath, $fileContent);
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the resource.'],
            ['module', InputArgument::OPTIONAL, 'The name of module will be used.'],
        ];
    }

}
