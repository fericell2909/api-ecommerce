<?php

namespace App\Modules\Shop\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductsByCategory extends JsonResource
{
    public function toArray($request)
    {
        $this->product->gallery;
        $this->product->category;

        return $this->product;
    }
}

?>
