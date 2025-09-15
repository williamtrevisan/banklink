<?php

declare(strict_types=1);

namespace Banklink\Banks\Itau\Repositories;

use Illuminate\Http\Client\Factory;
use Illuminate\Http\Client\PendingRequest;

final readonly class MenuHttpRepository
{
    public function __construct(
        private Factory|PendingRequest $http,
    ) {}

    public function get(string $operation): string
    {
        return $this->http
            ->replaceHeaders([
                'op' => $operation,
            ])
            ->post('/router-app/router')
            ->body();
    }

    public function load(string $operation): void
    {
        $this->http
            ->withHeaders([
                'op' => $operation,
                'segmento' => 'VAREJO',
                'X-Auth-Token' => session()->get('auth_token'),
                'X-CLIENT-ID' => session()->get('client_id'),
                'X-FLOW-ID' => session()->get('flow_id'),
                'X-Requested-With' => 'XMLHttpRequest',
            ])
            ->post('/router-app/router');
    }
}
