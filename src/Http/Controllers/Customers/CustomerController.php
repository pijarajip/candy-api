<?php

namespace GetCandy\Api\Http\Controllers\Customers;

use Illuminate\Http\Request;
use GetCandy\Api\Http\Controllers\BaseController;
use GetCandy\Api\Http\Requests\Users\UserResource;
use GetCandy\Api\Http\Requests\Customers\CreateRequest;

class CustomerController extends BaseController
{
    /**
     * Shows all the customers.
     *
     * @return array
     */
    public function index(Request $request)
    {
        $customers = app('api')->customers()->getPaginatedData(
            $request->length,
            $request->page,
            $request->keywords
        );

        return $this->respondWithCollection($customers, new UserTransformer);
    }

    public function show($id, Request $request)
    {
        $customer = app('api')->customers()->getByHashedId($id);
        return new UserResource($customer);
    }

    /**
     * Handles request to store a customer.
     *
     * @param CreateRequest $request
     *
     * @return array
     */
    public function store(CreateRequest $request)
    {
        $customer = app('api')->customers()->register($request->all());

        return new UserResource($customer);
    }
}
