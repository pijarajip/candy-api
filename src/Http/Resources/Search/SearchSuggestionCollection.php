<?php

namespace GetCandy\Api\Http\Resources\Search;

use GetCandy\Api\Http\Resources\AbstractCollection;

class ShippingSuggestionCollection extends AbstractCollection
{
    /**
     * The resource that this resource collects.
     *
     * @var string
     */
    public $collects = SearchSuggestionResource::class;

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection,
        ];
    }

    public function includes()
    {
        return [];
    }
}
