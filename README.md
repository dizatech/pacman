# Pacman
Simple Laravel Package Manager

pacman provides simple commands to create and manage
your laravel packages and modules , version 1.0.0

## Installation
Using composer :

`composer require dizatech/pacman`

Packagist : https://packagist.org/packages/dizatech/pacman

## Usage
You can always checkout new commands by `php artisan list` ,
pacman section

`php artisan pacman:<command>` with arguments

## Available Commands

`module <module_name>`          Create a new module structure and service provider

`controller`    Create a new controller class for specific module

`migration`     Create a new migration file for specific module

`model`         Create a new Eloquent model class for specific module

`seeder`        Create a new seeder class for specific module

`component`     Create a new view component class for specific module

`observer`      Create a new observer class for specific module

`request`       Create a new form request class for specific module
