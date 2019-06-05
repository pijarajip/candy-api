<?php

namespace GetCandy\Api\Core\Cache;

use Log;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Cache\CacheManager;

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

    public function __construct(CacheTagger $tagger, CacheManager $cache)
    {
        $this->tagger = $tagger;
        $this->config = config('getcandy.endpoints.cache', []);
        $this->cacheType = $this->config['driver'] ?? 'default';

        if ($this->cacheType == 'default') {
            $this->cacheType = config('cache.default');
        }

        $this->cache = $cache->driver($this->cacheType);
    }

    public function handle(JsonResource $resource, Request $request)
    {
        /**
         * If the cache store can't support tags, just return the resource
         */
        if (in_array($this->cacheType, $this->exceptConnections)) {
            Log::error("Cache store [{$this->cacheType}] not supported for endpoint caching");
            return $resource;
        }

        $cacheKey = $this->getCacheKey($request);
        $tags = $this->tagger->for($resource->resource)->getTags();

        return $this->cache->tags($tags)->remember($cacheKey, 60, function () use ($resource) {
            return $resource->response()->getContent();
        });
    }

    protected function getCacheKey($request)
    {
        return md5($request->getRequestUri());
    }
}
