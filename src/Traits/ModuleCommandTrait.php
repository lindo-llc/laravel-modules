<?php

namespace Nwidart\Modules\Traits;

trait ModuleCommandTrait
{
    /**
     * Get modules
     *
     * @return object
     */
    public function getModules()
    {
        return app('modules');
    }

    /**
     * Get currently used module
     *
     * @return object
     */
    public function getCurrentModule()
    {
        return $this->getModules()->getUsedNow();
    }

    /**
     * Get specified module
     *
     * @param null|string $module
     * @return object
     */
    public function getModule($module = null)
    {
        return $this->getModules()->findOrFail(
            $module ?: ($this->argument('module') ?: $this->getCurrentModule())
        );
    }

    /**
     * Get the module name.
     *
     * @return string
     */
    public function getModuleName()
    {
        return $this->getModule()->getStudlyName();
    }
}
