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

        $this->files->put($path, $this->buildClass($name));
        $this->files->put($interfacePath, $this->buildInterface($name));

        $this->info($this->type.' Interface created successfully.');
        $this->info($this->type.' created successfully.');
    }

    protected function getModelName()
    {
        return ucwords(Str::camel($this->getNameInput()));
    }

    protected function getClassName()
    {
        return ucwords(Str::camel($this->getNameInput())) . 'Repository';
    }

    protected function getInterfaceName()
    {
        return ucwords(Str::camel($this->getNameInput())) . 'RepositoryInterface';
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__ . '/../stubs/repository.stub';
    }

    /**
     * Get the Interface stub file for the generator.
     *
     * @return string
     */
    protected function getInterfaceStub()
    {
        return __DIR__ . '/../stubs/repositoryInterface.stub';
    }

    /**
     * Build the class with the given name.
     *
     * @param  string $name
     * @return string
     */
    protected function buildClass($name = null)
    {
        $stub = $this->files->get($this->getStub());

        return $this->replaceClass($stub, $this->getClassName());
    }

    /**
     * Build the interface with the given name.
     *
     * @param  string $name
     * @return string
     */
    protected function buildInterface($name = null)
    {
        $stub = $this->files->get($this->getInterfaceStub());

        return $this->replaceClass($stub, $this->getClassName());
    }

    /**
     * Replace the class name for the given stub.
     *
     * @param  string  $stub
     * @param  string  $name
     * @return string
     */
    protected function replaceClass($stub, $name)
    {
        $class = Str::replace($this->getNamespace($name).'\\', '', $name);

        $stub = Str::replace('{{class}}', $class, $stub);

        $stub = Str::replace('{{model}}', $this->getModelName(), $stub);

        $stub = Str::replace('{{interface}}', $this->getInterfaceName(), $stub);

        return $stub;
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
            config('repos.paths.repo_concrete'),
            Str::replace('\\', '/', $name.'Repository')
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
            Str::replace('\\', '/', $name.'RepositoryInterface')
        );
    }
}
