<?php

namespace Nwidart\Modules\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Nwidart\Modules\Support\Stub;
use Nwidart\Modules\Generators\FileGenerator;
use Nwidart\Modules\Traits\ModuleCommandTrait;
use Nwidart\Modules\Support\Config\GenerateConfigReader;
use Nwidart\Modules\Exceptions\FileAlreadyExistException;

abstract class GeneratorCommand extends Command
{
    use ModuleCommandTrait;

    /**
     * The name of 'name' argument.
     *
     * @var string
     */
    protected $argumentName = 'name';

    /**
     * Appendable resource name
     *
     * @var null|string
     */
    protected $appendable;

    /**
     * Stub file name
     *
     * @var null|string
     */
    protected $stubFile;

    /**
     * Specifies default fallback path of the resource.
     *
     * @var string
     */
    protected $defaultPath = "";

    /**
     * Destination file extension
     *
     * @var string
     */
    protected $outputExtension = 'php';

    /**
     * Generator paths key
     *
     * @see config/modules.php
     * @var string
     */
    protected $generatorPathsKey = 'generator.paths';

    /**
     * Generator config key
     *
     * @see config/modules.php
     * @var string
     */
    protected $generatorConfigKey = '';

    /**
     * Generator config prefix for the $generatorConfigKey
     *
     * @var string
     */
    protected $generatorConfigPrefix;

    /**
     * @var string $configKeySeparator
     */
    protected $configKeySeparator = '-';


    /**
     * Getter for the stub file
     *
     * @return null|string
     */
    public function stubFile()
    {
        return $this->stubFile;
    }

    /**
     * Getter for the appendable
     *
     * @return null|string
     */
    public function appendable()
    {
        return $this->appendable;
    }

    /**
     * Get stub file contents
     *
     * @return mixed
     */
    protected function getTemplateContents()
    {
        return (new Stub("/" . $this->stubFile(), $this->replaces()))->render();
    }

    /**
     * Resource stub replacements
     *
     * @return array
     */
    public function replaces()
    {
        return [
            // ...
        ];
    }

    /**
     * Get the destination file path.
     *
     * @return string
     */
    protected function getDestinationFilePath()
    {
        return  $this->getModulePath() . "/" .
            GenerateConfigReader::read($this->getGeneratorConfigKey())->getPath() . "/" .
            $this->resolveFilename() . '.' . $this->outputExtension;
    }

    /**
     * Resolves the filename so that it always starts with capital letter
     *
     * @return string
     */
    private function resolveFilename()
    {
        $filename = str_replace('/', ' ', $this->getFileName());
        $filename = ucwords(str_replace('\\', ' ', $filename));
        return trim(str_replace(' ', '/', $filename));
    }

    /**
     * Get configurator config key
     *
     * @return string
     */
    private function getGeneratorConfigKey()
    {
        return ($this->generatorConfigPrefix ? $this->generatorConfigPrefix . $this->configKeySeparator : '') .
            $this->generatorConfigKey;
    }

    /**
     * Execute the console command
     *
     * @return integer
     */
    public function handle(): int
    {
        $this->before();

        if ($this->executeCommand() != E_ERROR) {
            $this->after();
        }

        return 0;
    }

    /**
     * Run code before command gets executed
     *
     * @return void
     */
    public function before()
    {
        // ...
    }

    /**
     * Run code after command has executed
     *
     * @return void
     */
    public function after()
    {
        // ...
    }

    /**
     * Run the console command
     *
     * @return integer
     */
    private function executeCommand(): int
    {
        $path = str_replace('\\', '/', $this->getDestinationFilePath());

        if (!app('files')->isDirectory($dir = dirname($path))) {
            app('files')->makeDirectory($dir, 0777, true);
        }

        $contents = $this->getTemplateContents();

        try {
            $overwriteFile = $this->hasOption('force') ? $this->option('force') : false;
            (new FileGenerator($path, $contents))->withFileOverwrite($overwriteFile)->generate();

            $this->info("Created file " . basename($path));

            return 1;
        } catch (FileAlreadyExistException $e) {
            $this->critical("File [ {$path} ] already exists!");

            return E_ERROR;
        }

        return 0;
    }

    /**
     * Get class name.
     *
     * @return string
     */
    public function getClass()
    {
        return class_basename($this->getFileName());
    }

    /**
     * Get default namespace.
     *
     * @return string
     */
    public function getDefaultNamespace(): string
    {
        return $this->getModules()->config(
            $this->generatorPathsKey . '.' . $this->getGeneratorConfigKey() . '.namespace'
        ) ?: $this->getModules()->config(
            $this->generatorPathsKey . '.' . $this->getGeneratorConfigKey() . '.path',
            $this->defaultPath ?: ''
        );
    }

    /**
     * Get class namespace.
     *
     * @param \Nwidart\Modules\Module $module
     *
     * @return string
     */
    public function getClassNamespace($module)
    {
        $extra = str_replace($this->getClass(), '', $this->getFileName());

        $extra = str_replace('/', '\\', $extra);

        $namespace = $this->getModules()->config('namespace');

        $namespace .= '\\' . $module->getStudlyName();

        $namespace .= '\\' . $this->getDefaultNamespace();

        $namespace .= '\\' . $extra;

        $namespace = str_replace('/', '\\', $namespace);

        return trim($namespace, '\\');
    }

    /**
     * Get the file name.
     *
     * @return string
     */
    protected function getFileName(): string
    {

        $name = Str::studly($this->argument($this->argumentName));
        if ($this->appendable() && !Str::contains(strtolower($name), strtolower($this->appendable()))) {
            $name .= Str::studly($this->appendable());
        }

        return Str::singular(Str::studly($name));
    }
}
