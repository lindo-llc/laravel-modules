<?php

namespace Nwidart\Modules\Commands;

use Illuminate\Console\Command;
use Nwidart\Modules\Traits\ModuleCommandTrait;
use Symfony\Component\Console\Input\InputArgument;

class ModuleDeleteCommand extends Command
{
    use ModuleCommandTrait;

    protected $name = 'module:delete';
    protected $description = 'Delete a module from the application';

    public function handle(): int
    {
        $this->laravel['modules']->delete($this->argument('module'));

        $this->success("{$this->argument('module')} module has been deleted.");

        return 0;
    }

    protected function getArguments()
    {
        return [
            ['module', InputArgument::REQUIRED, 'The name of module to delete.'],
        ];
    }
}
