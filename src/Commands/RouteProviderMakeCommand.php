<?php

namespace Nwidart\Modules\Commands;

use Nwidart\Modules\Support\Config\GenerateConfigReader;
use Nwidart\Modules\Support\Stub;
use Nwidart\Modules\Traits\ModuleCommandTrait;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class RouteProviderMakeCommand extends GeneratorCommand
{
    use ModuleCommandTrait;

    protected $argumentName = 'module';


    /**
     * Specifies default fallback path of the resource.
     *
     * @var string
     */
    protected $defaultPath = "Controllers";

    /**
     * The command name.
     *
     * @var string
     */
    protected $name = 'module:route-provider';

    /**
     * The command description.
     *
     * @var string
     */
    protected $description = 'Create a new route service provider for the specified module.';

    /**
     * Stub file name
     *
     * @var null|string
     */
    protected $stubFile = 'route-provider.stub';

    /**
     * The command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['module', InputArgument::OPTIONAL, 'The name of module will be used.'],
        ];
    }

    protected function getOptions()
    {
        return [
            ['force', null, InputOption::VALUE_NONE, 'Force the operation to run when the file already exists.'],
        ];
    }

    /**
     * @inheritDoc
     * @return array
     */
    public function replaces()
    {
        return [
            'NAMESPACE'            => $this->getClassNamespace($this->getModule()),
            'CLASS'                => $this->getFileName(),
            'MODULE_NAMESPACE'     => $this->getModules()->config('namespace'),
            'MODULE'               => $this->getModuleName(),
            'CONTROLLER_NAMESPACE' => $this->getDefaultNameSpace(),
            'WEB_ROUTES_PATH'      => $this->getWebRoutesPath(),
            'API_ROUTES_PATH'      => $this->getApiRoutesPath(),
            'LOWER_NAME'           => $this->getModule()->getLowerName(),
        ];
    }

    /**
     * @inheritDoc
     * @return string
     */
    protected function getFileName(): string
    {
        return 'RouteServiceProvider';
    }

    /**
     * @return mixed
     */
    protected function getWebRoutesPath()
    {
        return '/' . $this->getModules()->config('stubs.files.routes/web', 'Routes/web.php');
    }

    /**
     * @return mixed
     */
    protected function getApiRoutesPath()
    {
        return '/' . $this->getModules()->config('stubs.files.routes/api', 'Routes/api.php');
    }
}
