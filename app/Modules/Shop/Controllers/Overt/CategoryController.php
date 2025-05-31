<?php
namespace App\Modules\Shop\Controllers\Overt;


use App\Modules\Shop\Controllers\AppController as BaseController;
use Illuminate\Http\Request;
use App\Modules\Shop\Models\Category;

class CategoryController extends BaseController
{
	protected $category=null;
	protected $rules=null;

	function __construct()
	{
		$this->category = new Category;
		$this->rules = [
		];
	}

    /**
    * @OA\GET(
    *     path="/api/shop/categories",
    *     tags={"Ecommerce Público"},
    *     summary="Obtiene los datos de un Usuario por su ID.",
    *     description="Obtiene los datos de un Usuario por su ID. Se necesita estar autenticado para realizar la solicitud. El Rol Supervisor Tributario tiene acceso a este endpoint.",
    *     operationId="listCategories",
    *     security={{"bearer":{}}},
    *      @OA\Response(response=200, description="Datos encontrados exitosamente.", @OA\JsonContent(ref="#/components/schemas/ResponseUser200ShowSchema")),
    *      @OA\Response(response=401, description="Acceso no Autenticado.", @OA\JsonContent(ref="#/components/schemas/ResponseGeneric401Schema")),
    *      @OA\Response(response=403, description="Acceso restringido.", @OA\JsonContent(ref="#/components/schemas/ResponseGeneric403Schema")),
    *      @OA\Response(response=404, description="Datos  no encontrados", @OA\JsonContent(ref="#/components/schemas/ResponseGeneric404Schema")),
    *      @OA\Response(response=500, description="Error Interno del Servidor.", @OA\JsonContent(ref="#/components/schemas/ResponseGeneric500Schema"))
    * )
    */
	public function list(Request $request)
	{
		$list = $this->category->whereNull('parent_id')
            ->orderBy('parent_id','desc')
			->with(['allChildren'])
			->get();
		$data = [
			'status'=>'success',
			'data'=>$list->toArray()
		];
		return response($data,200);
	}

	public function show($slug){
		try {
			$data = $this->category->with(['allChildren'])
				->where('slug', $slug)->first();
			if ($data == NULL) throw new \Exception("Not found", 404);

			return ['data' => $data ];
		} catch (\Exception $e) {
			return $this->serverError($e);
		}

	}
}
