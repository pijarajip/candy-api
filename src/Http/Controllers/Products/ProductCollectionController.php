<?php

namespace GetCandy\Api\Http\Controllers\Products;

use GetCandy\Api\Http\Controllers\BaseController;
use GetCandy\Api\Http\Resources\Collections\CollectionResource;
use GetCandy\Api\Http\Requests\Products\UpdateCollectionsRequest;
use GetCandy\Api\Http\Resources\Collections\CollectionCollection;

class ProductCollectionController extends BaseController
{
    /**
     * @param $product
     * @param UpdateCollectionsRequest $request
     * @return array|\Illuminate\Http\Response
     */
    public function update($product, UpdateCollectionsRequest $request)
    {
        try {
            $collections = app('api')->productCollections()->update($product, $request->all());
        } catch (HttpException $e) {
            return $this->errorUnprocessable($e->getMessage());
        } catch (NotFoundHttpException $e) {
            return $this->errorNotFound();
        }
        return new CollectionCollection($collections);
    }

    /**
     * Deletes a products collection.
     * @param  int        $productId
     * @param  int        $collectionId
     * @return array|\Illuminate\Http\Response
     */
    public function destroy($productId, $collectionId)
    {
        $result = app('api')->productCollections()->delete($productId, $collectionId);

        if ($result) {
            return response()->json([
                'message' => 'Successfully removed collection from product',
            ], 202);
        }

        return response()->json('Error', 500);
    }
}
