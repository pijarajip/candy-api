<?php

namespace GetCandy\Api\Http\Controllers\Shipping;

use Illuminate\Http\Request;
use GetCandy\Api\Http\Controllers\BaseController;
use GetCandy\Api\Http\Requests\Shipping\CreateRequest;
use GetCandy\Api\Http\Requests\Shipping\UpdateRequest;
use GetCandy\Api\Http\Shipping\ShippingMethodResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use GetCandy\Api\Http\Shipping\ShippingMethodCollection;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ShippingMethodController extends BaseController
{
    /**
     * Returns a listing of channels.
     * @return Json
     */
    public function index(Request $request)
    {
        $methods = app('api')->shippingMethods()->getPaginatedData($request->per_page, $request->current_page);
        return new ShippingMethodCollection($methods);
    }

    /**
     * Handles the request to show a shipping method based on it's hashed ID.
     * @param  string $id
     * @return Json
     */
    public function show($id)
    {
        try {
            $method = app('api')->shippingMethods()->getByHashedId($id);
        } catch (ModelNotFoundException $e) {
            return $this->errorNotFound();
        }
        return new ShippingMethodResource($method);
    }

    /**
     * Handles the request to create a new channel.
     * @param  CreateRequest $request
     * @return Json
     */
    public function store(CreateRequest $request)
    {
        $result = app('api')->shippingMethods()->create($request->all());
        return new ShippingMethodResource($result);
    }

    public function update($id, UpdateRequest $request)
    {
        try {
            $result = app('api')->shippingMethods()->update($id, $request->all());
        } catch (NotFoundHttpException $e) {
            return $this->errorNotFound();
        }
        return new ShippingMethodResource($result);
    }

    public function updateZones($id, Request $request)
    {
        $method = app('api')->shippingMethods()->updateZones($id, $request->all());
        return new ShippingMethodResource($method);
    }

    public function updateUsers($id, Request $request)
    {
        $method = app('api')->shippingMethods()->updateUsers($id, $request->users);

        return $this->respondWithItem($method, new ShippingMethodTransformer);
    }

    public function deleteUser($methodId, $userId)
    {
        $method = app('api')->shippingMethods()->deleteUser($methodId, $userId);

        return $this->respondWithItem($method, new ShippingMethodTransformer);
    }

    public function destroy($methodId)
    {
        try {
            $result = app('api')->shippingMethods()->delete($methodId);
        } catch (NotFoundHttpException $e) {
            return $this->errorNotFound();
        }

        return $this->respondWithSuccess();
    }
}
