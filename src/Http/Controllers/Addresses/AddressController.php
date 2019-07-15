<?php

namespace GetCandy\Api\Http\Controllers\Addresses;

use Illuminate\Http\Request;
use GetCandy\Api\Http\Controllers\BaseController;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use GetCandy\Api\Http\Resources\Addresses\AddressResource;

class AddressController extends BaseController
{
    public function update($id, Request $request)
    {
        try {
            $address = app('api')->addresses()->update($id, $request->all());
        } catch (ModelNotFoundException $e) {
            return $this->errorNotFound();
        }
        return new AddressResource($address);
    }

    public function store(Request $request)
    {
        try {
            $authUser = $request->user();
            if ($request->user_id && $authUser->hasRole('admin')) {
                $id = $request->user_id;
            } else {
                $id = $authUser->encodedId();
            }
            $user = app('api')->users()->getByHashedId($id);
        } catch (ModelNotFoundException $e) {
            return $this->errorNotFound();
        }
        $address = app('api')->addresses()->create($user, $request->all());

        return new AddressResource($address);
    }

    public function destroy($id)
    {
        try {
            $result = app('api')->addresses()->delete($id);
        } catch (ModelNotFoundException $e) {
            return $this->errorNotFound();
        }

        return $this->respondWithNoContent();
    }

    public function makeDefault($id, Request $request)
    {
        try {
            $address = app('api')->addresses()->makeDefault($id);
        } catch (ModelNotFoundException $e) {
            return $this->errorNotFound();
        }

        return new AddressResource($address);
    }

    public function removeDefault($id, Request $request)
    {
        try {
            $address = app('api')->addresses()->removeDefault($id);
        } catch (ModelNotFoundException $e) {
            return $this->errorNotFound();
        }

        return new AddressResource($address);
    }
}
