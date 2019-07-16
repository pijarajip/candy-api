<?php

namespace GetCandy\Api\Http\Resources\Settings;

use GetCandy\Api\Http\Resources\AbstractResource;

class SettingResource extends AbstractResource
{
    public function payload()
    {
        return array_merge([
            'name' => $this->name,
            'handle' => $this->handle,
        ], $this->config ? $this->config->toArray() : []);
    }

    public function includes()
    {
        return [];
    }
}
