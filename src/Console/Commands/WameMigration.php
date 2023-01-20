<?php

namespace Wame\LaravelCommands\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Pluralizer;
use Wame\LaravelCommands\Utils\Helpers;

class WameMigration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wame:migration {name : Name of the model}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create migration';

    protected array $idColumns = [
        'id' => '$table->id()',
        'uuid' => '$table->uuid(\'id\')->primary()',
        'ulid' => '$table->ulid(\'id\')->primary()',
    ];

    public function handle()
    {
        $modelName = $this->argument('name');
        $console = $this->output;

        $tableName = Helpers::camelCaseConvert(Pluralizer::plural($modelName));
        $migrationFileName = 'create_' . $tableName . '_table';
        $migrationFile = glob(app_path('../database/migrations/*' . $migrationFileName . '.php'));

        $idColumn = $this->idColumns[config('wame-commands.id-type', 'ulid')];
        $sorting = config('wame-commands.sorting');

        Helpers::createDir('../database/migrations');

        if (empty(count($migrationFile))) {
            Artisan::call('make:migration', ['name' => $migrationFileName, '--table' => $tableName]);

            $found = false;
            // Wait for file to exist
            for($i=0; $i<5; $i++){if(!empty(glob(app_path('../database/migrations/*' . $migrationFileName . '.php')))){$found = true;$migrationFile= glob(app_path('../database/migrations/*' . $migrationFileName . '.php'))[0];break;} sleep(1);}
            if ($found) {
                $console->text("Creating $migrationFileName...");

                $file = fopen($migrationFile, "w");
                $lines = [
                    "<?php \n",
                    "\n",
                    "use Illuminate\Database\Migrations\Migration;\n",
                    "use Illuminate\Database\Schema\Blueprint;\n",
                    "use Illuminate\Support\Facades\Schema;\n",
                    "\n",
                    'return new class extends Migration' . "\n",
                    "{\n",
                    "    /**\n",
                    "    * Run the migrations.\n",
                    "    *\n",
                    "    * @return void\n",
                    "    */\n",
                    "    public function up()\n",
                    "    {\n",
                    "        Schema::create('" . $tableName . "', function (Blueprint \$table) {\n",
                    "            $idColumn;\n",
                    $sorting ? "            \$table->unsignedInteger('". config('eloquent-sortable.order_column_name', 'sort') ."')->nullable();\n":"",
                    "            \$table->timestamps();\n",
                    "            \$table->softDeletes();\n",
                    "        });\n",
                    "    }\n",
                    "\n",
                    "    /**\n",
                    "     * Reverse the migrations.\n",
                    "     *\n",
                    "     * @return void\n",
                    "     */\n",
                    "    public function down()\n",
                    "    {\n",
                    "        Schema::dropIfExists('" . $tableName . "');\n",
                    "    }\n",
                    "\n",
                    "};\n",
                ];

                fwrite($file, implode('', $lines));
                fclose($file);
            }
            $console->info("Created $migrationFileName");
        } else {
            $console->info("$migrationFileName already exists");
        }
    }
}
