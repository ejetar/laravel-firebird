# Laravel Firebird  

---
![GitHub last commit](https://img.shields.io/github/last-commit/ejetar/laravel-firebird)
![GitHub release (latest by date)](https://img.shields.io/github/v/release/ejetar/laravel-firebird)
![GitHub](https://img.shields.io/github/license/ejetar/laravel-firebird)

* [About](#about)
* [Compability](#compability)
* [Installation](#installation)
* [Changelog](#changelog)
* [Contributing](#contributing)
* [Credits](#credits)
* [License](#license)

## About
With this package you can use Eloquent and QueryBuilder with a Firebird database. 🔥

## Compability 
Support for Laravel 5.5 to 8.x with PHP 7.1+ and Firebird 1.5, 2.5, 3.0.

## Installation

1. Install/enable the Firebird PDO driver for PHP (`pdo_firebird`);
2. Install the package with composer:
```bash
composer require ejetar/laravel-firebird
```
3. As of Laravel 5.5, it is not necessary to inform service providers in `config/app.php`. But if you want to inform, enter the file `config/app.php` and include the class below in the section `providers`:
```php
Ejetar\LaravelFirebird\FirebirdServiceProvider::class
```
4. Declare your connection in section `connections` in file `config/database.php`, using firebird driver:
```php
'firebird' => [
    'driver'         => 'firebird',
    'host'           => env('DB_HOST', 'localhost'),
    'database'       => env('DB_DATABASE','/storage/firebird/APPLICATION.FDB'),
    'username'       => env('DB_USERNAME', 'sysdba'),
    'password'       => env('DB_PASSWORD', 'masterkey'),
    'charset'        => env('DB_CHARSET', 'UTF8'),
    'role'           => 'RDB$ADMIN',
    //'engine_version' => '3.0', //it will be discovered automatically
]
```
If you do not enter `engine_version`, it will be discovered automatically.

## Changelog
Nothing for now...

## Contributing
Contribute to this wonderful project, it will be a pleasure to have you with us. Let's help the free software community. You are invited to incorporate new features, make corrections, report bugs, and any other form of support. Don't forget to star in this repository! 😀

## Credits
This package was based on the repository [marcha/laravel-firebird](https://github.com/marcha/laravel-firebird) and its predecessors, forked and extended:
* [sim1984/laravel-firebird](https://github.com/sim1984/laravel-firebird)
* [jacquestvanzuydam/laravel-firebird](https://github.com/jacquestvanzuydam/laravel-firebird)
* [KKSzymanowski/laravel-6-firebird](https://github.com/KKSzymanowski/laravel-6-firebird)
* [harrygulliford/laravel-firebird](https://github.com/harrygulliford/laravel-firebird)

## License
This library is a open-source software licensed under the MIT license.
