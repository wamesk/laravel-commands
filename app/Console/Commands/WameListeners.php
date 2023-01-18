<?php

namespace App\Console\Commands;

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

        $events = ['Creating', 'Created', 'Deleting', 'Deleted', 'ForceDeleted', 'Restored', 'Updating', 'Updated'];

        Helpers::createDir('Listeners/'. $modelName);

        foreach ($events as $event) {
            $suffix = 'Listener' . 'Job';
            if (file_exists(app_path('Listeners/'. $modelName . '/' . $event .'/Run'.$modelName.$event.$suffix.'.php'))) {
//                return ['warn', __('Listener :listener already exist.', ['listener' => 'Run'.$modelName.$event.$suffix])];
                continue;
            }

            $file = Helpers::createFile('Listeners/'. $modelName . '/Run'.$modelName.$event.$suffix.'.php');

            $lines = [
                "<?php \n",
                "\n",
                "namespace App\Listeners\\". $modelName . ";\n",
                "\n",
                'use App\Events\\' . $modelName . '\\' . $modelName . $event . "Event;\n",
                "\n",
                "class Run$modelName" . "$event" . $suffix . "\n",
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
        }
        return ['info', __('Listener :listener has been created.', ['listener' => 'Run' . $modelName.$event.$suffix])];
    }
}
