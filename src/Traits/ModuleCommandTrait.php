<?php

namespace Laraflow\Crud\Traits;

use Laraflow\Crud\Exceptions\GeneratorException;

trait ModuleCommandTrait
{
    /**
     * Get the module name.
     *
     * @return string
     *
     * @throws GeneratorException
     */
    public function getModuleName()
    {
        $fallbackPath = storage_path('cli-package.json');

        $module = $this->argument('module');

        if (!$module && file_exists($fallbackPath)) {

            $fallback = json_decode(file_get_contents($fallbackPath), true);

            if ($fallback['use']) {

                $module = $fallback['use'] ?? null;
            }
        }

        if (!$module) {
            throw new GeneratorException('Invalid or Missing module name on argument.');
        }

        return $module;
    }

    /**
      * return module path with start and closing slash
     * @param string|null $module
     * @return string
     * @throws GeneratorException
     */
    public function getModulePath(string $module = null)
    {
        if ($module == null) {
            $module = $this->getModuleName();
        }

        $rootPath = config('fintech.generators.paths.modules');

        return $rootPath.'/'.$module.'/';
    }

    /**
     * return module namespace with start and closing slash
     *
     * @param string|null $module
     * @return string
     * @throws GeneratorException
     */
    public function getModuleNS(string $module = null)
    {
        if ($module == null) {
            $module = $this->getModuleName();
        }

        $rootPath = config('fintech.generators.namespace');

        return '\\'.$rootPath.'\\'.$module.'\\';
    }
}
