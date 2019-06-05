<?php

namespace GetCandy\Api\Core\Cache;

use Log;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Cache\CacheManager;
use GetCandy\Api\Core\CandyApi;

class ResponseCache
{
    /**
     * The cache connections this doesn't support
     *
     * @see https://laravel.com/docs/5.8/cache#cache-tags
     * @var array
     */
    protected $exceptConnections = [
        'file',
        'database',
    ];

    /**
     * The cache store in use
     *
     * @var string
     */
    protected $cacheType;

    /**
     * The cache tagger instance
     *
     * @var CacheTagger
     */
    protected $tagger;

    /**
     * The cache config
     *
     * @var array
     */
    protected $config;

    /**
     * The API manager instance
     *
     * @var CandyApi
     */
    protected $api;

    public function __construct(CacheTagger $tagger, CacheManager $cache, CandyApi $api)
    {
        $this->api = $api;
        $this->tagger = $tagger;
        $this->config = config('getcandy.endpoints.cache', []);
        $this->cacheType = $this->config['driver'] ?? 'default';

        if ($this->cacheType == 'default') {
            $this->cacheType = config('cache.default');
        }

        $this->cache = $cache->driver($this->cacheType);
    }

    /**
     * Handle the response caching of a resource and request
     *
     * @param JsonResource $resource
     * @param Request $request
     * @return json
     */
    public function handle(JsonResource $resource, Request $request)
    {
        // If it's a hub request, just bail straight away
        if ($this->api->isHubRequest()) {
            return $resource;
        }

        /**
         * If the cache store can't support tags, just return the resource
         */
        $invalid = in_array($this->cacheType, $this->exceptConnections);
        if ((!$this->config['enabled'] ?? false) || $invalid) {
            if ($invalid) {
                Log::error("Cache store [{$this->cacheType}] not supported for endpoint caching");
            }
            return $resource;
        }

        $tags = $this->tagger->for($resource->resource)->getTags();

        $response = $this->cache->tags($tags->toArray())->remember(
            $this->getCacheKey($request),
            ($this->config['lifetime'] ?? 86400),
            function () use ($resource) {
                return $resource->response()->getContent();
            }
        );

        return response($response)->header('Content-Type', 'application/json');
    }

    public function flush($tags)
    {
        return $this->cache->tags($tags)->flush();
    }

    /**
     * Gets the cache key via the request
     *
     * @param Request $request
     * @return string
     */
    protected function getCacheKey($request)
    {
        return md5($request->getRequestUri());
    }
}
