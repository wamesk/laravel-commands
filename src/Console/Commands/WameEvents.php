<?php

declare(strict_types = 1);

namespace Wame\LaravelCommands\Console\Commands;

use Illuminate\Console\Command;
use Wame\LaravelCommands\Utils\Helpers;

class WameEvents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wame:events {name : Name of the model}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create events';

    public function handle(): void
    {
        $modelName = $this->argument('name');
        $console = $this->output;

        $events = ['Creating', 'Created', 'Deleting', 'Deleted', 'ForceDeleted', 'Restored', 'Updating', 'Updated'];
        foreach ($events as $event) {
            $eventName = $event . 'Event';
            $filePath = 'Events/' . $modelName . '/' . $modelName . $eventName . '.php';
            if (file_exists(app_path($filePath))) {
                $console->info($modelName . $eventName . ' already exists.');
                continue;
            }

            $console->text('Creating ' . $modelName . $eventName . '...');

            Helpers::createDir('Events/' . $modelName);
            $file = Helpers::createFile($filePath);

            $lines = [
                "<?php \n",
                "\n",
                "namespace App\Events\\" . $modelName . ";\n",
                "\n",
                'use App\Models\\' . $modelName . ";\n",
                "use Illuminate\Broadcasting\InteractsWithSockets;\n",
                "use Illuminate\Broadcasting\PrivateChannel;\n",
                "use Illuminate\Foundation\Events\Dispatchable;\n",
                "use Illuminate\Queue\SerializesModels;\n",
                "\n",
                "class {$modelName}" . "{$eventName}\n",
                "{\n",
                "    use Dispatchable;\n",
                "    use InteractsWithSockets;\n",
                "    use SerializesModels;\n",
                "\n",
                "    /**\n",
                "     * Create a new event instance.\n",
                "     *\n",
                "     * @return void\n",
                "     */\n",
                "    public function __construct(\n",
                '        public ' . $modelName . " \$entity\n",
                "    ) { }\n",
                "\n",
                "    /**\n",
                "     * Get the channels the event should broadcast on.\n",
                "     *\n",
                "     * @return \Illuminate\Broadcasting\Channel|array\n",
                "     */\n",
                "    public function broadcastOn()\n",
                "    {\n",
                "        return new PrivateChannel('channel-name');\n",
                "    }\n",
                "}\n",
            ];
            fwrite($file, implode('', $lines));
            fclose($file);

            $console->info("Created {$eventName}");
        }
    }
}
