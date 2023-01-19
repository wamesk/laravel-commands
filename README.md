# Laravel Commands

## Laravel package for better artisan commands for making Model etc.

## Installation
```shell
composer require wamesk/laravel-commands 
```
Use this command to upload command files into your project to use.
```shell
php artisan vendor:publish
```
Commands should appear in the *Commands* folder as shown below.
```
project
 └─ app
     └─ Console
         └─ Commands
             │  WameApiController.php
             │  WameEvents.php
             │  WameListeners.php
             │  WameMake.php
             │  WameMigration.php
             │  WameModel.php
             └─ WameObserver.php
```
## Usage

### Make command
This command will run all `php artisan` commands listed below.

- [wame:model](#wame-model)
- [wame:migration](#wame-migration)
- [wame:observer](#wame-observer)
- [wame:events](#wame-events)
- [wame:listeners](#wame-listeners)
- [wame:api-controller](#wame-api-controller)

```shell
php artisan wame:make
```

### Model command

### Migration command

### Observer command

### Events command

### Listeners command

### Api controller command

## Utils

### Helpers

### Validator
