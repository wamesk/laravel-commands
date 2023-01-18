<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Wame\LaravelCommands\Utils\Helpers;

class WameObserver extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wame:observer {name : Name of the model}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create observer';

    public function handle()
    {
        $modelName = $this->argument('name');

        Helpers::createDir('Observers');

        if (file_exists(app_path("Observers/". $modelName ."Observer.php"))) {
            return ['warn', __('Observer :observer already exist.', ['observer' => $modelName.'Observer'])];
        }

        $file = Helpers::createFile('Observers/'. $modelName .'Observer.php');

        $lines = [
            "<?php \n",
            "\n",
            "namespace App\Observers;\n",
            "\n",
            'use App\Events\\' . $modelName . '\\' . $modelName . "CreatingEvent;\n",
            'use App\Events\\' . $modelName . '\\' . $modelName . "CreatedEvent;\n",
            'use App\Events\\' . $modelName . '\\' . $modelName . "DeletingEvent;\n",
            'use App\Events\\' . $modelName . '\\' . $modelName . "DeletedEvent;\n",
            'use App\Events\\' . $modelName . '\\' . $modelName . "ForceDeletedEvent;\n",
            'use App\Events\\' . $modelName . '\\' . $modelName . "RestoredEvent;\n",
            'use App\Events\\' . $modelName . '\\' . $modelName . "UpdatingEvent;\n",
            'use App\Events\\' . $modelName . '\\' . $modelName . "UpdatedEvent;\n",
            'use App\Models\\' . $modelName . ";\n",
            "\n",
            "class $modelName" . "Observer\n",
            "{\n",
            "    /**\n",
            '     * Handle the ' . $modelName . ' "creating" event.' . "\n",
            "     *\n",
            "     * @param \App\Models\\". $modelName . ' $'.strtolower($modelName) . "\n",
            "     * @return void\n",
            "     */\n",
            "    public function creating(" . $modelName . ' $'.strtolower($modelName) . ")\n",
            "    {\n",
            "        " . $modelName . "CreatingEvent::dispatch($" .strtolower($modelName) . ");" . "\n",
            "    }\n",
            "\n",
            "    /**\n",
            '     * Handle the ' . $modelName . ' "created" event.' . "\n",
            "     *\n",
            "     * @param \App\Models\\". $modelName . ' $'.strtolower($modelName) . "\n",
            "     * @return void\n",
            "     */\n",
            "    public function created(" . $modelName . ' $'.strtolower($modelName) . ")\n",
            "    {\n",
            "        " . $modelName . "CreatedEvent::dispatch($" .strtolower($modelName) . ");" . "\n",
            "    }\n",
            "\n",
            "    /**\n",
            '     * Handle the ' . $modelName . ' "updating" event.' . "\n",
            "     *\n",
            "     * @param \App\Models\\". $modelName . ' $'.strtolower($modelName) . "\n",
            "     * @return void\n",
            "     */\n",
            "    public function updating(" . $modelName . ' $'.strtolower($modelName) . ")\n",
            "    {\n",
            "        " . $modelName . "UpdatingEvent::dispatch($" .strtolower($modelName) . ");" . "\n",
            "    }\n",
            "\n",
            "    /**\n",
            '     * Handle the ' . $modelName . ' "updated" event.' . "\n",
            "     *\n",
            "     * @param \App\Models\\". $modelName . ' $'.strtolower($modelName) . "\n",
            "     * @return void\n",
            "     */\n",
            "    public function updated(" . $modelName . ' $'.strtolower($modelName) . ")\n",
            "    {\n",
            "        " . $modelName . "UpdatedEvent::dispatch($" .strtolower($modelName) . ");" . "\n",
            "    }\n",
            "\n",
            "    /**\n",
            '     * Handle the ' . $modelName . ' "deleting" event.' . "\n",
            "     *\n",
            "     * @param \App\Models\\". $modelName . ' $'.strtolower($modelName) . "\n",
            "     * @return void\n",
            "     */\n",
            "    public function deleting(" . $modelName . ' $'.strtolower($modelName) . ")\n",
            "    {\n",
            "        " . $modelName . "DeletingEvent::dispatch($" .strtolower($modelName) . ");" . "\n",
            "    }\n",
            "\n",
            "    /**\n",
            '     * Handle the ' . $modelName . ' "deleted" event.' . "\n",
            "     *\n",
            "     * @param \App\Models\\". $modelName . ' $'.strtolower($modelName) . "\n",
            "     * @return void\n",
            "     */\n",
            "    public function deleted(" . $modelName . ' $'.strtolower($modelName) . ")\n",
            "    {\n",
            "        " . $modelName . "DeletedEvent::dispatch($" .strtolower($modelName) . ");" . "\n",
            "    }\n",
            "\n",
            "    /**\n",
            '     * Handle the ' . $modelName . ' "restored" event.' . "\n",
            "     *\n",
            "     * @param \App\Models\\". $modelName . ' $'.strtolower($modelName) . "\n",
            "     * @return void\n",
            "     */\n",
            "    public function restored(" . $modelName . ' $'.strtolower($modelName) . ")\n",
            "    {\n",
            "        " . $modelName . "RestoredEvent::dispatch($" .strtolower($modelName) . ");" . "\n",
            "    }\n",
            "\n",
            "    /**\n",
            '     * Handle the ' . $modelName . ' "force deleted" event.' . "\n",
            "     *\n",
            "     * @param \App\Models\\". $modelName . ' $'.strtolower($modelName) . "\n",
            "     * @return void\n",
            "     */\n",
            "    public function forceDeleted(" . $modelName . ' $'.strtolower($modelName) . ")\n",
            "    {\n",
            "        " . $modelName . "ForceDeletedEvent::dispatch($" .strtolower($modelName) . ");" . "\n",
            "    }\n",
            "}\n",
        ];

        fwrite($file, implode('', $lines));
        fclose($file);

        return ['info', __('Observer :observer has been created.', ['observer' => $modelName.'Observer'])];
    }
}
