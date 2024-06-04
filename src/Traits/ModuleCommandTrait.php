<?php

namespace Laraflow\ApiCrud\Traits;

use Laraflow\ApiCrud\Exceptions\GeneratorException;

trait ModuleCommandTrait
{
    /**
     * return module path with start and closing slash
     *
     * @return string
     *
     * @throws GeneratorException
     */
    public function getModulePath(?string $module = null)
    {
        if ($module == null) {
            $module = $this->getModuleName();
        }

        $rootPath = config('fintech.generators.paths.modules');

        return $rootPath.'/'.$module.'/';
    }

    /**
     * Get the module name.
     *
     * @return string
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

    /**
     * return module namespace with start and closing slash
     *
     * @throws GeneratorException
     */
    public function getModuleNS(?string $module = null): string
    {
        if ($module == null) {
            $module = $this->getModuleName();
        }

        $rootPath = config('fintech.generators.namespace');

        return '\\'.$rootPath.'\\'.$module.'\\';
    }
}
