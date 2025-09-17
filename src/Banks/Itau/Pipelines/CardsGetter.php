<?php

declare(strict_types=1);

namespace Banklink\Banks\Itau\Pipelines;

use Banklink\Banks\Itau\Actions\Card\GetAllCards;
use Banklink\Banks\Itau\Actions\Card\GetCardDetails;
use Banklink\Entities\Card;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Collection;

final class CardsGetter
{
    /**
     * @return Collection<int, Card>
     */
    public function get(): Collection
    {
        return app(Pipeline::class)
            ->through([
                GetCardDetails::class,
                GetAllCards::class,
            ])
            ->thenReturn();
    }
}
