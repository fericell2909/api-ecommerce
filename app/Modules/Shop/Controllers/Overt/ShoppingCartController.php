<?php

namespace App\Modules\Shop\Controllers\Overt;

use App\Modules\Shop\Controllers\AppController as BaseController;
use Illuminate\Http\Request;

use App\Modules\Shop\Models\Product;
use App\Modules\Shop\Models\ShoppingCart;

class ShoppingCartController extends BaseController
{
	protected $shoppingCart=null;
	protected $rules=null;

	function __construct()
	{
		$this->shoppingCart = new ShoppingCart;
		$this->rules = [
		];
	}
	public function cart(Request $request)
	{
		$validations = [
			"products" => "required",
		];
		$this->validate($request, $validations);
		if (is_array($request->products)) {
			$validations = [
				"products.*.id" => "required|integer|exists:".(new Product)->getTable().",id",
				"products.*.product_quantity" => "required|integer",
			];
			$this->validate($request, $validations);
		}
		$cart = $this->shoppingCart->where('owner_id',\Auth::id())->first();
		if($cart==null) {
			$cart = $this->shoppingCart->create([
				'owner_id'=>\Auth::id(),
				'key'=>bin2hex(openssl_random_pseudo_bytes(25)),
			]);
		}
		$list=[];
		$out_of_stock=[];
		if (is_array($request->products)) {
			foreach ($request->products as $key => $p) {
				$product = Product::where('id',$p['id'])->available()->with(['gallery'])->first();
				$data=[];
				if ($product) {
					if ($p['product_quantity']<=$product->quantity) {
						$list[$product->id]=[
							'product_quantity' => $p['product_quantity']
						];
					}else{
						$data['product_id']=$product->id;
						$data['status']='insufficient';
						$data['message']='No hay suficiente stock';
						if ($product->quantity==0) {
							$data['status']='out_of_stock';
							$data['message']='Stock agotado';
						}

						$out_of_stock[]=$data;
					}
				}else{
					$data['product_id']=(int)$p['id'];
					$data['status']='not_found';
					$data['message']='Producto no encontrado';
					$out_of_stock[]=$data;
				}
			}
		}
		$cart->products()->sync($list);
		$cart->products;
		return response([
			'status'=>'success',
			'data'=>[
				'cart'=>$cart,
				'info_stock'=>$out_of_stock
			],
		],200);
	}
}
