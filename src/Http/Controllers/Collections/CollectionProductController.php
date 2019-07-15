<?php

namespace GetCandy\Api\Http\Controllers\Collections;

use GetCandy\Api\Http\Controllers\BaseController;
use GetCandy\Api\Http\Resources\Collections\CollectionCollection;
use GetCandy\Api\Http\Requests\Collections\Products\UpdateRequest;

class CollectionProductController extends BaseController
{
    /**
     * @param                                                       $product
     * @param \GetCandy\Api\Http\Requests\Products\CreateUrlRequest $request
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function store($collection, UpdateRequest $request)
    {
        $result = app('api')->collections()->syncProducts($collection, $request->products);

        return new CollectionCollection($result);
    }
}
