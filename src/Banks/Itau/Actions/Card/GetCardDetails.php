<?php

declare(strict_types=1);

namespace Banklink\Banks\Itau\Actions\Card;

use Banklink\Banks\Itau\Repositories\Contracts\CardRepository;
use Banklink\Support\PageParser;
use Closure;

final readonly class GetCardDetails
{
    public function __construct(
        private CardRepository $cardRepository,
    ) {}

    public function handle(mixed $passable, Closure $next): mixed
    {
        $operation = PageParser::make()
            ->html($details = $this->cardRepository->details())
            ->extract('buscarDadosCompletos', '/buscarDadosCompletos[\s\S]*?url:\s*"([^"]+)"/');

        session()->put('card_statement_operation', $operation);

        return $next($details);
    }
}
