<?php

declare(strict_types=1);

namespace Banklink\Banks\Itau\Actions\Card;

use Banklink\Banks\Itau\Repositories\Contracts\CardRepository;
use Banklink\Support\PageParser;

final readonly class GetAllCards
{
    public function __construct(
        private CardRepository $cardRepository,
    ) {}

    public function handle(mixed $passable): mixed
    {
        $operation = PageParser::make()
            ->html($passable)
            ->extract('urlContingencia', '/if\s*\([^)]*\)\s*\{\s*urlContingencia\s*=\s*"([^"]+)"/');

        session()->put('card_operation', $operation);

        return $this->cardRepository->all();
    }
}
