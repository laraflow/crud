<?php

namespace Laraflow\ApiCrud\Traits;

use Laraflow\ApiCrud\Exceptions\GeneratorException;

trait ModuleCommandTrait
{
    /**
     * Get the module name.
     *
     *
     * @throws GeneratorException
     */
    public function getModuleName(): string
    {
        $fallbackPath = base_path(config('api-crud.root_path', 'app'));

        $module = config('api-crud.namespace', 'App');

        if (! $module) {
            throw new GeneratorException('Invalid Root namespace on config.');
        }

        if (! is_dir($fallbackPath)) {
            throw new GeneratorException('Invalid Root Path on config.');
        }

        return $module;
    }
}
