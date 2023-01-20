<?php

namespace Wame\LaravelCommands\Console\Commands;

use Illuminate\Console\Command;
use Wame\LaravelCommands\Utils\Helpers;

class WameListeners extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wame:listeners {name : Name of the model}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create listeners';

    public function handle()
    {
        $modelName = $this->argument('name');
        $console = $this->output;

        $events = ['Creating', 'Created', 'Deleting', 'Deleted', 'ForceDeleted', 'Restored', 'Updating', 'Updated'];

        Helpers::createDir('Listeners/'. $modelName);

        foreach ($events as $event) {
            $listenerName = 'Run'.$modelName.$event. 'ListenerJob';
            $listenerPath = 'Listeners/'. $modelName . '/' . $event ."/$listenerName.php";

            if (file_exists(app_path($listenerPath))) {
                $console->info($listenerName . ' already exists.');
                continue;
            }

            $console->text('Creating '. $listenerName . '...');

            $file = Helpers::createFile('Listeners/'. $modelName . "/$listenerName.php");

            $lines = [
                "<?php \n",
                "\n",
                "namespace App\Listeners\\". $modelName . ";\n",
                "\n",
                'use App\Events\\' . $modelName . '\\' . $modelName . $event . "Event;\n",
                "\n",
                "class $listenerName\n",
                "{\n",
                "    /**\n",
                "     * Create the event listener.\n",
                "     *\n",
                "     * @return void\n",
                "     */\n",
                "    public function __construct()\n",
                "    {\n",
                "        //\n",
                "    }\n",
                "\n",
                "    /**\n",
                "     * Handle the event.\n",
                "     *\n",
                "     * @param ". $modelName . $event.'Event' . " " . '$event' ."\n",
                "     * @return void\n",
                "     */\n",
                "    public function handle(" . $modelName.$event.'Event' . ' $event' . ")\n",
                "    {\n",
                "\n",
                "    }\n",
                "}\n",
            ];

            fwrite($file, implode('', $lines));
            fclose($file);
            $console->info("Created $listenerName");
        }
    }
}
