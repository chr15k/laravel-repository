<?php

namespace Chr15k\Repository;

use Illuminate\Support\ServiceProvider;
use Chr15k\Repository\Console\RepositoryMakeCommand;

class RepositoryProvider extends ServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/config.php' => config_path('repos.php'),
        ], 'config');
    }

    /**
     * {@inheritdoc}
     */
    public function register()
    {
        $this->app->bind('command.make:repository', RepositoryMakeCommand::class);

        $this->commands('command.make:repository');

        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'repos');
    }
}
