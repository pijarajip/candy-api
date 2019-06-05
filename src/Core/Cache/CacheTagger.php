<?php

namespace GetCandy\Api\Core\Cache;

use Illuminate\Database\Eloquent\Model;

class CacheTagger
{
    /**
     * The Model
     *
     * @var Model
     */
    protected $model;

    /**
     * Any cache tag keys to avoid
     *
     * @var array
     */
    protected $except = [
        'asset',
        'assettransform',
    ];

    /**
     * The cache tags array
     *
     * @var array
     */
    protected $tags = [];

    /**
     * Set the model to generate tags for
     *
     * @param Model $model
     * @return self
     */
    public function for(Model $model)
    {
        $this->model = $model;
        $this->setTags($this->model);
        return $this;
    }

    /**
     * Gets the tags
     *
     * @return \Illuminate\Support\Collection
     */
    public function getTags()
    {
        return collect($this->tags);
    }

    /**
     * Get the cache tag key for a model
     *
     * @param Model $model
     * @return void
     */
    public function getTagKey(Model $model)
    {
        return  $this->getTagPrefix($model) . '_' . $model->id;
    }

    /**
     * Get the tag prefix for the model
     *
     * @param Model $model
     * @return void
     */
    protected function getTagPrefix(Model $model)
    {
        return strtolower(class_basename($model));
    }

    /**
     * Sets the cache tags based on a model
     *
     * @param Model $model
     * @return void
     */
    protected function setTags(Model $model)
    {
        if (!in_array($this->getTagPrefix($model), $this->except)) {
            $this->tags[] = $this->getTagKey($model);
            foreach ($model->getRelations() as $relation) {
                if (is_iterable($relation)) {
                    foreach ($relation as $r) {
                        $this->setTags($r);
                    }
                } else {
                    $this->setTags($relation);
                }
            }
        }
    }
}
