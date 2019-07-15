<?php

namespace GetCandy\Api\Http\Resources\Countries;

use GetCandy\Api\Http\Resources\AbstractResource;

class CountryGroupResource extends AbstractResource
{
    public function payload()
    {
        return [
            'id' => $this->encoded_id,
        ];
    }

    public function includes()
    {
        return [
            'set' => ['data' => new DiscountSetResource($this->whenLoaded('set'))],
        ];
    }
}
