<?php

namespace GetCandy\Api\Http\Controllers\Routes;

use Illuminate\Http\Request;
use GetCandy\Api\Core\Routes\RouteCriteria;
use GetCandy\Api\Http\Controllers\BaseController;
use GetCandy\Api\Http\Requests\Routes\UpdateRequest;
use GetCandy\Api\Http\Resources\Routes\RouteResource;
use GetCandy\Api\Http\Resources\Routes\RouteCollection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use GetCandy\Api\Exceptions\MinimumRecordRequiredException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class RouteController extends BaseController
{
    public function index()
    {
        $routes = app('api')->routes()->getPaginatedData();
        return new RouteCollection($routes);
    }

    /**
     * Handles the request to show a route based on it's hashed ID.
     * @param  string $slug
     * @return Json
     */
    public function show(Request $request, RouteCriteria $routes)
    {
        try {
            $route = $routes->slug($request->slug)->path($request->path)->includes($request->includes)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return $this->errorNotFound();
        }

        return new RouteResource($route);
    }

    /**
     * Update a route.
     *
     * @param string $id
     * @param UpdateRequest $request
     *
     * @return Json
     */
    public function update($id, UpdateRequest $request)
    {
        try {
            $route = app('api')->routes()->update($id, $request->all());
        } catch (ModelNotFoundException $e) {
            return $this->errorNotFound();
        }

        return new RouteResource($route);
    }

    public function destroy($id)
    {
        try {
            $result = app('api')->routes()->delete($id);
            return $this->respondWithNoContent();
        } catch (MinimumRecordRequiredException $e) {
            return $this->errorUnprocessable($e->getMessage());
        } catch (NotFoundHttpException $e) {
            return $this->errorNotFound();
        }
    }
}
