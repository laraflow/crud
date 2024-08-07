<?php

namespace Laraflow\Crud\Abstracts;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Laraflow\Crud\Exceptions\FileAlreadyExistException;
use Laraflow\Crud\Exceptions\GeneratorException;
use Laraflow\Crud\Generators\FileGenerator;
use Laraflow\Crud\Support\Config\GenerateConfigReader;

abstract class GeneratorCommand extends Command
{
    /**
     * The name of 'name' argument.
     *
     * @var string
     */
    protected $argumentName = '';

    /**
     * Execute the console command.
     *
     * @throws GeneratorException
     * @throws FileAlreadyExistException
     */
    public function handle(): int
    {
        /**
         * Disable Script on Testing environment
         * also Disable through configuration
         */
        if (app()->environment('testing') || ! config('crud.enabled', false)) {
            return self::SUCCESS;
        }

        $path = str_replace('\\', '/', $this->getDestinationFilePath());

        if (! $this->laravel['files']->isDirectory($dir = dirname($path))) {
            $this->laravel['files']->makeDirectory($dir, 0777, true);
        }

        $contents = $this->getTemplateContents();

        try {
            $this->components->task("Generating file {$path}", function () use ($path, $contents) {
                $overwriteFile = $this->hasOption('force') ? $this->option('force') : false;
                (new FileGenerator($path, $contents))->withFileOverwrite($overwriteFile)->generate();
            });

            return self::SUCCESS;

        } catch (FileAlreadyExistException $e) {
            $this->components->error("File : {$path} already exists.");

            return self::FAILURE;
        }
    }

    /**
     * Get the destination file path.
     */
    /**
     * @return mixed
     *
     * @throws GeneratorException
     */
    protected function getDestinationFilePath(): string
    {
        $config = GenerateConfigReader::read($this->type);

        return config('crud.root_path', 'app').'/'
            .$config->getPath().'/'
            .$this->getFileName();
    }

    /**
     * @return string
     */
    protected function getFileName()
    {
        $type = Str::studly($this->type);

        return str_replace($type, '', Str::studly($this->argument('name')))."{$type}.php";
    }

    /**
     * Get template contents.
     */
    abstract protected function getTemplateContents(): string;

    /**
     * Get class namespace.
     *
     *
     * @throws GeneratorException
     */
    public function getClassNamespace(?string $module = null): string
    {
        $extra = str_replace($this->getClass(), '', $this->argument($this->argumentName));

        $extra = str_replace('/', '\\', $extra);

        $namespace = config('crud.namespace');

        $namespace .= '\\'.$this->getDefaultNamespace();

        $namespace .= '\\'.$extra;

        $namespace = str_replace('/', '\\', $namespace);

        return trim($namespace, '\\');
    }

    /**
     * Get class name.
     */
    public function getClass(): string
    {
        return class_basename($this->argument($this->argumentName));
    }

    /**
     * Get default namespace.
     *
     * @param  null  $type
     *
     * @throws GeneratorException
     */
    public function getDefaultNamespace($type = null): string
    {
        if (! $type) {
            if (property_exists($this, 'type')) {
                $type = $this->type;
            }
        }

        if (! $type) {
            throw new GeneratorException('Stub type argument or property is not configured.');
        }

        if (! config("crud.templates.{$type}")) {
            throw new InvalidArgumentException("Generator is missing [{$type}] config, check generators.php file.");
        }

        $config = config("crud.templates.{$type}");

        return $config['namespace'] ?? $config['path'];

    }
}
