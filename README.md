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

`provider <provider_name> <module_name>`    Create a new provider class for specific module

`controller <controller_name> <module_name>`    Create a new controller class for specific module

