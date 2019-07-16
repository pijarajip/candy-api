<?php

namespace GetCandy\Api\Http\Resources\Layouts;

use GetCandy\Api\Http\Resources\AbstractResource;

class LayoutResource extends AbstractResource
{
    public function payload()
    {
        return [
            'id' => $this->encoded_id,
            'name' => $this->name,
            'handle' => $this->handle,
            'type' => $this->type,
        ];
    }

    public function includes()
    {
        return [
            'element' => ['data' => $this->whenLoaded('element', function () {
                // Need to guess the element
                $class = class_basename(get_class($this->element));
                $resource = 'GetCandy\Api\Http\Resources\\' . str_plural($class) . '\\' . $class . 'Resource';
                if (class_exists($resource)) {
                    return new $resource($this->element);
                }
                return null;
            })]
        ];
    }
}
