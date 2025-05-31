<?php

namespace App\Modules\Shop\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Modules\Shop\Models\CategoriesProducts;

class ProductWithRelated extends JsonResource
{
    public function toArray($request)
    {

    	$categories = collect($this->category);
    	$categoriesIds = $categories->map(function ($category, $key) {
   	 		return $category->id;
		});

        $miga = rand(0, count($categoriesIds) - 1);
        $categoryId = $categoriesIds[$miga];

    	$related = CategoriesProducts::select('product_id')
            ->where('category_id', $categoryId)
    		->where('product_id', '!=', $this->id)
            ->groupBy('product_id')
    		->orderBy('id', 'DESC')
    		->paginate(4);

        return [
        	'product' => parent::toArray($request),
        	'related' => ProductsByCategory::collection($related)
        ];
    }
}

?>
