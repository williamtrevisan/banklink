<?php

declare(strict_types=1);

namespace Banklink\Banks\Itau\Repositories;

use Banklink\Banks\Itau\Entities\Card;
use Banklink\Banks\Itau\Entities\CardStatement;
use Banklink\Banks\Itau\Repositories\Contracts\CardRepository;
use Illuminate\Http\Client\Factory;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Collection;

final readonly class CardHttpRepository implements CardRepository
{
    public function __construct(public Factory|PendingRequest $http) {}

    public function details(): string
    {
        return $this->http
            ->withHeaders([
                'op' => session()->pull('card_details_operation'),
                'X-Auth-Token' => session()->get('auth_token'),
                'X-Flow-ID' => session()->get('flow_id'),
                'X-Client-ID' => session()->get('client_id'),
                'X-Requested-With' => 'XMLHttpRequest',
            ])
            ->post('/router-app/router')
            ->body();
    }

    public function all(): Collection
    {
        return $this->http
            ->replaceHeaders([
                'op' => session()->pull('card_operation'),
            ])
            ->post('/router-app/router', [
                'secao' => 'Cartoes',
                'item' => 'Home',
            ])
            ->collect('object.data')
            ->map(fn (array $card): Card => Card::from($card));
    }

    public function statementBy(string $cardId): Collection
    {
        return $this->http
            ->replaceHeaders([
                'op' => session()->pull('card_statement_operation'),
            ])
            ->withBody($cardId)
            ->post('/router-app/router')
            ->collect('object.faturas')
            ->map(fn (array $statement): CardStatement => CardStatement::from($cardId, $statement));
    }
}
