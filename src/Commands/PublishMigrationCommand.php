<?php

namespace Nwidart\Modules\Commands;

use Illuminate\Console\Command;
use Nwidart\Modules\Migrations\Migrator;
use Nwidart\Modules\Publishing\MigrationPublisher;
use Symfony\Component\Console\Input\InputArgument;

class PublishMigrationCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:publish-migration';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Publish a module's migrations to the application";

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        if ($name = $this->argument('module')) {
            $this->publish($this->getModule());

            return 0;
        }

        foreach ($this->getModules()->allEnabled() as $module) {
            $this->publish($this->getModule());
        }

        return 0;
    }

    /**
     * Publish migration for the specified module.
     *
     * @param \Nwidart\Modules\Module $module
     */
    public function publish($module)
    {
        with(new MigrationPublisher(new Migrator($module, $this->getLaravel())))
            ->setRepository($this->getModules())
            ->setConsole($this)
            ->publish();
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['module', InputArgument::OPTIONAL, 'The name of module being used.'],
        ];
    }
}
