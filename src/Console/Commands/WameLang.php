<?php

declare(strict_types = 1);

namespace Wame\LaravelCommands\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Pluralizer;
use Wame\LaravelCommands\Utils\Helpers;
use Wame\Utils\Helpers\Dir;

class WameLang extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wame:lang {name : Name of the model}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create lang file';

    public function handle(): void
    {
        $langs = config('wame-commands.langs', ['en']);

        $name = $this->argument('name');
        $name2 = Helpers::camelCaseConvert($name);
        $singular = Helpers::camelCaseConvert($name, ' ');
        $singularUc = ucfirst($singular);
        $plural = Pluralizer::plural($singularUc);

        foreach ($langs as $lang) {
            if (file_exists(resource_path("lang/{$lang}/{$name2}.php"))) {
                $this->output->info("./resources/lang/{$lang}/{$name2}.php lang already exists");
            } else {
                $this->output->text("Creating ./resources/lang/{$lang}/{$name2}.php lang...");

                $dir = resource_path("lang/{$lang}");
                Dir::createDir($dir);
                $file = fopen($dir . "/{$name2}.php", 'w');

                $lines = [
                    "<?php \n",
                    "\n",
                    "return [\n",
                    "    'label' => '{$plural}',\n",
                    "    'plural' => '{$plural}',\n",
                    "    'singular' => '{$singularUc}',\n",
                    "    'detail' => '{$singularUc}: :title',\n",
                    "\n",
                    "    'create.button' => 'Create {$singular}',\n",
                    "    'update.button' => 'Update {$singular}',\n",
                    "\n",
                    "];\n",
                ];

                fwrite($file, implode('', $lines));
                fclose($file);

                $this->output->info("Created ./resources/lang/{$lang}/{$name2}.php lang");
            }
        }
    }
}
