<?php

namespace App\Modules\Shop\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Modules\Shop\Models\CategoriesProducts;

class ProductFullDetail extends JsonResource
{
    public function toArray($request)
    {
    	return parent::toArray($request);
    }
}

?>
