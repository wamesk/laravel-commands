<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Wame\LaravelCommands\Utils\Helpers;

class WameModel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wame:model {name : Name of the model}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create model';

    protected array $paths = [
        'id' => "",
        'uuid' => "use Illuminate\Database\Eloquent\Concerns\HasUuids;\n",
        'ulid' => "use Illuminate\Database\Eloquent\Concerns\HasUlids;\n",
    ];

    protected array $uses = [
        'id' => "",
        'uuid' => "use HasUuids;\n",
        'ulid' => "use HasUlids;\n",
    ];

    protected array $sorting = [
        true => "    protected array \$sortable = [\n        'order_column_name' => 'sort',\n        'sort_when_creating' => 'true',\n        'sort_on_has_many' => 'true',\n        'sort_on_belongs_to' => 'true',\n        'nova_order_by' => 'ASC',\n    ];\n",
        false => ""
    ];

    public function handle()
    {
        $name = $this->argument('name');

        $sorting = config('wame-commands.sorting', false);

        $idType = config('wame-commands.id-type', 'ulid');

        Helpers::createDir('Models');

        if (file_exists(app_path("Models/$name.php"))) {
            return ['warn', __('Model :model already exist.', ['model' => $name])];
        } else {
            $file = Helpers::createFile("Models/$name.php");
            $lines = [
                "<?php \n",
                "\n",
                "namespace App\Models;\n",
                "\n",
                $this->paths[$idType],
                "use Illuminate\Database\Eloquent\SoftDeletes;\n",
                $sorting ? "use Spatie\EloquentSortable\Sortable;\n":"",
                $sorting ? "use Spatie\EloquentSortable\SortableTrait;\n":"",
                "\n",
                "class $name extends BaseModel",
                $sorting ? " implements Sortable":"",
                "\n{\n",
                "    use SoftDeletes;\n",
                $sorting ? "    use SortableTrait;\n":"",
                "    " . $this->uses[$idType],
                "\n",
                "    protected \$guarded = ['id'];\n",
                "\n",
                "    protected \$casts = [\n",
                "        'created_at' => 'datetime',\n",
                "        'updated_at' => 'datetime',\n",
                "        'deleted_at' => 'datetime',\n",
                "    ];\n",
                "\n",
                $this->sorting[$sorting],
                "}\n",
            ];

            fwrite($file, implode('', $lines));
            fclose($file);

            return ['info', __('Model :model has been created.', ['model' => $name])];
        }
    }
}
