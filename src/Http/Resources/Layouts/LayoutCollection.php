<?php

namespace GetCandy\Api\Http\Resources\Layouts;

use GetCandy\Api\Http\Resources\AbstractCollection;

class LayoutCollection extends AbstractCollection
{
    /**
     * The resource that this resource collects.
     *
     * @var string
     */
    public $collects = LayoutResource::class;

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
}
