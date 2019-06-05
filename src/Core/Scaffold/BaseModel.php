<?php

namespace GetCandy\Api\Core\Scaffold;

use GetCandy\Api\Core\Traits\Hashids;
use Illuminate\Database\Eloquent\Model;
use GetCandy\Api\Core\Routes\Models\Route;
use Illuminate\Database\Eloquent\Concerns\HasEvents;
use GetCandy\Api\Core\Cache\CacheTagger;
use GetCandy\Api\Core\Cache\ResponseCache;

abstract class BaseModel extends Model
{
    use Hashids, HasEvents;

    protected $hashids = 'main';

    public $custom_attributes = [];

    /**
     * Fire the given event for the model.
     *
     * @param  string  $event
     * @param  bool  $halt
     * @return mixed
     */
    protected function fireModelEvent($event, $halt = true)
    {
        if (! isset(static::$dispatcher)) {
            return true;
        }

        if ($event == 'saved') {
            $cacheTag = app()->make(CacheTagger::class)->getTagKey($this);
            app()->make(ResponseCache::class)->flush($cacheTag);
        }

        // First, we will get the proper method to call on the event dispatcher, and then we
        // will attempt to fire a custom, object based event for the given event. If that
        // returns a result we can return that result, or we'll call the string events.
        $method = $halt ? 'until' : 'fire';
        $result = $this->filterModelEventResults(
            $this->fireCustomModelEvent($event, $method)
        );
        if ($result === false) {
            return false;
        }
        return ! empty($result) ? $result : static::$dispatcher->{$method}(
            "eloquent.{$event}: ".static::class, $this
        );
    }

    public function getSettingsAttribute()
    {
        $settings = app('api')->settings()->get($this->settings);
        if (! $settings) {
            return [];
        }

        return $settings->content;
    }

    public function setCustomAttribute($key, $value)
    {
        $this->custom_attributes[$key] = $value;

        return $this;
    }

    public function getCustomAttribute($key)
    {
        return $this->custom_attributes[$key] ?? null;
    }

    /**
     * Scope a query to only include enabled.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeEnabled($query)
    {
        return $query->where('enabled', '=', true);
    }

    /**
     * Scope a query to only include the default record.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDefault($query)
    {
        return $query->where('default', '=', true);
    }

    public function routes()
    {
        return $this->morphMany(Route::class, 'element');
    }

    /**
     * Determine if the given relationship (method) exists.
     *
     * @param  string  $key
     * @return bool
     */
    public function hasRelation($key)
    {
        // If the key already exists in the relationships array, it just means the
        // relationship has already been loaded, so we'll just return it out of
        // here because there is no need to query within the relations twice.
        if ($this->relationLoaded($key)) {
            return true;
        }

        // If the "attribute" exists as a method on the model, we will just assume
        // it is a relationship and will load and return results from the query
        // and hydrate the relationship's value on the "relationships" array.
        if (method_exists($this, $key)) {
            //Uses PHP built in function to determine whether the returned object is a laravel relation
            return is_a($this->$key(), "Illuminate\Database\Eloquent\Relations\Relation");
        }

        return false;
    }
}
