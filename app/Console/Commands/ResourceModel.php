<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Pluralizer;

/**
 * Create folders
 * - app/Events
 * - app/Listeners
 * - app/Observers
 */
class ResourceModel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'resource:model {model : Name of the Model}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Model With Migration, Observer, Events, Listeners & Example Job';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $modelName = $this->argument('model');

        $messages = [];

        // Create Model
        $bar = $this->output->createProgressBar(18);
        $messages[] = $this->createModel($modelName);
        $bar->advance();

        // Create Migration
        $messages[] = $this->createMigration($modelName);
        $bar->advance();

        // Create Observer with Events ready
        $messages[] = $this->createObserver($modelName);
        $bar->advance();

        // Create Events
        $events = ['Creating', 'Created', 'Deleting', 'Deleted', 'ForceDeleted', 'Restored', 'Updating', 'Updated'];
        foreach ($events as $event) {
            $messages[] = $this->createEvent($modelName, $event);
            $bar->advance();
        }

        // Create Listeners For Events
        foreach ($events as $event) {
            $messages[] = $this->createListener($modelName, $event, 'Job');
            $bar->advance();
        }
        $bar->finish();

        $this->newLine();
        foreach ($messages as $message) {
            $type = $message[0];
            $text = $message[1];
            $this->$type($text);
        }

        $this->line(__('All tasks finished.'));
        $this->newLine();
    }

    /**
     * Create Listener File
     *
     * @param string $modelName
     * @param string $eventName
     * @param string $suffix
     * @return array
     */
    private function createListener(string $modelName, string $eventName, string $suffix)
    {
        $suffix = 'Listener' . $suffix;
        if (file_exists(app_path('Listeners/'. $modelName . '/' . $eventName .'/Run'.$modelName.$eventName.$suffix.'.php'))) {
            return ['warn', __('Listener :listener already exist.', ['listener' => 'Run'.$modelName.$eventName.$suffix])];
        }
        if (!is_dir(app_path('Listeners/'. $modelName . '/' . $eventName))) mkdir(app_path('Listeners/'. $modelName . '/' . $eventName), 0755, true);
        $file = fopen(app_path('Listeners/'. $modelName . '/' . $eventName .'/Run'.$modelName.$eventName.$suffix.'.php'), "w");
        $lines = [
            "<?php \n",
            "\n",
            "namespace App\Listeners\\". $modelName . '\\' . $eventName . ";\n",
            "\n",
            'use App\Events\\' . $modelName . '\\' . $modelName . $eventName . "Event;\n",
            "\n",
            "\n",
            "class Run$modelName" . "$eventName" . $suffix . "\n",
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
            "\n",
            "    /**\n",
            "     * Handle the event.\n",
            "     *\n",
            "     * @param ". $modelName . $eventName.'Event' . " " . '$event' ."\n",
            "     * @return void\n",
            "     */\n",
            "    public function handle(" . $modelName.$eventName.'Event' . ' $event' . ")\n",
            "    {\n",
            "\n",
            "    }\n",
            "\n",
            "}\n",
        ];

        fwrite($file, implode('', $lines));
        fclose($file);

        return ['info', __('Listener :listener has been created.', ['listener' => 'Run' . $modelName.$eventName.$suffix])];
    }
}
