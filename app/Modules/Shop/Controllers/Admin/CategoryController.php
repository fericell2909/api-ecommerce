<?php
namespace App\Modules\Shop\Controllers\Admin;

use App\Modules\Shop\Controllers\AppController as BaseController;
use App\Modules\Shop\Models\CategoriesProducts;
use \Illuminate\Http\Request;
use App\Modules\Shop\Models\Category;
use Illuminate\Support\Str;
use App\Modules\Shop\Models\Product;

class CategoryController extends BaseController
{
	protected $category=null;
	protected $rules=null;
	protected $categoryFields = [
		'created_by',
		'parent_id',
		'order',
		'icon',
		'name',
		'slug'
	];

	function __construct()
	{
		$this->category = new Category;
		$this->rules = [
			'name' => 'required|min:5|max:50'
		];
	}

	/**
	 * @method POST
	 * @link /shop/categories
	 */
	public function create(Request $request)
	{
		$this->validate($request, $this->rules);
		$fields = $request->only($this->categoryFields);
		$fields['slug'] = Str::slug($fields['name']);

        if($request->parent_id <> null) {
            $category = Category::where('id',$request->parent_id)->first();

        }

		$category = $this->category->create($fields);

		return response([
			'status'=>'success',
			'data'=> $category ,
		], 200);
	}

	/**
	 * @method PUT
	 * @link /shot/categories/{id}
	 */
	public function update(Request $request, $id)
	{
		$this->validate($request, $this->rules);
		$fields = $request->only($this->categoryFields);
		$categoryFields['slug'] = Str::slug($fields['name']);

        if($request->parent_id <> null) {
            $category = Category::where('id',$request->parent_id)->first();

        }

		try {
			$category = $this->category->find($id);
			if ($category == NULL)
				throw new \Exception("Not found", 404);

			if (isset($fields['parent_id'])){
				$parent = $this->category->find($fields['parent_id']);
				if ($parent == NULL)
					throw new \Exception("Parent Category not found", 500);

				if ($parent->id === $category->id)
					throw new \Exception("Category and Parent Category are the same", 500);


			}

			$category->update($fields);

			return response([
				'status'=>'success',
				'data'=> $category ,
			],200);
		} catch (\Exception $e) {
			return $this->serverError($e);
		}
	}


	/**
	 * @method GET
	 * @link /shot/categories/{id}
	 */
	public function show($id)
	{
		try {
			$category = $this->category->with(['allChildren'])->find($id);
			if ($category == NULL)
				throw new \Exception("Not found", 404);

			return response([
				'status'=>'success',
				'data'=> $category ,
			],200);
		} catch (\Exception $e) {
			return $this->serverError($e);
		}
	}


	public function delete(Request $request, $id)
	{

        try {
			$category = $this->category->find($id);
			if ($category == NULL)
                return response([
                    'status'=>'error',
                    'message' => 'La Categoría no existe',
                    'data'=> $category ,
                ],200);

			$rows = CategoriesProducts::where('category_id',$id)->get();

            if(count($rows) == 0){
                $category->delete();
                return response([
                    'status'=>'success',
                    'message' => 'La Categoría ha sido eliminada correctamente.',
                    'data'=> $category ,
                ],200);
            } else {
                return response([
                    'status'=>'error',
                    'message' => 'La Categoría tiene productos asociados. No se puede eliminar.',
                    'data'=> $category ,
                ],200);
            }

		} catch (\Exception $e) {
			return response([
                'status'=>'error',
                'message' => 'Ha ocurrido un error. Por favor intente en unos minutos.',
                'data'=> $category ,
            ],200);
		}

	}


	public function list(Request $request)
	{
        if($request->filled('search')){

            $list = $this->category::where('name','like' , '%'.$request->search.'%')->orderBy('id','desc')
                    ->orderBy('order','asc')
                    ->orderBy('parent_id','desc')
                    ->with(['allChildren','parent'])
                    ->paginate(10)->toArray();

        } else {
            $list = $this->category->orderBy('id','desc')
                ->orderBy('order','asc')
                ->orderBy('parent_id','desc')
                ->with(['allChildren','parent'])
                ->paginate(10)->toArray();
        }


        $data = array();

        foreach($list['data'] as $d) {

            if($d['parent_id'] == null){

                $d['parent_id_description'] = 'Sin Categoría Padre';
                $d['parent_id'] = null;

            } else {
                $d['parent_id_description'] = $d['parent']['name'];
                $d['parent_id'] = $d['parent']['id'];
            }
            if($d['order'] == null){
                $d['order'] = 0;
            }

            array_push($data,$d);

        }

        $list['data']= $data;

		$data['status'] = 'success';
		$data['data'] = $list;
		return response($data,200);
	}

    public function listMain()
    {
        $list = $this->category->whereNull('parent_id')
			->orderBy('id','desc')
			->get();

        $result = [];

        array_push($result,["value" => null, "label"=> 'Sin Categoría Padre']);

        foreach($list as $d) {

            array_push($result,["value" => $d['id'], "label"=> $d['name']]);

        }
        $data['status'] = 'success';
        $data['data'] = $result;

        return response($data,200);

    }

    public function options(Request $request)
	{
		$data=$this->category->optionsCascade()
			->get()->toArray();
        $result = array();

        foreach($data as $r) {

            $r['children'] = $r['children2'];
            array_push($result,$r);


        }
		return response([
			'status'=>'success',
			'data'=>$result,
		],200);
	}

    public function selectoptions() {

        $data=$this->category->optionsCascade()
            ->get()->toArray();
        $result = array();
        //dd($data);
        array_push($result,["value" => null, "label"=> 'Sin Categoría' , 'role' => 'Sin Categoría']);

        foreach($data as $r) {
            $r['role'] = $r['label'];
                array_push($result,$r);

            if(count($r['children2']) > 0){

                foreach($r['children2'] as $rdet){
                    $rdet['role'] = $r['label'];//$this->getName($data,['parent_id']);
                    $rdet['label'] = $r['label'] . ' - ' .$rdet['label'];
                    array_push($result,$rdet);
                }
            }

        }
        return response([
            'status'=>'success',
            'data'=>$result,
        ],200);

    }
    private function getName($records,$category) {
        $name = '';

        foreach($records as  $r){
            if($r['value'] == $category){
                $name = $r['label'];
            }
        }

        return $name;
    }
    public function product(Request $request)
	{
		$prodcut=new Product;
		$this->validate($request,[
			'product_id' => 'integer|exists:'.$prodcut->getTable().',id',
			'categories[*]' => 'integer|exists:'.$this->category->getTable().',id',
		]);
		$prodcut=$prodcut->find($request->product_id);
		$prodcut->category()->sync($request->categories);
		return response([
			'status'=>'success',
			'data'=>$prodcut,
		],200);
	}

}
