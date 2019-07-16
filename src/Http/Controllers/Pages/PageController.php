<?php

namespace GetCandy\Api\Http\Controllers\Pages;

use GetCandy\Api\Http\Controllers\BaseController;
use GetCandy\Api\Http\Resources\Pages\PageResource;
use GetCandy\Api\Http\Resources\Pages\PageCollection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PageController extends BaseController
{
    public function index()
    {
        $pages = app('api')->pages()->getPaginatedData();
        return new PageCollection($pages);
    }

    /**
     * Handles the request to show a currency based on it's hashed ID.
     * @param  string $id
     * @return Json
     */
    public function show($channel, $lang, $slug = null)
    {
        try {
            $page = app('api')->pages()->findPage($channel, $lang, $slug);
        } catch (ModelNotFoundException $e) {
            return $this->errorNotFound();
        }
        return new PageResource($page);
    }
}
