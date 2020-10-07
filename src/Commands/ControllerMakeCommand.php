<?php

namespace Nwidart\Modules\Commands;

use Nwidart\Modules\Traits\ModuleCommandTrait;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class ControllerMakeCommand extends GeneratorCommand
{
    use ModuleCommandTrait;

    /**
     * The name of argument being used.
     *
     * @var string
     */
    protected $argumentName = 'controller';

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:make-controller';

    /**
     * Appendable resource name
     *
     * @var null|string
     */
    protected $appendable = 'Controller';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate new restful controller for the specified module.';

    /**
     * @inheritDoc
     * @return array
     */
    public function replaces()
    {
        return [
            'MODULENAME'        => $this->getModuleName(),
            'CONTROLLERNAME'    => $this->getFileName(),
            'NAMESPACE'         => $this->getModuleName(),
            'CLASS_NAMESPACE'   => $this->getClassNamespace($this->getModule()),
            'CLASS'             => $this->getClass(),
            'LOWER_NAME'        => $this->getModule()->getLowerName(),
            'MODULE'            => $this->getModuleName(),
            'NAME'              => $this->getModuleName(),
            'STUDLY_NAME'       => $this->getModuleName(),
            'MODULE_NAMESPACE'  => $this->getModules()->config('namespace'),
        ];
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['controller', InputArgument::REQUIRED, 'The name of the controller class.'],
            ['module', InputArgument::OPTIONAL, 'The name of module will be used.'],
        ];
    }

    /**
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['plain', 'p', InputOption::VALUE_NONE, 'Generate a plain controller', null],
            ['api', null, InputOption::VALUE_NONE, 'Exclude the create and edit methods from the controller.'],
        ];
    }
    /**
     * @inheritDoc
     * @return null|string
     */
    public function stubFile()
    {
        $stubType = '';

        if ($this->option('api') === true) {
            $stubType = '-api';
        }

        if ($this->option('plain') === true) {
            $stubType = '-plain';
        }

        return "constroller{$stubType}.stub";
    }
}
