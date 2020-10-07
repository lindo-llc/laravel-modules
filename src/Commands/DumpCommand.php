<?php

namespace Nwidart\Modules\Commands;

use Illuminate\Console\Command;
use Nwidart\Modules\Traits\ModuleCommandTrait;
use Symfony\Component\Console\Input\InputArgument;

class DumpCommand extends Command
{
    use ModuleCommandTrait;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:dump';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dump-autoload the specified module or for all module.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Generating optimized autoload modules.');

        if ($module = $this->argument('module')) {
            $this->dump($module);
        } else {
            foreach ($this->getModules()->all() as $module) {
                $this->dump($module->getStudlyName());
            }
        }

        return 0;
    }

    public function dump($module)
    {
        $module = $this->getModule($module);

        $this->info("Running for <info>{$module}</info> module");

        chdir($module->getPath());

        passthru('composer dump -o -n -q');
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['module', InputArgument::OPTIONAL, 'Module name.'],
        ];
    }
}
