<?php

namespace Nwidart\Modules\Commands;

use Illuminate\Support\Str;
use Nwidart\Modules\Module;
use Nwidart\Modules\Support\Config\GenerateConfigReader;
use Nwidart\Modules\Support\Stub;
use Nwidart\Modules\Traits\ModuleCommandTrait;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class ListenerMakeCommand extends GeneratorCommand
{
    use ModuleCommandTrait;

    protected $argumentName = 'name';

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:make-listener';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new event listener class for the specified module';

    /**
     * Appendable resource name
     *
     * @var null|string
     */
    protected $appendable = 'Listener';
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
            ['event', 'e', InputOption::VALUE_OPTIONAL, 'The event class being listened for.'],
            ['queued', null, InputOption::VALUE_NONE, 'Indicates the event listener should be queued.'],
        ];
    }

    /**
     * @inheritDoc
     * @return array
     */
    public function replaces()
    {
        return [
            'NAMESPACE' => $this->getClassNamespace($this->getModule()),
            'EVENTNAME' => $this->getEventName($this->getModule()),
            'SHORTEVENTNAME' => $this->getShortEventName(),
            'CLASS' => $this->getClass(),
        ];
    }

    /**
     * Get event name
     *
     * @param Module $module
     * @return void
     */
    protected function getEventName(Module $module)
    {
        $namespace = $this->getModules()->config('namespace') . "\\" . $module->getStudlyName();
        $eventPath = GenerateConfigReader::read('event');

        $eventName = $namespace . "\\" . $eventPath->getPath() . "\\" . $this->option('event');

        return str_replace('/', '\\', $eventName);
    }

    /**
     * Get short event name
     *
     * @return string
     */
    protected function getShortEventName()
    {
        return class_basename($this->option('event'));
    }


    /**
     * @inheritDoc
     * @return null|string
     */
    public function stubFile()
    {
        if ($this->option('queued')) {
            if ($this->option('event')) {
                return 'listener-queued.stub';
            }

            return 'listener-queued-duck.stub';
        }

        if ($this->option('event')) {
            return 'listener.stub';
        }

        return 'listener-duck.stub';
    }
}
