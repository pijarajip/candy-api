<?php

namespace GetCandy\Api\Http\Controllers\Layouts;

use GetCandy\Api\Http\Controllers\BaseController;
use GetCandy\Api\Http\Resources\Layouts\LayoutResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use GetCandy\Api\Http\Resources\Layouts\LayoutCollection;

class LayoutController extends BaseController
{
    public function index()
    {
        $layouts = app('api')->layouts()->getPaginatedData();
        return new LayoutCollection($layouts);
    }

    /**
     * Handles the request to show a layout based on it's hashed ID.
     * @param  string $id
     * @return Json
     */
    public function show($id)
    {
        try {
            $layout = app('api')->layouts()->getByEncodedId($id);
        } catch (ModelNotFoundException $e) {
            return $this->errorNotFound();
        }
        return new LayoutResource($layout);
    }
}
