<?php

namespace GetCandy\Api\Http\Resources\Currencies;

use GetCandy\Api\Http\Resources\AbstractResource;

class CurrencyResource extends AbstractResource
{
    public function payload()
    {
        return [
            'id' => $this->encoded_id,
            'name' => $this->name,
            'code' => $this->code,
            'format' => $this->format,
            'decimal' => $this->decimal_point,
            'thousand' => $this->thousand_point,
            'exchange_rate' => $this->exchange_rate,
            'enabled' => (bool) $this->enabled,
            'default' => (bool) $this->default,
        ];
    }
}
