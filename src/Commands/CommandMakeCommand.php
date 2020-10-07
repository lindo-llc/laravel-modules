<?php

namespace Nwidart\Modules\Commands;

use Nwidart\Modules\Traits\ModuleCommandTrait;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class CommandMakeCommand extends GeneratorCommand
{
    use ModuleCommandTrait;

    /**
     * The name of argument name.
     *
     * @var string
     */
    protected $argumentName = 'name';

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:make-command';

    /**
     * Appendable resource name
     *
     * @var null|string
     */
    protected $appendable = 'Command';

    /**
     * Stub file name
     *
     * @var null|string
     */
    protected $stubFile = 'command.stub';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate new Artisan command for the specified module.';

    /**
     * @inheritDoc
     * @return void
     */
    public function replaces()
    {
        return [
            'COMMAND_NAME' => $this->option('command') ?: 'command:name',
            'NAMESPACE'    => $this->getClassNamespace($this->getModule()),
            'CLASS'        => $this->getClass(),
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
            ['name', InputArgument::REQUIRED, 'The name of the command.'],
            ['module', InputArgument::OPTIONAL, 'The name of module will be used.'],
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['command', null, InputOption::VALUE_OPTIONAL, 'The terminal command that should be assigned.', null],
        ];
    }
}
