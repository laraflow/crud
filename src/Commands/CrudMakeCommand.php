<?php

namespace Laraflow\ApiCrud\Commands;

use Fintech\Core\Facades\Core;
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
    protected $name = 'package:make-crud';

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

            if (Core::packageExists('RestApi')) {

                $this->createRequests();

                $this->createResources();

                $this->createController();

                $this->updateRouteFile();

            }

            $this->createStubFiles();

            $this->createRepositories();

            $this->createConfigOption();

            $this->updateModelEntryClasses();

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

            Artisan::call('package:make-request', $options);
        }
    }

    protected function getResourceName()
    {
        return Str::studly($this->argument('name'));
    }

    //Create Request

    private function createResources()
    {
        Artisan::call('package:make-resource', [
            'name' => $this->getResourceName() . 'Resource',
            'module' => $this->getModuleName(),
        ]);

        Artisan::call('package:make-resource', [
            'name' => $this->getResourceName() . 'Collection',
            'module' => $this->getModuleName(),
            '--collection',
        ]);
    }

    private function createController()
    {
        Artisan::call('package:make-controller', [
            'controller' => $this->getResourceName(),
            'module' => $this->getModuleName(),
            '--crud' => true,
        ]);
    }

    //Create Resource

    /**
     * @throws GeneratorException
     */
    private function createStubFiles()
    {

        Artisan::call('package:make-model', [
            'model' => $this->getResourceName(),
            'module' => $this->getModuleName(),
        ]);

        Artisan::call('package:make-service', [
            'name' => $this->getResourceName() . 'Service',
            'module' => $this->getModuleName(),
            '--crud' => true,
            '--repository' => $this->getResourceName() . 'Repository',
        ]);

        Artisan::call('package:make-seed', [
            'name' => $this->getResourceName(),
            'module' => $this->getModuleName(),
        ]);

    }

    //Create Controller,Model,Service and Interface etc.

    private function createRepositories()
    {
        Artisan::call('package:make-interface', [
            'name' => $this->getResourceName() . 'Repository',
            'module' => $this->getModuleName(),
            '--crud' => true,
        ]);

        Artisan::call('package:make-repository', [
            'name' => 'Eloquent/' . $this->getResourceName() . 'Repository',
            'module' => $this->getModuleName(),
            '--model' => $this->getResourceName(),
            '--crud' => true,
        ]);

        Artisan::call('package:make-repository', [
            'name' => 'Mongodb/' . $this->getResourceName() . 'Repository',
            'module' => $this->getModuleName(),
            '--model' => $this->getResourceName(),
            '--crud' => true,
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

    private function createConfigOption()
    {

        $filePath = $this->getModulePath() . GenerateConfigReader::read('config')->getPath()
            . DIRECTORY_SEPARATOR . strtolower($this->getModuleName()) . '.php';

        if (!file_exists($filePath)) {
            throw new InvalidArgumentException("`{$filePath}` is invalid config file path");
        }

        $fileContent = file_get_contents($filePath);

        $singleName = basename($this->getResourceName());

        $lowerName = Str::lower(Str::snake($singleName));

        $model = GeneratorPath::convertPathToNamespace(
            $this->getModuleNS() .
            GenerateConfigReader::read('model')->getNamespace() .
            '\\' . $singleName . '::class'
        );

        $interfacePath = GeneratorPath::convertPathToNamespace(
            $this->getModuleNS() .
            GenerateConfigReader::read('interface')->getNamespace() .
            '\\' . $singleName . 'Repository::class'
        );

        $repositoryPath = GeneratorPath::convertPathToNamespace(
            $this->getModuleNS() .
            GenerateConfigReader::read('repository')->getNamespace() .
            '\\Eloquent\\' . $this->getResourceName() . 'Repository::class'
        );

        $modelOptionTemplate = <<<HTML

    /*
    |--------------------------------------------------------------------------
    | {$singleName} Model
    |--------------------------------------------------------------------------
    |
    | This value will be used to across system where model is needed
    */
    '{$lowerName}_model' => {$model},

    //** Model Config Point Do not Remove **//
HTML;
        $repoOptionTemplate = <<<HTML
{$interfacePath} => {$repositoryPath},

        //** Repository Binding Config Point Do not Remove **//
HTML;


        $replacements = [
            '//** Model Config Point Do not Remove **//' => $modelOptionTemplate,
            '//** Repository Binding Config Point Do not Remove **//' => $repoOptionTemplate
        ];

        $fileContent = str_replace(array_keys($replacements), array_values($replacements), $fileContent);

        file_put_contents($filePath, $fileContent);
    }

    private function updateModelEntryClasses()
    {
        $filePath = $this->getModulePath() . 'src/' . $this->getModuleName() . '.php';

        if (!file_exists($filePath)) {
            throw new InvalidArgumentException("Module Entry Class(" . $this->getModuleName() . ") file doesn't exist");
        }

        $fileContent = file_get_contents($filePath);

        $methodName = Str::camel(basename($this->getResourceName()));

        $service = GeneratorPath::convertPathToNamespace(
            $this->getModuleNS() .
            GenerateConfigReader::read('service')->getNamespace()
            . '\\' . $this->getResourceName() . 'Service'
        );

        $template = <<<HTML
/**
     * @return {$service}
     */
    public function {$methodName}()
    {
        return app({$service}::class);
    }

    //** Crud Service Method Point Do not Remove **//

HTML;

        $fileContent = str_replace('//** Crud Service Method Point Do not Remove **//', $template, $fileContent);

        file_put_contents($filePath, $fileContent);

        $this->updateModelEntryFacades();
    }

    private function updateModelEntryFacades()
    {
        $filePath = $this->getModulePath() . 'src/Facades/' . $this->getModuleName() . '.php';

        if (!file_exists($filePath)) {
            throw new InvalidArgumentException("Module Entry Facades Class(" . $this->getModuleName() . ") file doesn't exist");
        }

        $fileContent = file_get_contents($filePath);

        $methodName = Str::camel(basename($this->getResourceName()));

        $service = GeneratorPath::convertPathToNamespace(
            $this->getModuleNS() .
            GenerateConfigReader::read('service')->getNamespace()
            . '\\' . $this->getResourceName() . 'Service'
        );

        $template = <<<HTML
@method static {$service} {$methodName}()
 * // Crud Service Method Point Do not Remove //
HTML;

        $fileContent = str_replace('// Crud Service Method Point Do not Remove //', $template, $fileContent);

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
