<?php
namespace App\Modules\Shop\Controllers\Admin;

use App\Modules\Shop\Controllers\AppController as BaseController;
use \Illuminate\Http\Request;
use App\Modules\Shop\Models\Producttop;
use App\Modules\Auth\Models\User;
use Illuminate\Support\Str;

class ProducttopController extends BaseController
{
	protected $product=null;
	protected $rules=null;
    protected $messages = null;
	protected $productFields = [
		'created_by',
		'product_id',
		'oreder',
        'file_preview_id',
        'file_later_id'
	];

	function __construct()
	{
		$this->product = new Producttop;
		$this->rules = [
			'product_id' => 'required|unique:'.(new Producttop)->getTable().',product_id',
			'file_preview_id' => 'required',
            'file_later_id' => 'required'
		];
        $this->messages = [
            'product_id.required' => 'El producto es requerido.',
            'sku.unique' => 'EL producto ya existe.',
            'file_preview_id.required' => 'La primera imagen es requerida.',
            'file_later_id.required' => 'La segunda imagen es requerida.',
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

            $list = $this->product::with(['product','preview','later'])->paginate(12);

        } else {
            $list = $this->product->orderBy('id','desc')->with(['product','preview','later'])->paginate(12);
        }

		$data = array_merge(
			['status'=>'success',],
			$list->toArray()
		);
		return response($data,200);
	}

    public function select(Request $request) {

        $u=\Auth::user();
        $current_user = null;

        if($u){
            $current_user = User::where('id',$u->id)->first();
        }

        if($request->action == 1) {


            $this->product->WhereNotIn('product_id',$request->selecteds)->delete();

        } else {
            foreach($request->selecteds as $r) {

                $p = Producttop::where('product_id',$r)->get();

                if(count($p) <= 0) {
                    $this->product->create(['product_id' => $r , 'file_later_id' => 0 ,
                                            'file_preview_id' => 0 , 'oreder' => 0  ,
                                            'created_by' => $current_user->id ]);

                }
            }
        }


        return $this->show();


    }

    public function updateorder(Request $request) {


        $product = $this->product::where('id',$request->data['id'])->first();

        if($product) {

            $product->update(['oreder' => $request->data['order']]);

        }

        return $this->show();

    }

    public function show()
	{

		try {

			$product = $this->product->with('product','preview' ,'later')->get();

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

    public function listclient() {
        $product = $this->product::orderBy('oreder','asc')->with('product','preview' ,'later')->get();

        $result = array();

        foreach($product as $r) {

            if($r['file_preview_id'] > 0 && $r['file_later_id'] > 0) {
                array_push($result,$r);
            }

        }
			if ($result ) {

                return response([
                    'status'=>'success',
                    'data'=> $result ,
                ],200);

            } else {
                return response([
                    'status'=>'success',
                    'data'=> [] ,
                ],200);
            }
    }
}
