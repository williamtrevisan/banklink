<?php

declare(strict_types=1);

namespace Banklink\Banks\Itau\Actions\Card;

use Banklink\Banks\Itau\Entities\CardStatement;
use Banklink\Banks\Itau\Repositories\Contracts\CardRepository;

final readonly class GetCardStatements
{
    public function __construct(
        private CardRepository $cardRepository,
    ) {}

    /**
     * @return CardStatement[]
     */
    public function byCardId(string $cardId): array
    {
        return $this->cardRepository->statementBy($cardId);
    }
}
