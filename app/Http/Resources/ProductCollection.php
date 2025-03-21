<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ProductCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => $this->collection->map(function ($data) {
                return [
                    'id' => (int) $data->id,
                    'name' => $data->getTranslation('name'),
                    'slug' => $data->slug,
                    'thumbnail_image' => api_asset($data->thumbnail_img),
                    'base_price' => (float) product_base_price($data),
                    'base_discounted_price' => (float) product_discounted_base_price($data),
                    'stock' => $data->stock,
                    'unit' => $data->getTranslation('unit'),
                    'min_qty' => $data->min_qty,
                    'max_qty' => $data->max_qty,
                    'rating' => (float) $data->rating,
                    'earn_point' => (float) $data->earn_point,
                    'is_variant' => (int) $data->is_variant,
                    'variations' => $data->variations,
                    'description' => $data->description,
                    'brandName' => optional($data->brand)->getTranslation('name'),
                    'reference' => $data->reference,
                    'currency' => $data->currency,
                    'is_digital' => $data->digital == 1 ? true : false,
                ];
            })
        ];
    }

    public function with($request)
    {
        return [
            'success' => true,
            'status' => 200
        ];
    }
}
