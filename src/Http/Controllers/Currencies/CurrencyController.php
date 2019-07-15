<?php

namespace GetCandy\Api\Http\Controllers\Currencies;

use Illuminate\Http\Request;
use GetCandy\Api\Http\Controllers\BaseController;
use GetCandy\Exceptions\MinimumRecordRequiredException;
use GetCandy\Api\Http\Requests\Currencies\CreateRequest;
use GetCandy\Api\Http\Requests\Currencies\DeleteRequest;
use GetCandy\Api\Http\Requests\Currencies\UpdateRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use GetCandy\Api\Http\Resources\Currencies\CurrencyResource;
use GetCandy\Api\Http\Resources\Currencies\CurrencyCollection;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CurrencyController extends BaseController
{
    /**
     * Returns a listing of currencies.
     * @return Json
     */
    public function index(Request $request)
    {
        $paginator = app('api')->currencies()->getPaginatedData($request->per_page);
        return new CurrencyCollection($paginator);
    }

    /**
     * Handles the request to show a currency based on it's hashed ID.
     * @param  string $id
     * @return Json
     */
    public function show($id)
    {
        try {
            $currency = app('api')->currencies()->getByHashedId($id);
        } catch (ModelNotFoundException $e) {
            return $this->errorNotFound();
        }
        return new CurrencyResource($currency);
    }

    /**
     * Handles the request to create a new channel.
     * @param  CreateRequest $request
     * @return Json
     */
    public function store(CreateRequest $request)
    {
        $result = app('api')->currencies()->create($request->all());
        return CurrencyResource($result);
    }

    public function update($id, UpdateRequest $request)
    {
        try {
            $result = app('api')->currencies()->update($id, $request->all());
        } catch (MinimumRecordRequiredException $e) {
            return $this->errorUnprocessable($e->getMessage());
        } catch (NotFoundHttpException $e) {
            return $this->errorNotFound();
        }
        return CurrencyResource($result);
    }

    /**
     * Handles the request to delete a currency.
     * @param  string        $id
     * @param  DeleteRequest $request
     * @return Json
     */
    public function destroy($id, DeleteRequest $request)
    {
        try {
            $result = app('api')->currencies()->delete($id);
        } catch (MinimumRecordRequiredException $e) {
            return $this->errorUnprocessable($e->getMessage());
        } catch (NotFoundHttpException $e) {
            return $this->errorNotFound();
        }

        return $this->respondWithNoContent();
    }
}
