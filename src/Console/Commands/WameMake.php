<?php

namespace Wame\LaravelCommands\Console\Commands;

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
        'nova',
        'lang',
    ];

    protected array $commandLabels = [
        'model' => 'Model',
        'migration' => 'Migration',
        'observer' => 'Observer',
        'events' => 'Events',
        'listeners' => 'Listeners',
        'api-controller' => 'Api Controller and Resource',
        'nova' => 'Nova resource',
        'lang' => 'Language',
    ];

    public function handle()
    {
        $modelName = $this->argument('name');
        $commands = $this->commands;
        $console = $this->output;

        $customConfiguration = $this->confirm('Would you like to customize configuration for current Model?');

        if ($customConfiguration) {
            foreach ($this->commands as $index => $command) {
                $runCommand = $this->confirm('Create '. $this->commandLabels[$command] .'?', true);
                if (!$runCommand) unset($commands[$index]);
            }
        }

        $console->note('Running commands...');
        foreach ($commands as $command) {
            $commandLabel = $this->commandLabels[$command];

            if ($customConfiguration) {
                Artisan::call("wame:$command", ['name' => $modelName]);
            } else {
                if (config("wame-commands.make.$command", true)) {
                    Artisan::call("wame:$command", ['name' => $modelName]);
                } else {
                    $console->error("Configuration setting for $commandLabel is set false, skipping command");
                }
            }
        }
        $console->success('✓');
    }
}
