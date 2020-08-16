<?php

namespace Chr15k\Repository\Console;

use Illuminate\Support\Str;
use Illuminate\Console\GeneratorCommand;

class RepositoryMakeCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:repository';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new repository class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Repository';

    /**
     * Execute the console command.
     *
     * @return bool|null
     */
    public function handle()
    {
        $name = $this->qualifyClass($this->getNameInput());

        $path = $this->getPath($name);

        $interfacePath = $this->getInterfacePath($name);

        if ((! $this->hasOption('force') || ! $this->option('force'))
            && $this->alreadyExists($this->getNameInput())
        ) {
            $this->error($this->type.' already exists!');

            return false;
        }

        $this->makeDirectory($path);
        $this->makeDirectory($interfacePath);

        $this->files->put($path, $this->build(
            $this->getClassStub(),
            $this->getClassName()
        ));

        $this->files->put($interfacePath, $this->build(
            $this->getInterfaceStub(),
            $this->getInterfaceName()
        ));

        $this->info($this->type.' Interface created successfully.');
        $this->info($this->type.' created successfully.');
    }

    /**
     * Get the name from the input.
     *
     * @return string
     */
    protected function getModelName()
    {
        return ucwords(Str::camel($this->getNameInput()));
    }

    /**
     * Get the class name.
     *
     * @return string
     */
    protected function getClassName()
    {
        return $this->getModelName() . 'Repository';
    }

    /**
     * Get the interface name.
     *
     * @return string
     */
    protected function getInterfaceName()
    {
        return $this->getModelName() . 'RepositoryInterface';
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getClassStub()
    {
        return __DIR__ . '/../../stubs/repository.stub';
    }

    /**
     * Get the Interface stub file for the generator.
     *
     * @return string
     */
    protected function getInterfaceStub()
    {
        return __DIR__ . '/../../stubs/repositoryInterface.stub';
    }

    /**
     * Replace the class name for the given stub.
     *
     * @param  string  $stub
     * @param  string  $name
     * @return string
     */
    protected function build($stub, $name)
    {
        $stub = $this->files->get($stub);

        $search = [
            '{{namespace}}',
            '{{class}}',
            '{{model}}',
            '{{interface}}'
        ];

        $replace = [
            $this->generateNamespace($name),
            $this->generateClass($name),
            $this->getModelName(),
            $this->getInterfaceName()
        ];

        return str_replace($search, $replace, $stub);
    }

    /**
     * Get the destination class path.
     *
     * @param  string $name
     * @return string
     */
    protected function getPath($name)
    {
        $name = Str::replaceFirst($this->rootNamespace(), '', $name);

        return sprintf(
            "%s/%s.php",
            config('repos.paths.repo'),
            str_replace('\\', '/', $name.'Repository')
        );
    }

    /**
     * Get the destination class path.
     *
     * @param  string $name
     * @return string
     */
    protected function getInterfacePath($name)
    {
        $name = Str::replaceFirst($this->rootNamespace(), '', $name);

        return sprintf(
            "%s/%s.php",
            config('repos.paths.repo_interface'),
            str_replace('\\', '/', $name.'RepositoryInterface')
        );
    }

    /**
     * Generate the namespace for the stub.
     *
     * @param  string $name
     * @return string
     */
    protected function generateNamespace($name)
    {
        $configKey = Str::contains($name, 'Interface') ? 'repo_interface' : 'repo';

        return sprintf(
            "%s%s",
            $this->rootNamespace(),
            str_replace('/', '\\', Str::after(config("repos.paths.{$configKey}"), 'app/'))
        );
    }

    /**
     * Generate the class name.
     *
     * @param  string $name
     * @return string
     */
    protected function generateClass($name)
    {
        return str_replace($this->getNamespace($name).'\\', '', $name);
    }
}
