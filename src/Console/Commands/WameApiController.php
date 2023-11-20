<?php

declare(strict_types = 1);

namespace Wame\LaravelCommands\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Pluralizer;
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

    public function handle(): void
    {
        $name = $this->argument('name');
        $console = $this->output;

        $version = config('wame-commands.version', null);
        $idType = config('wame-commands.id-type', 'ulid');

        $tableName = mb_strtolower(Pluralizer::plural($name));

        $controllerName = $name . 'Controller';
        $controllerFile = $version ? "Http/Controllers/{$version}/{$controllerName}.php" : "Http/Controllers/{$controllerName}.php";

        $resourceName = $name . 'Resource';
        $resourcePathName = $version ? $version . '\\' . $resourceName : $resourceName;

        $namespace = $version ? "namespace App\Http\Controllers\\" . $version . ";\n" : "namespace App\Http\Controllers;\n";

        Artisan::call($version ? "make:resource {$version}/{$name}Resource" : "make:resource {$name}Resource");

        Helpers::createDir($version ? 'Http/Controllers/' . $version : 'Http/Controllers');

        if (file_exists(app_path("{$controllerFile}"))) {
            $console->info($controllerName . ' already exists');
        } else {
            $console->text('Creating ' . $controllerName . '...');
            $file = Helpers::createFile($controllerFile);
            $lines = [
                "<?php \n",
                "\n",
                $namespace,
                "\n",
                "use App\Http\Controllers\Controller;\n",
                "use App\Http\Resources\\{$resourcePathName};\n",
                "use App\Models\\{$name};\n",
                "use Illuminate\Http\JsonResponse;\n",
                "use Illuminate\Http\Request;\n",
                "use Illuminate\Support\Facades\DB;\n",
                "use Wame\ApiResponse\Helpers\ApiResponse;\n",
                "use Wame\LaravelCommands\Utils\Validator;\n",
                "\n",
                "/**\n",
                " * @group {$name}\n",
                " */\n",
                "class {$controllerName} extends Controller\n",
                "{\n",
                "    /**\n",
                "     * GET {$name} index\n",
                "     *\n",
                "     * @bodyParam page int Pagination page Example: 1\n",
                "     * @bodyParam per_page int Pagination per page Example: 20\n",
                "     *\n",
                "     * @param Request \$request\n",
                "     * @return JsonResponse\n",
                "     */\n",
                "    public function index(Request \$request): JsonResponse\n",
                "    {\n",
                "        try {\n",
                "            \$validator = Validator::code()->validate(\$request->all(), [\n",
                "                'page' => 'integer|min:1',\n",
                "                'per_page' => 'integer|min:1'\n",
                "            ]);\n",
                "            if (\$validator) return \$validator;\n",
                "\n",
                "            \$perPage = \$request->get('per_page', config('wame-commands.per_page', 20));\n",
                "\n",
                "            \$data = {$name}::paginate(\$perPage);\n",
                "\n",
                '            return ApiResponse::collection($data, ' . $resourceName . "::class)->code()->response();\n",
                "        } catch (\Exception \$e) {\n",
                "            return ApiResponse::code()->message(\$e->getMessage())->response(500);\n",
                "        }\n",
                "    }\n",
                "\n",
                "    /**\n",
                "     * POST {$name} store\n",
                "     *\n",
                "     * @param Request \$request\n",
                "     * @return JsonResponse\n",
                "     */\n",
                "    public function store(Request \$request): JsonResponse\n",
                "    {\n",
                "        try {\n",
                "            \$validator = Validator::code()->validate(\$request->all(), [\n",
                "\n",
                "            ]);\n",
                "            if (\$validator) return \$validator;\n",
                "\n",
                "            DB::beginTransaction();\n",
                "\n",
                "            \$entity = {$name}::create([\n",
                "\n",
                "            ]);\n",
                "\n",
                "            DB::commit();\n",
                "\n",
                "            return ApiResponse::data({$resourceName}::make(\$entity))->code()->response(201);\n",
                "        } catch (\Exception \$e) {\n",
                "            DB::rollBack();\n",
                "            return ApiResponse::code()->message(\$e->getMessage())->response(500);\n",
                "        }\n",
                "    }\n",
                "\n",
                "    /**\n",
                "     * GET {$name} show\n",
                "     *\n",
                "     * @urlParam id {$idType} required {$name} id Example: 01gsa40bvafp2tewxh67bbphw2\n",
                "     *\n",
                "     * @param string \$id\n",
                "     * @return JsonResponse\n",
                "     */\n",
                "    public function show(string \$id): JsonResponse\n",
                "    {\n",
                "        try {\n",
                "            \$validator = Validator::code()->validate(['id' => \$id], [\n",
                "                'id' => '{$idType}|required|exists:{$tableName},id,deleted_at,NULL',\n",
                "            ]);\n",
                "            if (\$validator) return \$validator;\n",
                "\n",
                "            \$entity = {$name}::find(\$id);\n",
                "\n",
                "            return ApiResponse::data({$resourceName}::make(\$entity))->code()->response();\n",
                "        } catch (\Exception \$e) {\n",
                "            return ApiResponse::code()->message(\$e->getMessage())->response(500);\n",
                "        }\n",
                "    }\n",
                "\n",
                "    /**\n",
                "     * PUT {$name} update\n",
                "     *\n",
                "     * @urlParam id {$idType} required {$name} id Example: 01gsa40bvafp2tewxh67bbphw2\n",
                "     *\n",
                "     * @param string \$id\n",
                "     * @param Request \$request\n",
                "     * @return JsonResponse\n",
                "     */\n",
                "    public function update(string \$id, Request \$request): JsonResponse\n",
                "    {\n",
                "        try {\n",
                "            \$validate = \$request->all();\n",
                "            \$validate['id'] = \$id;\n",
                "\n",
                "            \$validator = Validator::code()->validate(\$validate, [\n",
                "                'id' => '{$idType}|required|exists:{$tableName},id,deleted_at,NULL',\n",
                "            ]);\n",
                "            if (\$validator) return \$validator;\n",
                "\n",
                "            \$entity = {$name}::find(\$id);\n",
                "\n",
                "            DB::beginTransaction();\n",
                "\n",
                "            \$entity->update([\n",
                "\n",
                "            ]);\n",
                "\n",
                "            DB::commit();\n",
                "\n",
                "            return ApiResponse::data({$resourceName}::make(\$entity))->code()->response();\n",
                "        } catch (\Exception \$e) {\n",
                "            DB::rollBack();\n",
                "            return ApiResponse::code()->message(\$e->getMessage())->response(500);\n",
                "        }\n",
                "    }\n",
                "\n",
                "    /**\n",
                "     * DELETE {$name} delete\n",
                "     *\n",
                "     * @urlParam id {$idType} required {$name} id Example: 01gsa40bvafp2tewxh67bbphw2\n",
                "     *\n",
                "     * @param string \$id\n",
                "     * @return JsonResponse\n",
                "     */\n",
                "    public function delete(string \$id): JsonResponse\n",
                "    {\n",
                "        try {\n",
                "            \$validator = Validator::code()->validate(['id' => \$id], [\n",
                "                'id' => '{$idType}|required|exists:{$tableName},id,deleted_at,NULL',\n",
                "            ]);\n",
                "            if (\$validator) return \$validator;\n",
                "\n",
                "            DB::beginTransaction();\n",
                "\n",
                "            \$entity = {$name}::find(\$id);\n",
                "            \$entity->delete();\n",
                "\n",
                "            DB::commit();\n",
                "\n",
                "            return ApiResponse::data({$resourceName}::make(\$entity))->code()->response();\n",
                "        } catch (\Exception \$e) {\n",
                "            DB::rollBack();\n",
                "            return ApiResponse::code()->message(\$e->getMessage())->response(500);\n",
                "        }\n",
                "    }\n",
                "}\n",
            ];

            fwrite($file, implode('', $lines));
            fclose($file);

            $console->info("Created {$controllerName}");
        }
    }
}
