<?php

namespace Laraflow\ApiCrud\Commands;

use Illuminate\Console\Command;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\File\Exception\CannotWriteFileException;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laraflow:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Configure the application files for Crud.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {

            $this->configureRouteFile();

            $this->confirmConfigPublish();

            $this->confirmLanguagePublish();

            $this->confirmStubsPublish();

            $this->components->twoColumnDetail('API Crud Setup Complete.', '<fg=green;options=bold>DONE</>');

            return self::SUCCESS;

        } catch (\Exception $exception) {

            $this->components->twoColumnDetail($exception->getMessage(), '<fg=red;options=bold>ERROR</>');

            return self::FAILURE;
        }
    }

    private function configureRouteFile(): void
    {
        $routeFilePath = base_path(config('api-crud.route_path', 'routes/api.php'));

        if (! is_file($routeFilePath)) {
            throw new InvalidArgumentException("Invalid API route file path: ({$routeFilePath}).");
        }

        if (! is_readable($routeFilePath)) {
            throw new AccessDeniedException('Unable to read from route file.');
        }

        $content = file_get_contents($routeFilePath);

        if (str_contains($content, '//DO NOT REMOVE THIS LINE//')) {
            $this->components->twoColumnDetail(
                'API route file contains the crud point string `[//DO NOT REMOVE THIS LINE//]`.',
                '<fg=yellow;options=bold>SKIPPED</>');

            return;
        }

        $content .= "\n//DO NOT REMOVE THIS LINE//\n";

        if (! is_writable($routeFilePath)) {
            throw new CannotWriteFileException('Unable to write on route file.');
        }

        $this->components->twoColumnDetail(
            'Configured the API Route file.',
            '<fg=green;options=bold>DONE</>');

        file_put_contents($routeFilePath, $content);
    }

    private function confirmConfigPublish(): void
    {
        if ($this->confirm('Publish Configuration File', false)) {

            $this->vendorPublish('api-crud-config', is_file(base_path('config/api-crud.php')));
        } else {
            $this->components->twoColumnDetail(
                'Publish Configuration File.',
                '<fg=yellow;options=bold>SKIPPED</>');
        }
    }

    private function confirmLanguagePublish(): void
    {
        if ($this->confirm('Publish Language Files', false)) {

            $this->vendorPublish('api-crud-lang', is_dir(app()->langPath('vendor/api-crud')));
        } else {
            $this->components->twoColumnDetail(
                'Publish Language Files.',
                '<fg=yellow;options=bold>SKIPPED</>');
        }
    }

    private function confirmStubsPublish(): void
    {
        if ($this->confirm('Publish Template Files (Not-Recommended)', false)) {

            $this->vendorPublish('api-crud-stubs', is_dir(base_path('stubs/api-crud')));
        } else {
            $this->components->twoColumnDetail(
                'Publish Template Files',
                '<fg=yellow;options=bold>SKIPPED</>');
        }
    }

    private function vendorPublish(string $tag, bool $forced = false): void
    {
        if ($forced) {

            if ($this->confirm('Already Published. Overwrite?', true)) {

                $this->call('vendor:publish', ['--tag' => $tag, '--force']);
            }

            return;
        }

        $this->call('vendor:publish', ['--tag' => $tag]);
    }
}
