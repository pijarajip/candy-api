<?php

namespace GetCandy\Api\Http\Controllers\Associations;

use Illuminate\Http\Request;
use GetCandy\Api\Http\Controllers\BaseController;
use GetCandy\Api\Http\Resources\Associations\AssociationGroupCollection;

class AssociationGroupController extends BaseController
{
    /**
     * Returns a listing of channels.
     * @return Json
     */
    public function index(Request $request)
    {
        $groups = app('api')->associationGroups()->getPaginatedData();
        return new AssociationGroupCollection($groups, $this->parseIncludedFields($request));
    }
}
