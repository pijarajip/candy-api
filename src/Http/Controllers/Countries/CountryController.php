<?php

namespace GetCandy\Api\Http\Controllers\Countries;

use Illuminate\Http\Request;
use GetCandy\Api\Http\Controllers\BaseController;
use GetCandy\Api\Http\Resources\Countries\CountryCollection;

class CountryController extends BaseController
{
    /**
     * Returns a listing of channels.
     * @return Json
     */
    public function index(Request $request)
    {
        $collection = app('api')->countries()->all();
        return new CountryCollection($collection);
    }
}
