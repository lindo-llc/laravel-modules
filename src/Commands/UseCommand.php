<?php

namespace Nwidart\Modules\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Nwidart\Modules\Traits\ModuleCommandTrait;
use Symfony\Component\Console\Input\InputArgument;

class UseCommand extends Command
{
    use ModuleCommandTrait;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:use';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Use the specified module.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $module = Str::studly($this->argument('module'));

        if (!$this->getModules()->has($module)) {
            $this->critical("{$module} module does not exists.");

            return E_ERROR;
        }

        $this->getModules()->setUsed($module);

        $this->success("{$module} module is not being used");

        return 0;
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['module', InputArgument::REQUIRED, 'The name of module will be used.'],
        ];
    }
}
