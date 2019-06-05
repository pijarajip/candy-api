<?php

namespace GetCandy\Api\Http\Controllers;

use Illuminate\Routing\Controller;
use GetCandy\Api\Core\Traits\Fractal;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use GetCandy\Api\Core\Cache\CacheTagger;
use GetCandy\Api\Core\Cache\ResponseCache;

class BaseController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, Fractal;

    protected $cacheTagger;
    protected $responseCache;

    public function __construct(CacheTagger $cacheTagger, ResponseCache $responseCache)
    {
        $this->cacheTagger = $cacheTagger;
        $this->responseCache = $responseCache;
    }

    /**
     * Parses included fields into an array.
     *
     * @param string $request
     * @return void
     */
    protected function parseIncludedFields($request)
    {
        if (! $request->fields) {
            return [];
        }

        return explode(',', $request->fields);
    }
}
