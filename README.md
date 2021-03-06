# Pacman
Simple Laravel Package Manager

[![GitHub issues](https://img.shields.io/github/issues/dizatech/pacman?style=flat-square)](https://github.com/dizatech/pacman/issues)
[![GitHub stars](https://img.shields.io/github/stars/dizatech/pacman?style=flat-square)](https://github.com/dizatech/pacman/stargazers)
[![GitHub forks](https://img.shields.io/github/forks/dizatech/pacman?style=flat-square)](https://github.com/dizatech/pacman/network)
[![GitHub license](https://img.shields.io/github/license/dizatech/pacman?style=flat-square)](https://github.com/dizatech/pacman/blob/master/LICENSE)

pacman provides simple commands to create and manage
your laravel packages and modules

## Installation
Using composer :

`composer require dizatech/pacman`

Packagist : https://packagist.org/packages/dizatech/pacman

## Usage
You can always checkout new commands by `php artisan list` ,
pacman section

`php artisan pacman:<command>` with arguments

`php artisan pacman:<command> --directory=<specific-directory>` --directory is optional

default directory is `modules`

## Available Commands

`module <module_name>`          Create a new module structure and service provider

`provider <provider_name> <module_name>`    Create a new provider class for specific module

`controller <controller_name> <module_name>`    Create a new controller class for specific module

`migration <migration_name> <module_name>`    Create a new migration file for specific module

`seeder <seeder_name> <module_name>`    Create a new seeder class for specific module

`model <model_name> <module_name>`         Create a new Eloquent model class for specific module

`request <request_name> <module_name>`       Create a new form request class for specific module

`facade <facade_name> <module_name>`       Create a new facade class for specific module

`base-facade <module_name>`       Create a new base facade class for specific module

`repository <repository_name> <module_name>`       Create a new repository class for specific module

## ChangeLog

https://github.com/dizatech/pacman/wiki/ChangeLog
