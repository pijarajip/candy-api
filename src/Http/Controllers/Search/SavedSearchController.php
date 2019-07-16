<?php

namespace GetCandy\Api\Http\Controllers\Search;

use Illuminate\Http\Request;
use GetCandy\Api\Http\Controllers\BaseController;
use GetCandy\Api\Http\Requests\Search\StoreRequest;
use GetCandy\Api\Http\Resources\Search\SavedSearchResource;
use GetCandy\Api\Http\Resources\Search\SavedSearchCollection;

class SavedSearchController extends BaseController
{
    public function store(StoreRequest $request)
    {
        $search = app('api')->savedSearch()->store($request->all());
        return new SavedSearchResource($search);
    }

    public function getByType($type, Request $request)
    {
        $result = app('api')->savedSearch()->getByType($type);
        return SavedSearchCollection($result);
    }

    public function destroy($id)
    {
        try {
            $result = app('api')->savedSearch()->delete($id);
        } catch (NotFoundHttpException $e) {
            return $this->errorNotFound();
        }
    }
}
