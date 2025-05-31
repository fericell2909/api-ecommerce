<?php

namespace App\Modules\Shop\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ProductCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}

?>
