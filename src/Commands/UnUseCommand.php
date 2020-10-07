<?php

namespace Nwidart\Modules\Commands;

use Illuminate\Console\Command;
use Nwidart\Modules\Traits\ModuleCommandTrait;

class UnUseCommand extends Command
{
    use ModuleCommandTrait;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:unuse';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Forget the used module with module:use';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->laravel['modules']->forgetUsed();

        $this->success('Previous module used successfully forgotten.');

        return 0;
    }
}
