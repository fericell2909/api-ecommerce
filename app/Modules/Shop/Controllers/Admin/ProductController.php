<?php
namespace App\Modules\Shop\Controllers\Admin;

use App\Modules\Shop\Controllers\AppController as BaseController;
use \Illuminate\Http\Request;
use App\Modules\Shop\Models\Product;
use Illuminate\Support\Str;

class ProductController extends BaseController
{
	protected $product=null;
	protected $rules=null;
    protected $messages = null;
	protected $productFields = [
		'sku',
		'name',
		'description',
		'total_sales',
		'unit',
		'price',
		'discount',
		'quantity',
		'active'
	];

	function __construct()
	{
		$this->product = new Product;
		$this->rules = [
			'sku' => 'required|unique:'.(new Product)->getTable().',sku',
			'name' => 'required|min:5|max:50',
			'description' => 'required',
			//'short_description' => 'required|min:30|max:300',
			//'total_sales' =>
			//'unit' => 'required',
			'price' => 'required|min:1',
			'discount' => 'required|min:0',
			'quantity' => 'required|min:1',
			'active' => 'required|in:0,1'
		];
        $this->messages = [
            'sku.required' => 'El sku es requerido.',
            'sku.unique' => 'EL sku debe ser unico.',
		    'name.required' => 'El nombre es requerido.',
            'name.min'=> 'El nombre es menor al mínimo',
            'name.max'=> 'El nombre es mayor al máximo',
		    'description.required' => 'La descripción es requerida.',
            'price.required' => 'El precio es requerido.',
            'quantity.min' => 'El precio es menor al mínimo.',
            'discount.required' => 'El descuento es requerido.',
            'discount.min' => 'El descuento es menor al mínimo.',
            'quantity.required' => 'El stock es requerido.',
            'quantity.min' => 'EL stock es menor al mínimo.',
        ];

	}

	/**
	 * @method POST
	 * @link /shop/product
	 */
	public function create(Request $request)
	{
		$this->validate($request, $this->rules,$this->messages);
		$fields = $request->only($this->productFields);
		$fields['slug'] = Str::slug($fields['name']);
        $fields['unit'] = 1;

		$product = $this->product->create($fields);

		return response([
			'status'=>'success',
			'data'=> $product ,
		], 200);
	}

	/**
	 * @method PUT
	 * @link /shot/product/{id}
	 */
	public function update(Request $request, $id)
	{
		$rules = collect($this->rules);
        $rules->forget('sku');

		$this->validate($request, $rules->all(),$this->messages);
		$fields = $request->only($this->productFields);
		$fields['slug'] = Str::slug($fields['name']);

		try {
			$product = $this->product->find($id);
			if ($product == NULL)
				throw new \Exception("Not found", 404);

			$product->update($fields);

			return response([
				'status'=>'success',
				'data'=> $product ,
			],200);
		} catch (\Exception $e) {
			return $this->serverError($e);
		}
	}


	/**
	 * @method GET
	 * @link /shot/product/{id}
	 */
	public function show($id)
	{

		try {
			$product = $this->product->with(['gallery','category','relateds'])->find($id);

			if ($product ) {

                return response([
                    'status'=>'success',
                    'data'=> $product ,
                ],200);

            } else {
                return response([
                    'status'=>'success',
                    'data'=> [] ,
                ],200);
            }



		} catch (\Exception $e) {
			return $this->serverError($e);
		}
	}


	public function delete(Request $request)
	{
		return response([
			'status'=>'success',
			'data'=>[],
		],200);
	}


	public function list(Request $request)
	{

        if($request->filled('search')){

            $list = $this->product::where('sku','like','%' . $request->search . '%')
                        ->orWhere('name','like','%' . $request->search . '%')
                        ->orWhere('id','=',$request->search )
                        ->orderBy('id','desc')
                        ->with(['gallery','category'])->paginate(12);

        } else {
            $list = $this->product->orderBy('id','desc')->with(['gallery','category'])->paginate(12);
        }

		$data = array_merge(
			['status'=>'success',],
			$list->toArray()
		);
		return response($data,200);
	}

    public function active(Request $request)
	{
		$p = Product::findOrFail($request->product_id);
		$p->active=$request->active;
		$p->save();
		return response([
			'status'=>'success',
			'data'=>$request->all(),
		],200);
	}
}
