<?php

namespace Laraflow\ApiCrud\Commands;

use Illuminate\Console\Command;

class InstallCommand extends Command
{
    public $signature = 'api-crud:install';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
