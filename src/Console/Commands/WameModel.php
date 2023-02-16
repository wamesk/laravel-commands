<?php

declare(strict_types = 1);

namespace Wame\LaravelCommands\Console\Commands;

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
        'id' => '',
        'uuid' => "use Illuminate\Database\Eloquent\Concerns\HasUuids;\n",
        'ulid' => "use Illuminate\Database\Eloquent\Concerns\HasUlids;\n",
    ];

    protected array $uses = [
        'id' => '',
        'uuid' => "use HasUuids;\n",
        'ulid' => "use HasUlids;\n",
    ];

    public function handle(): void
    {
        $name = $this->argument('name');
        $sorting = config('wame-commands.sorting', false);
        $idType = config('wame-commands.id-type', 'ulid');
        $console = $this->output;
        $sortColumnName = config('eloquent-sortable.order_column_name', 'sort');

        $sortingArray = '';
        if ($sorting) {
            $sortingArray = $sortingArray = implode('', [
                "\n",
                "    protected array \$sortable = [\n",
                "        'order_column_name' => '{$sortColumnName}',\n",
                "        'sort_when_creating' => 'true',\n",
                "        'sort_on_has_many' => 'true',\n",
                "        'sort_on_belongs_to' => 'true',\n",
                "        'nova_order_by' => 'ASC',\n",
                "    ];\n",
            ]);
        }

        Helpers::createDir('Models');

        if (file_exists(app_path("Models/{$name}.php"))) {
            $console->info($name . ' model already exists');
        } else {
            $console->text('Creating ' . $name . ' model...');
            $file = Helpers::createFile("Models/{$name}.php");
            $lines = [
                "<?php \n",
                "\n",
                "namespace App\Models;\n",
                "\n",
                $this->paths[$idType],
                "use Illuminate\Database\Eloquent\SoftDeletes;\n",
                $sorting ? "use Spatie\EloquentSortable\Sortable;\n" : '',
                $sorting ? "use Spatie\EloquentSortable\SortableTrait;\n" : '',
                "\n",
                "class {$name} extends BaseModel",
                $sorting ? ' implements Sortable' : '',
                "\n{\n",
                "    use SoftDeletes;\n",
                $sorting ? "    use SortableTrait;\n" : '',
                '    ' . $this->uses[$idType],
                "\n",
                "    protected \$guarded = ['id'];\n",
                "\n",
                "    protected \$casts = [\n",
                "        'created_at' => 'datetime',\n",
                "        'updated_at' => 'datetime',\n",
                "        'deleted_at' => 'datetime',\n",
                "    ];\n",
                $sortingArray,
                "}\n",
            ];

            fwrite($file, implode('', $lines));
            fclose($file);

            $console->info("Created {$name} model");
        }
    }
}
