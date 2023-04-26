<?php

declare(strict_types = 1);

namespace Wame\LaravelCommands\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Pluralizer;
use Wame\LaravelCommands\Utils\Helpers;

class WamePolicy extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wame:policy {name : Name of the model}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create policy';

    public function handle(): void
    {
        $name = $this->argument('name');
        $console = $this->output;

        if (file_exists(app_path("Policies/{$name}Policy.php"))) {
            $console->info($name . ' Policy already exists');
        } else {
            $console->text('Creating ' . $name . 'Policy...');
            Helpers::createDir('Policies');
            $file = Helpers::createFile("Policies/{$name}Policy.php");

            $lines = [
                "<?php \n",
                "\n",
                "namespace App\Policies;\n",
                "\n",
                "use Sereny\NovaPermissions\Policies\BasePolicy;\n",
                "\n",
                "class {$name}Policy extends BasePolicy\n",
                "{\n",
                "    /**\n",
                "     * The Permission key the Policy corresponds to.\n",
                "     *\n",
                "     * @var string\n",
                "     */\n",
                '    public $key' . " = '" . Pluralizer::plural($name) . "';\n",
                "\n",
                "}\n",
            ];

            fwrite($file, implode('', $lines));
            fclose($file);

            $console->info("Created {$name}Policy");
        }
    }
}
