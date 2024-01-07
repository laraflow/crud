<?php

namespace Laraflow\Crud\Commands;

use Illuminate\Console\Command;

class CrudCommand extends Command
{
    public $signature = 'crud';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
