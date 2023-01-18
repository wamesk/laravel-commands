<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Wame\ApiResponse\Helpers\ApiResponse;
use Wame\LaravelCommands\Utils\Helpers;

class WameApiController extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wame:api-controller {name : Name of the model}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create api controller';

    public function handle()
    {
        $name = $this->argument('name');

        $version = config('wame-commands.version');

//        $controllerFile = $version ? "Http/Controllers/" . $version ."/". $name. "Controller.php" : "Http/Controllers/". $name. "Controller.php";
        $idType = config('wame-commands.id-type', 'ulid');

        $controllerName = $name. "Controller";
        $controllerFile = $version ? "Http\Controllers\\$version\\$controllerName.php" : "Http\Controllers\\$controllerName.php";
        $resourceName = $version ? $version ."\\". $name. "Resource":$name. "Resource";

        $namespace = $version ? "namespace App\Http\Controllers\\". $version .";\n" : "namespace App\Http\Controllers;\n";

        Artisan::call($version ? "make:resource $version/{$name}Resource" : "make:resource {$name}Resource");

        Helpers::createDir($version ? 'Http/Controllers/'. $version:'Http/Controllers');

        if (file_exists(app_path("Models/$name.php"))) {
            return ['warn', __('Model :model already exist.', ['model' => $name])];
        } else {
            $file = Helpers::createFile($controllerFile);
            $lines = [
                "<?php \n",
                "\n",
                $namespace,
                "\n",
                "use App\Http\Controllers\Controller;\n",
                "use App\Http\Resources\\$resourceName;\n",
                "use App\Models\\$name;\n",
                "use Illuminate\Http\JsonResponse;\n",
                "use Illuminate\Http\Request;\n",
                "use Wame\ApiResponse\Helpers\ApiResponse;\n",
                "use Wame\LaravelCommands\Utils\Validator;\n",
                "\n",
                "/**\n",
                " * @group $name\n",
                " */\n",
                "class $controllerName extends Controller\n",
                "{\n",
                "    /**\n",
                "     * GET $name index\n",
                "     *\n",
                "     * @bodyParam per_page int Pagination per page Example: 20\n",
                "     *\n",
                "     * @param Request \$request\n",
                "     * @return JsonResponse\n",
                "     */\n",
                "    public function index(Request \$request): JsonResponse\n",
                "    {\n",
                "        Validator::code()->validate(\$request->all(), [\n",
                "            'per_page' => 'integer'\n",
                "        ]);\n",
                "\n",
                "        try {\n",
                "            \$perPage = \$request->get('per_page', config('wame-commands.per_page', 20));\n",
                "\n",
                "            \$data = $name::paginate(\$perPage);\n",
                "\n",
                "            return ApiResponse::collection(\$data, ". $name ."Resource::class)->code()->response();\n",
                "        } catch (\Exception \$e) {\n",
                "            return ApiResponse::code()->message(\$e->getMessage())->response(\$e->getCode());\n",
                "        }\n",
                "    }\n",
                "\n",
                "    /**\n",
                "     * POST $name store\n",
                "     *\n",
                "     * @param Request \$request\n",
                "     * @return JsonResponse\n",
                "     */\n",
                "    public function store(Request \$request): JsonResponse\n",
                "    {\n",
                "        Validator::code()->validate(\$request->all(), [\n",
                "\n",
                "        ]);\n",
                "\n",
                "        try {\n",
                "            \$entity = $name::create([\n",
                "\n",
                "            ]);\n",
                "\n",
                "            return ApiResponse::data(\$entity)->code()->response(201);\n",
                "        } catch (\Exception \$e) {\n",
                "            return ApiResponse::code()->message(\$e->getMessage())->response(\$e->getCode());\n",
                "        }\n",
                "    }\n",
                "\n",
                "    /**\n",
                "     * GET $name show\n",
                "     *\n",
                "     * @urlParam id $idType required $name id Example: 9840ac0b-089e-433b-975a-5a6b58885e29\n",
                "     *\n",
                "     * @param string \$id\n",
                "     * @return JsonResponse\n",
                "     */\n",
                "    public function show(string \$id): JsonResponse\n",
                "    {\n",
                "        try {\n",
                "            \$entity = $name::find(\$id);\n",
                "            if (!\$entity) return ApiResponse::code()->response(404);;\n",
                "\n",
                "            return ApiResponse::data(\$entity)->code()->response();\n",
                "        } catch (\Exception \$e) {\n",
                "            return ApiResponse::code()->message(\$e->getMessage())->response(\$e->getCode());\n",
                "        }\n",
                "    }\n",
                "\n",
                "    /**\n",
                "     * PUT $name update\n",
                "     *\n",
                "     * @urlParam id $idType required $name id Example: 9840ac0b-089e-433b-975a-5a6b58885e29\n",
                "     *\n",
                "     * @param string \$id\n",
                "     * @param Request \$request\n",
                "     * @return JsonResponse\n",
                "     */\n",
                "    public function update(string \$id, Request \$request): JsonResponse\n",
                "    {\n",
                "        try {\n",
                "            \$entity = $name::find(\$id);\n",
                "            if (!\$entity) return ApiResponse::code()->response(404);;\n",
                "\n",
                "            \$entity->update([\n",
                "\n",
                "            ]);\n",
                "\n",
                "            return ApiResponse::data(\$entity)->code()->response();\n",
                "        } catch (\Exception \$e) {\n",
                "            return ApiResponse::code()->message(\$e->getMessage())->response(\$e->getCode());\n",
                "        }\n",
                "    }\n",
                "\n",
                "    /**\n",
                "     * DELETE $name delete\n",
                "     *\n",
                "     * @urlParam id $idType required $name id Example: 9840ac0b-089e-433b-975a-5a6b58885e29\n",
                "     *\n",
                "     * @param string \$id\n",
                "     * @return JsonResponse\n",
                "     */\n",
                "    public function delete(string \$id): JsonResponse\n",
                "    {\n",
                "        try {\n",
                "            \$entity = $name::find(\$id);\n",
                "            if (!\$entity) return ApiResponse::code()->response(404);;\n",
                "\n",
                "            \$entity->delete();\n",
                "\n",
                "            return ApiResponse::data(\$entity)->code()->response();\n",
                "        } catch (\Exception \$e) {\n",
                "            return ApiResponse::code()->message(\$e->getMessage())->response(\$e->getCode());\n",
                "        }\n",
                "    }\n",
                "}\n",
            ];

            fwrite($file, implode('', $lines));
            fclose($file);

            return ['info', __('Model :model has been created.', ['model' => $name])];
        }
    }
}
