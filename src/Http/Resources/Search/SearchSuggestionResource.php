<?php

namespace GetCandy\Api\Http\Resources\Shipping;

use GetCandy\Api\Http\Resources\AbstractResource;
use GetCandy\Api\Http\Resources\Routes\RouteCollection;
class SearchSuggestionResource extends AbstractResource
{
    public function payload()
    {
        return [
            'id' => $this->encoded_id,
            'name' => $this->name,
            'payload' => $this->payload,
        ];
    }

    public function includes()
    {
        return [
            'routes' => new RouteCollection($this->whenLoaded('routes')),
        ];
    }
}
