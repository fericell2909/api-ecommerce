<?php
namespace App\Modules\Shop\Controllers\Admin;

use App\Modules\Shop\Controllers\AppController as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Modules\Shop\Models\ProductEvaluation;

class EvaluationController extends BaseController
{
	public function list(Request $request)
	{
        if($request->filled('search')){

            $list = ProductEvaluation::where('email','like','%' . $request->search . '%')
                        ->orWhere('commentary','like','%' . $request->search . '%')
                        ->orderBy('created_at','desc')
                        ->with(['product'])
                        ->paginate(10)->toArray();

        } else {
            $list = ProductEvaluation::orderBy('created_at','desc')
                                    ->with(['product'])
                                    ->paginate(10)->toArray();
        }



		$data = ['status' => 'success' ,'data' => $list];

		return response($data,200);
	}
	public function delete($id)
	{
		$pe=ProductEvaluation::find($id);
		$data = ['status' => 'success'];
		if ($pe) {
			$pe->delete();
			$data['data']=['meessage'=>'Eliminado con exito!'];
		}else{
			$data['status']='error';
			$data['data']=['meessage'=>'Evaluación no encontrada'];
		}
		return response($data,200);
	}
}
