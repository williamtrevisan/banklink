<?php

declare(strict_types=1);

namespace Banklink\Banks\Itau\Actions\Card;

use Banklink\Banks\Itau\Entities\CardStatement;
use Banklink\Banks\Itau\Repositories\Contracts\CardRepository;
use Illuminate\Support\Collection;

final readonly class GetCardStatements
{
    public function __construct(
        private CardRepository $cardRepository,
    ) {}

    /**
     * @return Collection<int, CardStatement>
     */
    public function byCardId(string $cardId): Collection
    {
        return $this->cardRepository->statementBy($cardId);
    }
}
