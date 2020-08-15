# Laravel Repository

[![Latest Stable Version](https://poser.pugx.org/chr15k/laravel-repository/v)](//packagist.org/packages/chr15k/laravel-repository) [![Latest Unstable Version](https://poser.pugx.org/chr15k/laravel-repository/v/unstable)](//packagist.org/packages/chr15k/laravel-repository) [![Total Downloads](https://poser.pugx.org/chr15k/laravel-repository/downloads)](//packagist.org/packages/chr15k/laravel-repository) [![License](https://poser.pugx.org/chr15k/laravel-repository/license)](//packagist.org/packages/chr15k/laravel-repository)

Laravel Repository is a package for Laravel 7. It's used to abstract business logic into a repository layer.

## Why use this pattern? (opinion incoming!)

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
    'App\Repositories\Eloquent\UserRepository' // <-- Eloquent repo class
);
```

## Install
You can install this package via composer:

```bash
composer require chr15k/laravel-repository
```

## Usage


## License
The MIT License (MIT). Please see [License File](https://github.com/chr15k/laravel-repository/blob/master/LICENSE.md) for more information.
