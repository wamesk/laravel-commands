# Laravel Commands

Laravel package for better artisan commands for making Model etc.

<!-- TOC -->
* [Laravel Commands](#laravel-commands)
  * [Installation](#installation)
  * [Usage](#usage)
    * [Make command](#make-command)
    * [Model command](#model-command)
    * [Migration command](#migration-command)
    * [Observer command](#observer-command)
    * [Events command](#events-command)
    * [Listeners command](#listeners-command)
    * [Api controller command](#api-controller-command)
  * [Utils](#utils)
    * [Helpers](#helpers)
      * [Create directory](#create-directory)
      * [Create and/or open file](#create-andor-open-file)
      * [Camel Case converter](#camel-case-converter)
    * [Validator](#validator)
      * [Validate function](#validate-function)
      * [Code function](#code-function)
      * [Status Code function](#status-code-function)
<!-- TOC -->

## Installation
```shell
composer require wamesk/laravel-commands 
```
Use this command to upload command files into your project to use.
```shell
php artisan vendor:publish --provider="Wame\LaravelCommands\LaravelCommandsServiceProvider"
```
It will also generate config file *wame-commands.php* in the config folder.
```
project
 └─ config
     └─ wame-commands.php
```
You can configure package commands here
```php
// config/wame-commands.php
<?php
return [
    /* Version of ApiController you want to develop (v1, v2, v3, null) */
//    'version' => 'v1', // Default: null

    /* Typ of id your project is using (options: id (basic integer), uuid, ulid) */
//    'id-type' => 'uuid', // Default: ulid

    /* Enable or disable sorting in migration and model */
//    'sorting' => false, // Default: true

    /* Per page pagination default */
//    'per_page' => 10, // Default: 10

    /* You can disable commands that wame:make will run. By default, all will run. */
    'make' => [
//        'model' => false,
//        'migration' => false,
//        'observer' => false,
//        'events' => false,
//        'listeners' => false,
//        'api-controllers' => false,
    ],
];
```

## Usage

### Make command
This command will run all `php artisan` commands listed below.

- [wame:model](#model-command)
- [wame:migration](#migration-command)
- [wame:observer](#observer-command)
- [wame:events](#events-command)
- [wame:listeners](#listeners-command)
- [wame:api-controller](#api-controller-command)

Run command with *name* parameter at the end. Name parameter is your Model name. (example: User)
```shell
php artisan wame:make <name>
```
After running this command it will ask you if you want to make custom configuration.

```shell
Would you like to customize configuration for current model? (yes/no) [no]:
>
```

You can choose between *yes* and *no*.

If you choose *no (default)* it will run all commands. *Note: Make sure you have set your config file*.

If you choose *yes* it will ask you which commands should run *(Default being yes)*. *(Example shown below)*

*Note: if you make a custom configuration while running command it will ignore commands set false in config file.*

```shell
Create Model (yes/no) [yes]:
>
```

### Model command

This command will create base Model with preset classes.

It is configurable by these configs.

```php
// wame-commands.php

// Will add HasUuids or Ulids to model depending on id-type
'id-type' => 'ulid', // Other options: id, uuid

// Will add SortableTrait and Sortable interface to class along with $sortable array config
'sorting' => true, // Other option: false
```

Run this command using 

```shell
php artisan wame:model
```

### Migration command

This command will create base Migration with preset columns.

It is configurable by these configs.

```php
// wame-commands.php

// Will add id column depending on id-type
'id-type' => 'ulid', // Other options: id, uuid

// Will add `$table->unsignedInteger('sort')->nullable();` column
'sorting' => true, // Other option: false
```

Run this command using

```shell
php artisan wame:migration
```

### Observer command

This command will create base Observer with preset functions and Events.

Run this command using

```shell
php artisan wame:model
```

### Events command

This command will create base Events with preset construct, function and classes.

Run this command using

```shell
php artisan wame:events
```

### Listeners command

This command will create base Listener jobs.

Run this command using

```shell
php artisan wame:listeners
```

### Api controller command

This command will create base api Controller with preset functions, responses and validation.
It will also create base Model Resource and include it in Controller.

Run this command using

```shell
php artisan wame:api-controller
```

## Utils

Functions used in commands and for your project are documented here.

### Helpers

Include this util in your file using
```php
use Wame\LaravelCommands\Utils\Helpers;
```

#### Create directory

`Helpers::createDir()` function is used to create directory in your project.

It requires directory path parameter *(string)* and optional second parameter with chmod rule.

This function starts at *app* folder.

#### Create and/or open file

`Helpers::createFile()` function is used to create file and/or open file in your project.

It requires path of file, name and extension parameter *(string)* and optional second parameter is mode *(string)* Example: 'w' / 'r'.

This function starts at *app* folder.

#### Camel Case converter 

`Helpers::camelCaseConvert()` function is used to convert string

It requires string parameter to convert.

Second parameter is separator *(string) (default: '_')*. Optional parameter.

Third parameter is lower *(bool) (default: false)*. Optional parameter. Changes if words should start with lower case.

Example:
```php
$camelCase = Helpers::camelCaseConvert('SuperModel');

return $camelCase; // returns 'super_model'
```

### Validator

Validator used by default in this package.
It works by chaining functions and getting response.
Response is generated by `wamesk/laravel-api-response` package functions.
To better understand how response works checkout documentation for response package [here](https://github.com/wamesk/laravel-api-response)

#### Validate function

This function is final function. Always last.
It requires data and rules for validation.
Documentation for rules [click here](https://laravel.com/docs/9.x/validation)

Usage example:

```php
$data = ['email' => 'example@gmail.com', 'password' => 'password'];

$validator = Validator::validate($data, [
    'email' => 'email|required|max:255',
    'password' => 'required|string'
]);
if ($validator) return $validator;
```

In case of validation error it will return

```json
{
    "data": null,
    "code": null,
    "errors": {
        "email": [
            "validation.required"
        ]
    },
    "message": null
}
```

#### Code function

This function is add internal code in response.
You can pass second parameter that changes prefix for message translation.

Usage example:

```php
$data = ['email' => 'example@gmail.com', 'password' => 'password'];

$validator = Validator::code('1.2')->validate($data, [
    'email' => 'email|required|max:255',
    'password' => 'required|string'
]);
if ($validator) return $validator;
```

In case of validation error it will return

```json
{
    "data": "1.2",
    "code": null,
    "errors": {
        "email": [
            "validation.required"
        ]
    },
    "message": "api.1.2"
}
```

#### Status Code function

This function doesn't change response visually but changes status code of response.
Default status code is 400 *(Bad Request)*.
If you want to chain all functions it can look like this.
Status code is always integer.

```php
Validator::statusCode($statusCode)->code($code)->validate($data, $rules);
```
