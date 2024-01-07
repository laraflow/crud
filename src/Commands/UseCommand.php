<?php

namespace Laraflow\Crud\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;

class UseCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'package:use';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Use the specified package.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        try {
            $module = $this->argument('module');

            $modules = scandir(config('fintech.generators.paths.modules', 'packages'));

            if (!in_array($module, $modules)) {
                throw new \InvalidArgumentException("No Package found named [{$module}].");
            }

            file_put_contents(storage_path('cli-package.json'), json_encode(['use' => $module], JSON_PRETTY_PRINT));

            $this->components->info("Package [{$module}] set to use as default module.");

            return self::SUCCESS;

        } catch (\Exception $exception) {

            $this->components->error($exception->getMessage());

            return self::FAILURE;
        }
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['module', InputArgument::REQUIRED, 'The name of module will be used.'],
        ];
    }
}
