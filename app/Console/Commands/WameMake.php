<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class WameMake extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wame:make {name : Name of the model}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create all files for model';

    protected array $commands = [
        'model',
        'migration',
        'observer',
        'events',
        'listeners',
        'api-controller',
    ];

    public function handle()
    {
        $modelName = $this->argument('name');

        foreach ($this->commands as $command) {
            if (config("wame-commands.make.$command", true))  Artisan::call("wame:$command", ['name' => $modelName]);
        }
    }
}
