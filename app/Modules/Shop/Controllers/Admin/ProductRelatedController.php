<?php
namespace App\Modules\Shop\Controllers\Admin;

use App\Modules\Shop\Controllers\AppController as BaseController;
use Illuminate\Http\Request;

use App\Modules\Shop\Models\Product;
use App\Modules\Shop\Models\ProductRelated;

class ProductRelatedController extends BaseController
{
	protected $product=null;
	protected $rules=null;

	function __construct()
	{
		$this->product = new Product;
		$this->rules = [
			'products_for_relate'=>'array',
			'products_for_relate.*'=>'integer|exists:'.$this->product->getTable().',id',
		];
	}

	public function syncList($id,Request $request)
	{
		$this->validate($request, $this->rules);
		$product = $this->product->findOrFail($id);
		$product->relateds()->sync($request->products_for_relate);

		$data = [
			'status'=>'success',
			'data'=>[
				'message'=>'asociado exitosamente!',
				'relateds'=>$product->relateds,

			],
		];
		return response($data,200);
	}
}
