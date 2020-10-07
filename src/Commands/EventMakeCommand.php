<?php

namespace Nwidart\Modules\Commands;

use Nwidart\Modules\Commands\GeneratorCommand;
use Nwidart\Modules\Traits\ModuleCommandTrait;
use Symfony\Component\Console\Input\InputArgument;

class EventMakeCommand extends GeneratorCommand
{
    use ModuleCommandTrait;

    protected $argumentName = 'name';

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:make-event';

    /**
     * Appendable resource name
     *
     * @var null|string
     */
    protected $appendable = 'Event';

    /**
     * Stub file name
     *
     * @var null|string
     */
    protected $stubFile = 'event.stub';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new event class for the specified module';

    /**
     * @inheritDoc
     * @return array
     */
    public function replaces()
    {
        return [
            'NAMESPACE' => $this->getClassNamespace($this->getModule()),
            'CLASS' => $this->getClass(),
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
            ['name', InputArgument::REQUIRED, 'The name of the event.'],
            ['module', InputArgument::OPTIONAL, 'The name of module will be used.'],
        ];
    }
}
