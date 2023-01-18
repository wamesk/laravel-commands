# Laravel Commands

## Laravel package for better artisan commands for making Model etc.

**Installation**
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
### Make command
This command will run all commands listed below.

- [wame:model](#wame-model)
- [wame:migration](#wame-migration)
- [wame:observer](#wame-observer)
- [wame:events](#wame-events)
- [wame:listeners](#wame-listeners)
- [wame:api-controller](#wame-api-controller)

```shell
php artisan wame:make
```

### Model command {#wame-model}

### Migration command {#wame-migration}

### Observer command {#wame-observer}

### Events command {#wame-events}

### Listeners command {#wame-listeners}

### Api controller command {#wame-api-controller}
