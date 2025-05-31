<?php

namespace App\Modules\Shop\Controllers\Overt;

use App\Modules\Shop\Controllers\AppController as BaseController;
use Illuminate\Http\Request;

use App\Modules\Shop\Models\Product;
use App\Modules\Shop\Models\Category;
use App\Modules\Shop\Models\CategoriesProducts;
use App\Modules\Shop\Resources\ProductsByCategory;
use App\Modules\Shop\Resources\ProductWithRelated;
use App\Modules\Shop\Resources\ProductCollection;

class ProductPublicController extends BaseController
{
	protected $product=null;
	protected $rules=null;

	function __construct()
	{
		$this->product = new Product;
		$this->rules = [
		];
	}

	/**
	 * Parametros en request
	 * 	- rows => Cantidad de registros
	 *  - category_slug => Categoria (Padre o hijo)
	 *  - search => filtra por el nombre del producto
	 */
    /**
    * @OA\GET(
    *     path="/api/shop/product/list",
    *     tags={"Ecommerce Público"},
    *     summary="Listar los Productos",
    *     description="Listar los Productos",
    *     operationId="ListProduct",
    *     security={{"bearer":{}}},
    *      @OA\Parameter(name="category_slug", description="category_slug", required=false, in="query", @OA\Schema(type="string")),
    *      @OA\Parameter(name="search", description="search", required=false, in="query", @OA\Schema(type="string")),
    *      @OA\Parameter(name="page", description="page", required=false, in="query", @OA\Schema(type="integer", default=1)),
    *      @OA\Parameter(name="rows", description="rows", required=false, in="query", @OA\Schema(type="integer", default=12)),
    *      @OA\Response(response=200, description="Datos encontrados exitosamente.", @OA\JsonContent(ref="#/components/schemas/ResponseProduct200PaginateSchema")),
    *      @OA\Response(response=401, description="Acceso no Autenticado.", @OA\JsonContent(ref="#/components/schemas/ResponseGeneric401Schema")),
    *      @OA\Response(response=403, description="Acceso restringido.", @OA\JsonContent(ref="#/components/schemas/ResponseGeneric403Schema")),
    *      @OA\Response(response=404, description="Datos  no encontrados", @OA\JsonContent(ref="#/components/schemas/ResponseGeneric404Schema")),
    *      @OA\Response(response=500, description="Error Interno del Servidor.", @OA\JsonContent(ref="#/components/schemas/ResponseGeneric500Schema"))
    * )
    */
	public function list(Request $request)
	{
		$rows = $request->input('rows', 12 );
        $page = $request->input('page', 1);
        if ($rows < 1) $rows = 1;

		$cat_slug = $request->input('category_slug', NULL);
		$search = urldecode($request->input('search', NULL));

		try {
			// Se filtra por slug de categoria
			if ($cat_slug != NULL){
				$category = Category::where('slug', $cat_slug)->first();
				if ($category == NULL)
					throw new \Exception("Not found", 404);

				return ProductsByCategory::collection(
                    $category->productsByCategory()->paginate($rows, ['*'], 'page', $page)
                )->additional(['category_slug' => $cat_slug]);

			}

			$list = $this->product->with(['gallery','category'])->available();
			if ($search != NULL) $list->where('name', 'like', "%$search%");

			return new ProductCollection($list->paginate($rows, ['*'], 'page', $page));

			/*$data = array_merge(
				['status'=>'success'],
				$list->paginate($rows)->toArray()
			);
			return response($data,200);*/

		} catch (\Exception $e) {
			return $this->serverError($e);
		}

	}

    /**
    * @OA\GET(
    *     path="/api/shop/product/{param}/{value}",
    *     tags={"Ecommerce Público"},
    *     summary="Obtiene los datos del producto por Slug",
    *     description="Obtiene los datos del producto por Slug",
    *     operationId="showProduct",
    *     security={{"bearer":{}}},
    *      @OA\Parameter(name="param", description="param", required=true, in="path", @OA\Schema(type="string", enum={"slug"})),
    *      @OA\Parameter(name="value", description="value", required=true, in="path", @OA\Schema(type="string")),
    *      @OA\Response(response=200, description="Datos encontrados exitosamente.", @OA\JsonContent(ref="#/components/schemas/ResponseProduct200ShowSchema")),
    *      @OA\Response(response=401, description="Acceso no Autenticado.", @OA\JsonContent(ref="#/components/schemas/ResponseGeneric401Schema")),
    *      @OA\Response(response=403, description="Acceso restringido.", @OA\JsonContent(ref="#/components/schemas/ResponseGeneric403Schema")),
    *      @OA\Response(response=404, description="Datos  no encontrados", @OA\JsonContent(ref="#/components/schemas/ResponseGeneric404Schema")),
    *      @OA\Response(response=500, description="Error Interno del Servidor.", @OA\JsonContent(ref="#/components/schemas/ResponseGeneric500Schema"))
    * )
    */
	public function show($param,$value)
	{
		$p = $this->product
			->with(['gallery','category','relateds'])
			// ->available()
			->where([
				[$param,'=',$value]
			])
			->first();
        if ($p == null) {
            return response([
                'status' => false,
                'message' => 'La solicitud no ha sido encontrada en el servidor.'
            ], 404);
        }
    $data = ['product' => $p , 'related' => $p['relateds']];
    $data = array_merge(
        ['status' => $data ? 'success' : 'not_found'],
        ['data' => $data ? $data : null]
    );
    return response($data, 200);

    }
}
