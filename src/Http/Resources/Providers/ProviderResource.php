<?php

namespace GetCandy\Api\Http\Resources\Providers;

use GetCandy\Api\Http\Resources\AbstractResource;

class ProviderResource extends AbstractResource
{
    public function payload()
    {
        $data = [
            'name' => $this->getName(),
        ];

        if (method_exists($provider, 'getClientToken')) {
            $data['client_token'] = $this->getClientToken();
        }

        if (method_exists($provider, 'getTokenExpiry')) {
            $data['exires_at'] = $this->getTokenExpiry();
        }
        return $data;
    }

    public function includes()
    {
        return [];
    }
}
