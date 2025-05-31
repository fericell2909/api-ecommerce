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
	public function list(Request $request)
	{
		$rows = $request->input('rows', 12 );
		$cat_slug = $request->input('category_slug', NULL);
		$search = urldecode($request->input('search', NULL));

		try {
			// Se filtra por slug de categoria
			if ($cat_slug != NULL){
				$category = Category::where('slug', $cat_slug)->first();
				if ($category == NULL)
					throw new \Exception("Not found", 404);

				return ProductsByCategory::collection(
					$category->productsByCategory()->paginate($rows)
				)->additional(['category_slug' => $cat_slug]);
			}

			$list = $this->product->with(['gallery','category'])->available();
			if ($search != NULL) $list->where('name', 'like', "%$search%");

			return new ProductCollection($list->paginate($rows));

			/*$data = array_merge(
				['status'=>'success'],
				$list->paginate($rows)->toArray()
			);
			return response($data,200);*/

		} catch (\Exception $e) {
			return $this->serverError($e);
		}

	}

	public function show($param,$value)
	{
		$p = $this->product
			->with(['gallery','category','evaluations','relateds'])
			->available()
			->where([
				[$param,'=',$value]
			])
			->first();

    $data = ['product' => $p , 'related' => $p['relateds']];
    $data = array_merge(
        ['status' => $data ? 'success' : 'not_found'],
        ['data' => $data ? $data : null]
    );
    return response($data, 200);

    }
}
