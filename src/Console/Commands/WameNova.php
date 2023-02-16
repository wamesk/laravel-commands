<?php

declare(strict_types = 1);

namespace Wame\LaravelCommands\Console\Commands;

use Illuminate\Console\Command;
use Wame\LaravelCommands\Utils\Helpers;

class WameNova extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wame:nova {name : Name of the model}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Nova resource';

    public function handle(): void
    {
        $name = $this->argument('name');
        $name2 = Helpers::camelCaseConvert($name);
        $console = $this->output;

        if (file_exists(app_path("Nova/{$name}.php"))) {
            $console->info($name . ' Nova resource already exists');
        } else {
            $console->text('Creating ' . $name . ' Nova resource...');
            $file = Helpers::createFile("Nova/{$name}.php");

            $useSortableRows = config('wame-commands.sorting', false) ? "use Outl1ne\NovaSortable\Traits\HasSortableRows;\n" : '';
            $hasSortableRows = config('wame-commands.sorting', false) ? "    use HasSortableRows;\n" : '';

            $lines = [
                "<?php \n",
                "\n",
                "namespace App\Nova;\n",
                "\n",
                "use Eminiarts\Tabs\Tab;\n",
                "use Eminiarts\Tabs\Tabs;\n",
                "use Eminiarts\Tabs\Traits\HasTabs;\n",
                "use Laravel\Nova\Fields\ID;\n",
                "use Laravel\Nova\Http\Requests\NovaRequest;\n",
                $useSortableRows,
                "\n",
                "class {$name} extends BaseResource\n",
                "{\n",
                $hasSortableRows,
                "    use HasTabs;\n",
                "\n",
                "   /**\n",
                "    * The model the resource corresponds to.\n",
                "    *\n",
                "    * @var string\n",
                "    */\n",
                "    public static \$model = \App\Models\\" . $name . "::class;\n",
                "\n",
                "    /**\n",
                "     * The single value that should be used to represent the resource when being displayed.\n",
                "     *\n",
                "     * @var string\n",
                "     */\n",
                "    public static \$title = 'id';\n",
                "\n",
                "    /**\n",
                "     * The columns that should be searched.\n",
                "     *\n",
                "     * @var array\n",
                "     */\n",
                "    public static \$search = [\n",
                "        'id', \n",
                "    ];\n",
                "\n",
                "    /**\n",
                "     * Get the fields displayed by the resource.\n",
                "     *\n",
                "     * @param \Laravel\Nova\Http\Requests\NovaRequest \$request\n",
                "     * @return array\n",
                "     */\n",
                "    public function fields(NovaRequest \$request)\n",
                "    {\n",
                "        return [\n",
                "            Tabs::make(__('{$name2}.detail', ['title' => \$this->title]), [\n",
                "                Tab::make(__('{$name2}.singular'), [\n",
                "                    ID::make()->onlyOnForms(),\n",
                "                ]),\n",
                "            ])->withToolbar(),\n",
                "        ];\n",
                "    }\n",
                "\n",
                "    /**\n",
                "     * Get the cards available for the request.\n",
                "     *\n",
                "     * @param \Laravel\Nova\Http\Requests\NovaRequest \$request\n",
                "     * @return array\n",
                "     */\n",
                "    public function cards(NovaRequest \$request)\n",
                "    {\n",
                "        return [];\n",
                "    }\n",
                "\n",
                "    /**\n",
                "     * Get the filters available for the resource.\n",
                "     *\n",
                "     * @param \Laravel\Nova\Http\Requests\NovaRequest \$request\n",
                "     * @return array\n",
                "     */\n",
                "    public function filters(NovaRequest \$request)\n",
                "    {\n",
                "        return [];\n",
                "    }\n",
                "\n",
                "    /**\n",
                "     * Get the lenses available for the resource.\n",
                "     *\n",
                "     * @param \Laravel\Nova\Http\Requests\NovaRequest \$request\n",
                "     * @return array\n",
                "     */\n",
                "    public function lenses(NovaRequest \$request)\n",
                "    {\n",
                "        return [];\n",
                "    }\n",
                "\n",
                "    /**\n",
                "     * Get the actions available for the resource.\n",
                "     *\n",
                "     * @param \Laravel\Nova\Http\Requests\NovaRequest \$request\n",
                "     * @return array\n",
                "     */\n",
                "    public function actions(NovaRequest \$request)\n",
                "    {\n",
                "        return [];\n",
                "    }\n",
                "\n",
                "}\n",
            ];

            fwrite($file, implode('', $lines));
            fclose($file);

            $console->info("Created {$name} Nova resource");
        }
    }
}
