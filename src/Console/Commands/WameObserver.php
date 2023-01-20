<?php

namespace Wame\LaravelCommands\Console\Commands;

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
        $console = $this->output;
        $observerName = "{$modelName}Observer";

        Helpers::createDir('Observers');

        if (file_exists(app_path("Observers/$observerName.php"))) {
            $console->info($observerName .' already exists');
        }

        $console->text('Creating '. $observerName .'...');
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
            "     * @param \App\Models\\". $modelName . " \$entity\n",
            "     * @return void\n",
            "     */\n",
            "    public function creating(" . $modelName . " \$entity)\n",
            "    {\n",
            "        " . $modelName . "CreatingEvent::dispatch(\$entity);" . "\n",
            "    }\n",
            "\n",
            "    /**\n",
            '     * Handle the ' . $modelName . ' "created" event.' . "\n",
            "     *\n",
            "     * @param \App\Models\\". $modelName . " \$entity\n",
            "     * @return void\n",
            "     */\n",
            "    public function created(" . $modelName . " \$entity)\n",
            "    {\n",
            "        " . $modelName . "CreatedEvent::dispatch(\$entity);" . "\n",
            "    }\n",
            "\n",
            "    /**\n",
            '     * Handle the ' . $modelName . ' "updating" event.' . "\n",
            "     *\n",
            "     * @param \App\Models\\". $modelName . " \$entity\n",
            "     * @return void\n",
            "     */\n",
            "    public function updating(" . $modelName . " \$entity)\n",
            "    {\n",
            "        " . $modelName . "UpdatingEvent::dispatch(\$entity);" . "\n",
            "    }\n",
            "\n",
            "    /**\n",
            '     * Handle the ' . $modelName . ' "updated" event.' . "\n",
            "     *\n",
            "     * @param \App\Models\\". $modelName . " \$entity\n",
            "     * @return void\n",
            "     */\n",
            "    public function updated(" . $modelName . " \$entity)\n",
            "    {\n",
            "        " . $modelName . "UpdatedEvent::dispatch(\$entity);" . "\n",
            "    }\n",
            "\n",
            "    /**\n",
            '     * Handle the ' . $modelName . ' "deleting" event.' . "\n",
            "     *\n",
            "     * @param \App\Models\\". $modelName . " \$entity\n",
            "     * @return void\n",
            "     */\n",
            "    public function deleting(" . $modelName . " \$entity)\n",
            "    {\n",
            "        " . $modelName . "DeletingEvent::dispatch(\$entity);" . "\n",
            "    }\n",
            "\n",
            "    /**\n",
            '     * Handle the ' . $modelName . ' "deleted" event.' . "\n",
            "     *\n",
            "     * @param \App\Models\\". $modelName . " \$entity\n",
            "     * @return void\n",
            "     */\n",
            "    public function deleted(" . $modelName . " \$entity)\n",
            "    {\n",
            "        " . $modelName . "DeletedEvent::dispatch(\$entity);" . "\n",
            "    }\n",
            "\n",
            "    /**\n",
            '     * Handle the ' . $modelName . ' "restored" event.' . "\n",
            "     *\n",
            "     * @param \App\Models\\". $modelName . " \$entity\n",
            "     * @return void\n",
            "     */\n",
            "    public function restored(" . $modelName . " \$entity)\n",
            "    {\n",
            "        " . $modelName . "RestoredEvent::dispatch(\$entity);" . "\n",
            "    }\n",
            "\n",
            "    /**\n",
            '     * Handle the ' . $modelName . ' "force deleted" event.' . "\n",
            "     *\n",
            "     * @param \App\Models\\". $modelName . " \$entity\n",
            "     * @return void\n",
            "     */\n",
            "    public function forceDeleted(" . $modelName . " \$entity)\n",
            "    {\n",
            "        " . $modelName . "ForceDeletedEvent::dispatch(\$entity);" . "\n",
            "    }\n",
            "}\n",
        ];

        fwrite($file, implode('', $lines));
        fclose($file);

        $console->info("Created $observerName");
    }
}
