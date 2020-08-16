# Laravel Repository

[![Latest Stable Version](https://poser.pugx.org/chr15k/laravel-repository/v)](//packagist.org/packages/chr15k/laravel-repository) [![Latest Unstable Version](https://poser.pugx.org/chr15k/laravel-repository/v/unstable)](//packagist.org/packages/chr15k/laravel-repository) [![Total Downloads](https://poser.pugx.org/chr15k/laravel-repository/downloads)](//packagist.org/packages/chr15k/laravel-repository) [![License](https://poser.pugx.org/chr15k/laravel-repository/license)](//packagist.org/packages/chr15k/laravel-repository)

Laravel Repository is a package for Laravel 5 / 6 / 7.
It's used to abstract business logic into a repository layer with the aim of keeping
your codebase clean and maintainable.

## Why use this pattern? (opinion incoming)

**Controllers** should be light, human readable, and just control the flow.
Any developer should be able to take a look at a controller and understand it simply by reading it.

**Models** should be representation of the database schema and describes the relationships.
Models should just tell you what the data 'looks' like.

**Repositories** contain the business logic and any complex queries for your application.
They act as a wrapper for your models, giving human readable names to complicated queries.

The key benefit is the decoupling of models from controllers; the repository's interface is
injected into the controller's constructor method. This means that, if you need a new implementation, the
only thing you need to do is maintain your services!

```php
 $this->app->bind(
    'App\Repositories\Contracts\UserRepositoryInterface', // <-- injected into controller constructor
    'App\Repositories\Eloquent\UserRepository' // <-- Repo class
);
```

## Install
You can install this package via composer:

```bash
composer require chr15k/laravel-repository
```

If you are not running Laravel 5.5 (or higher), then add the service provider in `config/app.php`:

```php
Chr15k\Repository\RepositoryServiceProvider::class,
```

Optionally, you can publish the configuration to customise the model and repo file paths.
By default this package will assume your model files are located in `app/`

```bash
php artisan vendor:publish --provider="Chr15k\Repository\RepositoryServiceProvider"
```

## Setup

**Step 1**
Run the following command (set the name to match the name of the relating model):

```bash
php artisan make:repository User
```

This will create the following files inside app/Repositories

```bash
├── Repositories
│   ├── Contracts
│   │   └── UserRepositoryInterface.php
│   └── Eloquent
│       └── UserRepository.php
```

**Step 2**
Add the following to the `register()` method of your `app/Providers/AppServiceProvider.php` file:

```php
$this->app->bind(
    'App\Repositories\Contracts\UserRepositoryInterface',
    'App\Repositories\Eloquent\UserRepository'
);
```

**Step 3**
Simply inject the interface into your controller's constructor method, and Laravel will manage class dependencies:

```php
<?php

namespace App\Http\Controllers;

use App\Repositories\Contracts\UserRepositoryInterface;

class UserController extends Controller
{
    protected $userRepo;

    public function __construct(UserRepositoryInterface $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    public function index()
    {
        $users = $this->userRepo->all();

        return view('users.index', compact('users'));
    }

    public function show($id)
    {
        $user = $this->userRepo->find($id);

        return view('users.show', compact('user'));
    }
}
```

Just add any custom methods to `UserRepository.php` and `UserRepositoryInterface.php` and you're good to go!
If any other controller needs to use the same complex query, it's now in a centralised location.
New implementations can just be swapped out in AppServiceProvider.php and nothing else needs to change.

```php
    public function index($id)
    {
        $users = $this->userRepo->someComplexQuery();

        return view('users.index', compact('users'));
    }
}
```

## Usage
The following methods are available to your repo by default. If you need to access a method not included here,
then you can just call the `model()` method to fetch the model instance from the repo.

All of the fetch methods accept a `related` array for eager loading relationships.

You can also fetch the latest error for the last operation by calling `error()`
```php
    $this->repo->all($related = []);
    $this->repo->chunk($size, $callback);
    $this->repo->cursor();
    $this->repo->create($attributes = [], $related = []);
    $this->repo->destroy($id);
    $this->repo->errors();
    $this->repo->find($id, $related = []);
    $this->repo->findOrFail($id, $related = []);
    $this->repo->findOrNew($id, $related = []);
    $this->repo->getNew($attributes = []);
    $this->repo->model();
    $this->repo->paginate($perPage, $related = []);
    $this->repo->update($id, $attributes = [], $related = []);
```

## License
The MIT License (MIT). Please see [License File](https://github.com/chr15k/laravel-repository/blob/master/LICENSE.md) for more information.
